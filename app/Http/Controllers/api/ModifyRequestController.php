<?php

namespace App\Http\Controllers\Api;

use App\Helpers\WageCalculatorHelper;
use App\Http\Requests\Api\Modify\ModifyRequest;
use App\Work;
use App\WorkApplication;
use Illuminate\Http\Request;
use App\Http\Resources\ModifyRequestsCollection;
use App\Worker;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Auth;

class ModifyRequestController extends ApiController
{

    private $auth;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Auth $auth)
    {
        // $this->middleware('auth:admin');
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $uid = $request->uid;
        $worker = $this->getWorker($uid);
        if (!$worker) {
            $this->responseUnprocessable(trans('message.user_not_found'));
        }
        //承認されたもの以外を取得
        $dataModify = DB::table('modify_requests')->where('worker_id', $worker->id)->get();

        return new ModifyRequestsCollection($dataModify);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModifyRequest $request)
    {
        try {
            $uid = $request->uid;
            $worker = Worker::where('uid', $uid)->first();
            if (!$worker) {
                return $this->responseUnprocessable('ユーザが見つかりません。');
            }
            $work_application_id = $request->work_application_id;
            $workApplication = WorkApplication::where('id', $work_application_id)
                ->where('worker_id', $worker->id)
                ->first();
            if (!$workApplication) {
                return $this->responseUnprocessable('該当の仕事が見つかりません。');
            }

            $work = Work::whereId($workApplication->work_id)->first();
            if (!$work) {
                return $this->responseUnprocessable('仕事が存在しません。');
            }

            $comment = $request->comment;
            $modify_worktime_start_at = $request->modify_worktime_start_at;
            $modify_worktime_end_at = $request->modify_worktime_end_at;
            $resttime_minutes = $request->resttime_minutes ?? 0;

            // $wageCalculator = new WageCalculatorHelper(
            //     $modify_worktime_start_at,
            //     $modify_worktime_end_at,
            //     $work->worktime_start_at,
            //     $work->worktime_end_at,
            //     $resttime_minutes,
            //     $work->hourly_wage,
            //     $work->transportation_fee,
            //     $work->ovetime_extra_percentages,
            //     $work->night_extra_percentages
            // );

            $wageCalculator = new WageCalculatorHelper(
                $work,
                $modify_worktime_start_at,
                $modify_worktime_end_at,
                $resttime_minutes
            );

            \App\ModifyRequest::create([
                'worker_id' => $worker->id,
                'home_id' => $work->home_id,
                'work_id' => $work->id,
                'comment' => $comment,
                'scheduled_worktime_start_at' => $work->worktime_start_at,
                'scheduled_worktime_end_at' => $work->worktime_end_at,
                'modify_worktime_start_at' => $modify_worktime_start_at,
                'modify_worktime_end_at' => $modify_worktime_end_at,
                'resttime_minutes' => $resttime_minutes,
                'ovetime_percentages' => $work->ovetime_extra_percentages,
                'nighttime_percentages' => $work->night_extra_percentages,
                'ovetime_wage' => $wageCalculator->getOverTimeWage(),
                'nighttime_wage' => $wageCalculator->getNightTimeWage(),
                'base_wage' => $wageCalculator->getBaseWage(),
                'transportation_fee' => $work->transportation_fee,
                'approval_status' => Config('const.ModifyRequests.APPROVAL_STATUS_UNAPPROVED')
            ]);

            return $this->responseSuccess();


        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPreview(ModifyRequest $request)
    {
        try {
            $uid = $request->uid;
            $worker = Worker::where('uid', $uid)->first();
            if (!$worker) {
                return $this->responseUnprocessable('ユーザが見つかりません。');
            }
            $work_application_id = $request->work_application_id;
            $workApplication = WorkApplication::where('id', $work_application_id)
                ->where('worker_id', $worker->id)
                ->first();
            if (!$workApplication) {
                return $this->responseUnprocessable('該当の仕事が見つかりません。');
            }

            $work = Work::whereId($workApplication->work_id)->first();
            if (!$work) {
                return $this->responseUnprocessable('仕事が存在しません。');
            }

            $comment = $request->comment;
            $modify_worktime_start_at = $request->modify_worktime_start_at;
            $modify_worktime_end_at = $request->modify_worktime_end_at;
            $resttime_minutes = $request->resttime_minutes ?? 0;

            $wageCalculator = new WageCalculatorHelper(
                $work,
                $modify_worktime_start_at,
                $modify_worktime_end_at,
                $resttime_minutes,
            );
            $data = [
                'worktime_start_at' => $work->worktime_start_at,
                'worktime_end_at' => $work->worktime_end_at,
                'modify_worktime_start_at' => $modify_worktime_start_at,
                'modify_worktime_end_at' => $modify_worktime_end_at,
                'resttime_minutes' => $resttime_minutes,
                'base_wage' => $wageCalculator->getBaseWage(),
                'transportation_fee' => $work->transportation_fee,
                'comment' => $comment,
                'total_wage' => $wageCalculator->getTotalWage()

            ];
            return response()->json([
                'data' => $data,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
