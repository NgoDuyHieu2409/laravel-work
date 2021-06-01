<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\WageCalculatorHelper;

use App\Models\Work;
use App\Models\WorkApplication;
use App\Models\Timecard;
use App\Models\User;
use App\Models\WorkRecord;
use App\Http\Controllers\APIController;
use App\Models\UserWorkTotal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use Kreait\Firebase\Auth;

class CheckoutController extends ApiController
{

    private $auth;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(Auth $auth)
    // {
    //     // $this->middleware('auth:admin');
    //     $this->auth = $auth;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $uid = $request->uid;
        $worker = User::findOrFail($uid);
        if (!$worker) {
            return $this->responseUnprocessable("Không tìm thấy người dùng.");
        }
        
        $home = User::find($request->home_id);
        $work = Work::find($request->work_id);

        // Kiểm tra thời gian trả phòng
        // Thời gian bắt đầu công việc sau thời gian hiện tại。
        if ($work->worktime_start_at >= date("Y-m-d H:i:s")) {
            return $this->responseUnprocessable(trans('message.errors_text_time_checkout'));
        }

        $work_application = WorkApplication::where([
            'work_id' => $work->id,
            'worker_id' => $worker->id,
            'status' => Config('const.WorkApplications.STATUS_ASSIGNED'),
            'confirm_yn' => Config('const.WorkApplications.CONFIRM_STATUS.YES'),
        ])->first();

        if (!$work_application) {
            return $this->responseUnprocessable(trans('message.job_cannot_be_found'));
        }

        $timecard = Timecard::where([
            'worker_id' => $worker->id,
            'home_id' => $request->home_id,
            'work_id' => $request->work_id,
        ])->first();

        if(!$timecard) {
            $timecard = new Timecard();
            $timecard->worker_id = $worker->id;
            $timecard->home_id = $home->id;
            $timecard->work_id = $work->id;
            $timecard->checkin_at = date('Y-m-d H:i:s');
        } else {
            if ($timecard->checkout_at != NULL) {
                return $this->responseUnprocessable(trans('message.job_cannot_be_found'));
            }
        }

        $wageCalculator = new WageCalculatorHelper(
            $work,
            $work->worktime_start_at,
            $work->worktime_end_at,
            Carbon::parse($work->resttime_start_at)->diffInMinutes($work->resttime_end_at, false),
        );

