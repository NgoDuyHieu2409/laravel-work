<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Traits\AppUtility;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkApplication;
use App\Enums\WorkApplicationStatus;

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
        // $query = DB::table('works');
        // $query->selectRaw('*, TIMESTAMPDIFF(HOUR,worktime_start_at,worktime_end_at) as hours');

        // // sort
        // if ($request->sort_id) {
        //     switch ($request->sort_id) {
        //         case 1:
        //             $query->orderBy('created_at', 'DESC');
        //             break;
        //         case 2:
        //             $query->orderBy('base_wage', 'ASC');
        //             break;
        //         case 3:
        //             $query->orderBy('worktime_start_at', 'DESC');
        //             break;
        //         case 4:
        //             $query->orderBy('hours', 'DESC');
        //             break;
        //     }
        // }
        $works = Work::whereIn('status', [1, 2, 3])->get();
        foreach ($works as $key => $work) {
            $work->work_type =  $work->occupation_id ? $this->getValueItemToArray(setting('admin.occupations'), $work->occupation_id) : null;
        }

        // filter by recruitment_start_at
        // if ($request->worktime_start_at) {
        //     $time_form = Carbon::createFromFormat('Y-m-d', $request->worktime_start_at)->setTime(00,00,00);
        //     $time_to   = Carbon::createFromFormat('Y-m-d', $request->worktime_start_at)->setTime(23,59,59);
        //     $query->whereDate('worktime_start_at', '>=', $time_form)->whereDate('worktime_start_at', '<=', $time_to);
        // }

        // // filter by tags
        // if (isset($request->tags) && count($request->tags)) {
        //     $work_ids = array();

        //     $work_tags = WorkTag::whereIn('tag_id', $request->tags)->get();
        //     foreach ($work_tags as $work_tag) {
        //         $work_ids[] = $work_tag->work_id;
        //     }
        //     $query->whereIn('id', $work_ids);
        // }

        // // filter by prefectures
        // if (isset($request->prefs) && count(explode(',', $request->prefs))) {
        //     $prefs = explode(',', $request->prefs);
        //     $home_ids = array();
        //     $homes = Home::whereIn('pref', $prefs)->get();
        //     foreach ($homes as $home) {
        //         $home_ids[] = $home->id;
        //     }
        //     $query->whereIn('home_id', $home_ids);
        // }

        // // filter by worktime end
        // if ($request->worktime_end_at) {
        //     $query->whereTime('worktime_end_at', '<=', $request->worktime_end_at);
        // }
        // // filter by base wage
        // if ($request->base_wage) {
        //     $query->where('base_wage', '>=', $request->base_wage);
        // }
        return view('homes.works.index')->with(compact('works'));
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
        $skills_id = $work->work_skills->pluck('skill_id')->toArray();

        $work->work_type =  $work->occupation_id ? $this->getValueItemToArray(setting('admin.occupations'), $work->occupation_id) : null;
        $work->category_name =  $work->category_id ? $this->getValueItemToArray(setting('admin.categories'), $work->category_id) : null;
        $work->qualification_name =  $this->getItemToArray($this->getItemStringToArray(setting('admin.qualifications')), $qualification_id);
        $work->skills =  explode( ', ', $this->getItemToArray($this->getItemStringToArray(setting('admin.skills')), $skills_id));
        
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
