<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Traits\AppUtility;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkApplication;
use App\Enums\WorkApplicationStatus;
use App\Models\City;

class HomeController extends Controller
{
    use AppUtility;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'category_id' => $request->category,
            'occupation_id' => $request->occupation,
            'worktime_start_at' => [
                'worktime_start_at',
                '>=',
                $request->form_date
            ],
            'worktime_end_at' => [
                'worktime_end_at',
                '<=',
                $request->to_date
            ],
        ];
        
        if($request->hourly_wage) {
            switch ($request->hourly_wage) {
                case 1:
                    $whereHourlyWage = [
                        'hourly_wage' => ['hourly_wage', '<', 10000]
                    ];
                    break;
                case 2:
                    $whereHourlyWage = [
                        ['hourly_wage' => ['hourly_wage', '>=', 10000]],
                        ['hourly_wage' => ['hourly_wage', '<', 15000]]
                    ];
                    break;
                case 3:
                    $whereHourlyWage = [
                        ['hourly_wage' => ['hourly_wage', '>=', 15000]],
                        ['hourly_wage' => ['hourly_wage', '<', 18000]]
                    ];
                    break;
                            
                case 4:
                    $whereHourlyWage = [
                        ['hourly_wage' => ['hourly_wage', '>=', 18000]]
                    ];
                    break;
                            
                default:
                    $whereHourlyWage = [];
                    break;
            };

            $filter[] = $whereHourlyWage;
        }

        $filter = $this->formatSearchFilters($filter);
        $works = Work::where($filter);

        if($request->city){
            $works = $works->whereHas('company', function($query) use($request){
                return $query->where('city', $request->city);
            });
        }

        if($request->work_name){
            $works = $works->search($request->work_name);
        }

        $works = $works->orderBy('id', 'desc')->paginate(10);
        foreach ($works as $key => $work) {
            $work->work_type =  $work->occupation_id ? $this->getValueItemToArray(setting('admin.occupations'), $work->occupation_id) : null;
        }

        $workTags = $this->getItemStringToArray(setting('admin.tags'));
        $workSkills = $this->getItemStringToArray(setting('admin.skills'));

        $advancedSearch = [
            'search_hourly_wage' => [
                1 => 'Dưới 10,000/giờ',
                2 => '10,000 - 15,000/giờ', 
                3 => '15,000 - 18,000/giờ',
                4 => 'Trên 18,000/giờ',
            ],
            'occupations' => $this->getItemStringToArray(setting('admin.occupations')),
            'categories' => $this->getItemStringToArray(setting('admin.categories')),
            'citys' => City::pluck('name', 'id'),
        ];

        $outstandingWorks = Work::getOutstanding();

        return view('homes.works.index')->with(compact('works', 'workTags', 'workSkills', 'advancedSearch', 'outstandingWorks', 'request'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $slug = null)
    {
        $work = Work::findOrFail($id);
        $qualification_id = $work->work_qualifications->pluck('qualification_id')->toArray();
        $skill_ids = $work->work_skills->pluck('skill_id')->toArray();
        $tag_ids = $work->work_tags->pluck('tag_id')->toArray();

        $work->work_type =  $work->occupation_id ? $this->getValueItemToArray(setting('admin.occupations'), $work->occupation_id) : null;
        $work->category_name =  $work->category_id ? $this->getValueItemToArray(setting('admin.categories'), $work->category_id) : null;
        $work->qualification_name =  $this->getItemToArray($this->getItemStringToArray(setting('admin.qualifications')), $qualification_id);
        $work->skills =  explode( ', ', $this->getItemToArray($this->getItemStringToArray(setting('admin.skills')), $skill_ids));
        $work->tags =  $this->getItemToArray($this->getItemStringToArray(setting('admin.tags')), $tag_ids);

        return view('homes.works.show')->with(compact('work'));
    }

    public function applyWork(Request $request)
    {
        try {
            $worker_id = Auth::id();
            $job = WorkApplication::where('work_id', $request->work_id)->where('worker_id', $worker_id)->first();
            if ($job) {
                return response()->json([
                    'status' => false,
                    'message' => "Bạn đã nộp hồ sơ cho công việc này!"
                ]);
            }

            // Create Work Application (apply job)
            $workApplication = WorkApplication::create([
                'work_id' => $request->work_id,
                'worker_id' => $worker_id,
                'status' => WorkApplicationStatus::APPLYING,
            ]);

            // save room id firebase
            $workApplication->room_id = $workApplication->id;
            $workApplication->save();
            return response()->json([
                'status' => true,
                'message' => "Bạn đã nộp hồ sơ cho công việc thành công!"
            ]);

        } catch (\Exception $e) {
            // Transaction Rollback
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }
}