        DB::beginTransaction();
        try {
            $timecard->checkout_at = Carbon::now();
            
            if ($timecard->save()) {
                $workRecord =  WorkRecord::where([
                    'worker_id' => $worker->id,
                    'user_id' => $home->id,
                    'work_id' => $work->id
                ])->orderByDesc('id')->first();

                if (!$workRecord) {
                    $workRecord = new WorkRecord();
                }

                $workRecord->company_id = $home->company_id;
                $workRecord->worker_id = $worker->id;
                $workRecord->user_id = $home->id;
                $workRecord->work_id = $work->id;
                $workRecord->work_date = Carbon::now();
                $workRecord->ovetime_percentages  = Config('const.overtime_percentage');
                $workRecord->nighttime_percentages = Config('const.nighttime_percentages');
                $workRecord->transfer_request_status = Config('const.WorkRecords.TRANSFER_REQUEST_STATUS.NO');
                $workRecord->commission_fee = $wageCalculator->getCommissoinFee();
                $workRecord->commission_fee_tax = $wageCalculator->getCommissoinFeeTax();
                $workRecord->commission_fee_tax_rate = Config('const.tax_rate');
                $workRecord->fixed_yn  = Config('const.WorkRecords.FIXED_YN.NO');
                $workRecord->title = $work->title;
                $workRecord->scheduled_worktime_start_at = $work->worktime_start_at;
                $workRecord->scheduled_worktime_end_at = $work->worktime_end_at;
                $workRecord->scheduled_resttime_start_at = $work->resttime_start_at;
                $workRecord->resttime_minutes = $work->resttime_minutes;
                $workRecord->scheduled_resttime_end_at = $work->resttime_end_at;
                $workRecord->worktime_start_at = $work->worktime_start_at;
                $workRecord->worktime_end_at = $work->worktime_end_at;
                $workRecord->hourly_wage = $work->hourly_wage;
                $workRecord->transportation_fee = $work->transportation_fee;
                $calculatorwWrktime = (Carbon::parse($work->worktime_start_at)->diffInMinutes($work->worktime_end_at, false) - $work->resttime_minutes) / 60;
                $baseWorktime  = $calculatorwWrktime > 0 ? $calculatorwWrktime : 0;
                $workRecord->base_worktime = $baseWorktime;
                $nighttimeWorktime = $wageCalculator->getTotalNightTimeMinutes() > 0 ? $baseWorktime : NULL;
                $overtimeWorktime = 0;
                $workRecord->overtime_worktime   = $overtimeWorktime > 0 ? $overtimeWorktime : 0;
                $workRecord->nighttime_worktime = $nighttimeWorktime;
                $workRecord->base_wage = $baseWorktime * $work->hourly_wage;
                $workRecord->total_wage = ($baseWorktime * $work->hourly_wage) + $work->transportation_fee;

                // update status work applications =>  "4:  業務終了"
                $workApplication = WorkApplication::where([
                    'work_id' => $work->id,
                    'worker_id' => $worker->id,
                ])->first();

                $workApplication->status = Config('const.WorkApplications.STATUS_FINISHED');
                $workApplication->save();

                $workRecord->work_application_id = $workApplication->id;
                $workRecord->save();

                $totalWorktime = $worker->total_worktime + $baseWorktime + $overtimeWorktime + $nighttimeWorktime;
                
                if(!$userWorkTotal = $worker->work){
                    $userWorkTotal = new UserWorkTotal;
                }
                $userWorkTotal->user_id = $worker->id;
                $userWorkTotal->total_worktime = round($totalWorktime, 2);
                $userWorkTotal->total_workcount = $worker->total_workcount + 1;
                $userWorkTotal->save();
            }
            DB::commit();
            $data = [
                'work_application_id' => $work_application->id
            ];
            return $this->responseData(trans('message.success'), $data);
        } catch (\Exception $exception) {
            DB::rollBack();

            dd($exception);
            return $this->responseUnprocessable(trans('message.error_save_data'));
        }
    }

    /**
     * @param $baseWorktime
     * @param $overtimeWorktime
     * @param $nighttimeWorktime
     */
    public function calculateTotalWage($baseWorktime, $overtimeWorktime, $nighttimeWorktime, $hourlyWage, $transportationFee)
    {
        $totalWage = 0;
        if ($baseWorktime != 0 && $overtimeWorktime == 0 && $nighttimeWorktime == 0) {
            $totalWage = ($baseWorktime * $hourlyWage) + $transportationFee;
        } elseif ($baseWorktime != 0 && $overtimeWorktime != 0 && $nighttimeWorktime == 0) {
            $totalWage = ($baseWorktime * $hourlyWage) + $overtimeWorktime * ($hourlyWage * 1.25) + $transportationFee;
        } elseif ($baseWorktime != 0 && $overtimeWorktime == 0 && $nighttimeWorktime != 0) {
            $totalWage = ($baseWorktime * $hourlyWage) + $nighttimeWorktime * ($hourlyWage * 1.25) + $transportationFee;
        } elseif ($baseWorktime == 0 && $overtimeWorktime == 0 && $nighttimeWorktime != 0) {
            $totalWage =  $nighttimeWorktime * ($hourlyWage * 1.25) + $transportationFee;
        } elseif ($baseWorktime != 0 && $overtimeWorktime != 0 && $nighttimeWorktime != 0) {
            $totalWage =  ($baseWorktime * $hourlyWage) + $nighttimeWorktime * ($hourlyWage * 1.25) + $overtimeWorktime * ($hourlyWage * 1.25) +  $transportationFee;
        } elseif($baseWorktime == 0 && $overtimeWorktime != 0 && $nighttimeWorktime != 0) {
            $totalWage =  $nighttimeWorktime * ($hourlyWage * 1.25) + $overtimeWorktime * ($hourlyWage * 1.25) + $nighttimeWorktime * $hourlyWage + $transportationFee;
        }

        return $totalWage;
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
    public function store(Request $request)
    {
        //
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
