<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Work;
use App\Models\WorkApplication;
use App\Models\Timecard;
use App\Models\User;

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Auth;

class CheckinController extends ApiController
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

        // Nếu thời gian bắt đầu công việc muộn hơn 30 phút hoặc thời gian kết thúc công việc trước thời gian hiện tại 
        // (không thể kiểm tra công việc đã hoàn thành)
        // if ($work->worktime_start_at > date("Y-m-d H:i:s", strtotime("+" . Config('const.CHECKIN_BEFORE_MINUTE') . " minute"))
        //     || $work->worktime_end_at <= date("Y-m-d H:i:s")) {
        //     return $this->responseUnprocessable("Bạn có thể nhận phòng trước giờ làm việc 30 phút.");
        // }

        $work_application = WorkApplication::where([
            'work_id' => $request->work_id,
            'worker_id' => $worker->id,
            'status' => Config('const.WorkApplications.STATUS_ASSIGNED'),
            'confirm_yn' => Config('const.WorkApplications.CONFIRM_STATUS.YES'),
            ])->first();

        if (!$work_application) {
            return $this->responseUnprocessable(trans('message.job_cannot_be_found'));
        }

        if ($work->worktime_start_at >= date("Y-m-d H:i:s") ) {
            $checkinAt = $worktimeStartAt = $work->worktime_start_at;
        } else {
            $checkinAt = $worktimeStartAt = date('Y-m-d H:i:s');
        }

        DB::beginTransaction();
        try {
            $timecard = Timecard::where(['worker_id' => $worker->id, 'home_id' => $home->id, 'work_id' => $work->id])->first();

            if (!$timecard) {
                $timecard = new Timecard();
            }
            $timecard->worker_id = $worker->id;
            $timecard->home_id = $home->id;
            $timecard->work_id = $work->id;
            $timecard->checkin_at = $checkinAt;
            $timecard->save();
            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseUnprocessable(trans('message.you_have_already_checked_in'));
        }
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