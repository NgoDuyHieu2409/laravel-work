<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User as Worker;
use App\Traits\AppUtility;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkApplication;
use App\Enums\WorkApplicationStatus;
use App\Models\City;
use App\Models\FavoriteWork;
use App\Models\HomeReview;
use Illuminate\Support\Facades\DB;

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
                        ['hourly_wage', '<', 10000]
                    ];
                    break;
                case 2:
                    $whereHourlyWage = [
                        ['hourly_wage', '>=', 10000],
                        ['hourly_wage', '<', 15000]
                    ];
                    break;
                case 3:
                    $whereHourlyWage = [
                        ['hourly_wage', '>=', 15000],
                        ['hourly_wage', '<', 18000]
                    ];
                    break;
                            
                case 4:
                    $whereHourlyWage = [
                        ['hourly_wage', '>=', 18000]
                    ];
                    break;
                            
                default:
                    $whereHourlyWage = [];
                    break;
            };

            $filter = array_merge($filter, $whereHourlyWage);
        }

        $filter = $this->formatSearchFilters($filter);

        $worker_id = Auth::id();

        $workIds = WorkApplication::where('worker_id', $worker_id)->pluck('work_id');

        $works = Work::where($filter)->whereNotIN('id', $workIds);

        if($request->city){
            $works = $works->whereHas('company', function($query) use($request){
                return $query->where('city', $request->city);
            });
        }

        if($request->work_name){
            $works = $works->search($request->work_name);
        }

        $works = $works->orderBy('id', 'desc')->paginate(10);
        $favoriteWork = FavoriteWork::where('worker_id', $worker_id)->pluck('work_id')->toArray();
        foreach ($works->items() as $key => $work) {
            $work->work_type =  $work->occupation_id ? $this->getValueItemToArray(setting('admin.occupations'), $work->occupation_id) : null;
            $work->is_favorite = in_array($work->id, $favoriteWork);
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

        $workApplication = WorkApplication::where('worker_id', Auth::id())->pluck('work_id')->toArray();
        $work->is_applications = in_array($work->id, $workApplication);
        $outstandingWorks = Work::getOutstanding();

        return view('homes.works.show')->with(compact('work', 'outstandingWorks'));
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

    public function unapplyWork(Request $request)
    {
        try {
            $worker_id = Auth::id();
            $job = WorkApplication::where('work_id', $request->work_id)->where('worker_id', $worker_id)->first();
            if (!$job) {
                return response()->json([
                    'status' => false,
                    'message' => "Không tìm thấy công việc này"
                ]);
            }

            $job->delete();
            return response()->json([
                'status' => true,
                'message' => "Success!"
            ]);

        } catch (\Exception $e) {
            // Transaction Rollback
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public function favorite(Request $request)
    {
        DB::beginTransaction();
        try {
            $worker_id = Auth::id();
            $work = Work::find($request->work_id);
            if (!$work) {
                return response()->json([
                    'status' => false,
                    'message' => "Không tìm thấy công việc phù hợp!"
                ]);
            }

            FavoriteWork::where('work_id', $request->work_id)
                ->where('worker_id', $worker_id)
                ->delete();

            FavoriteWork::create([
                'work_id' => $request->work_id,
                'worker_id' => $worker_id,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "success"
            ]);

        } catch (\Exception $e) {
            // Transaction Rollback
            DB::rollback();
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public function unFavorite(Request $request)
    {
        DB::beginTransaction();
        try {
            $worker_id = Auth::id();
            $work = Work::find($request->work_id);
            if (!$work) {
                return response()->json([
                    'status' => false,
                    'message' => "Không tìm thấy công việc phù hợp!"
                ]);
            }

            // Create Work Application (apply job)
            FavoriteWork::where('work_id', $request->work_id)
                ->where('worker_id', $worker_id)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "success"
            ]);

        } catch (\Exception $e) {
            // Transaction Rollback
            DB::rollback();
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getFavoriteWork()
    {
        $worker_id = Auth::id();
        $outstandingWorks = Work::getOutstanding();
        $workTags = $this->getItemStringToArray(setting('admin.tags'));
        $workSkills = $this->getItemStringToArray(setting('admin.skills'));

        $workApplication = WorkApplication::where('worker_id', $worker_id)->pluck('work_id')->toArray();
        $workIds = FavoriteWork::where('worker_id', $worker_id)->pluck('work_id');
        $works = Work::whereIn('id', $workIds)->orderBy('id', 'desc')->paginate(10);
        
        foreach($works->items() as $work){
            $work->is_application = in_array($work->id, $workApplication);
        }

        return view('homes.works.favorite')->with(compact('works', 'outstandingWorks', 'workTags', 'workSkills'));
    }

    public function getEvaluatingWork(Request $request)
    {
        $worker_id = Auth::id();
        $isReview = $request->isReview ? true : false;

        $reviewWorkIds = [];
        $workApplication = WorkApplication::where('worker_id', $worker_id)
                                ->where('status', WorkApplicationStatus::FINISH);
        
        $reviewWorkIds = HomeReview::where('worker_id', $worker_id)->pluck('work_id')->toArray();
        if($isReview) {
            $workApplication = $workApplication->whereIn('work_id', $reviewWorkIds);
        }
        else {
            $workApplication = $workApplication->whereNotIn('work_id', $reviewWorkIds);
        }

        $workApplication = $workApplication->pluck('work_id')->toArray();

        $works = Work::whereIn('works.id', $workApplication)
                    ->leftJoin('home_reviews', 'home_reviews.work_id', '=', 'works.id')
                    ->where('home_reviews.worker_id', $worker_id)
                    ->orderBy('works.id', 'desc')->paginate(10);

        $outstandingWorks = Work::getOutstanding();
        $workTags = $this->getItemStringToArray(setting('admin.tags'));
        $workSkills = $this->getItemStringToArray(setting('admin.skills'));

        return view('homes.works.evaluating')->with(compact('works', 'outstandingWorks', 'workTags', 'workSkills', 'request'));
    }

    public function workerReviewJob(Request $request)
    {
        $work = Work::find($request->work_id);
        if(!$work){
            return response()->json([
                'status' => false,
                'message' => "Không tìm thấy công việc phù hợp!"
            ]);
        }

        DB::beginTransaction();
        try {
            $review = HomeReview::where('worker_id', Auth::id())->where('work_id', $work->id)->first();
            if(!$review){
                $review = new HomeReview();
            }

            $review->fill([
                'worker_id' => Auth::id(),
                'user_id'   => $work->user_id,
                'work_id'   => $work->id,
                'comment'   => $request->comment,
                'good_yn1'  => $request->good_yn1,
                'good_yn2'  => $request->good_yn2,
                'good_yn3'  => $request->good_yn3,
            ])->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Cảm ơn bạn đã đánh giá công việc.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function workerApplication(Request $request)
    {
        $worker_id = Auth::id();
        $outstandingWorks = Work::getOutstanding();
        $workTags = $this->getItemStringToArray(setting('admin.tags'));
        $workSkills = $this->getItemStringToArray(setting('admin.skills'));

        $workApplication = WorkApplication::where('worker_id', $worker_id)
                            ->where('status', WorkApplicationStatus::APPLYING)   
                            ->pluck('work_id')->toArray();
        $favoriteWork = FavoriteWork::where('worker_id', $worker_id)->pluck('work_id')->toArray();
        $works = Work::whereIn('id', $workApplication)->orderBy('id', 'desc')->paginate(10);
        
        foreach($works->items() as $work){
            $work->is_favorite = in_array($work->id, $favoriteWork);
        }

        return view('homes.works.application')->with(compact('works', 'outstandingWorks', 'workTags', 'workSkills'));
    }
}
