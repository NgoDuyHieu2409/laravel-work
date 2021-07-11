<?php

namespace App\Http\Services;

use App\Enums\ModifyRequestStatus;
use App\Enums\WorkApplicationStatus;
use App\Models\ModifyRequest;
use App\Models\WorkApplication;
use App\Models\User as Worker;
use App\Models\WorkRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ModifyRequestService
{
    protected $workRecordService;

    public function __construct(WorkRecordService $workRecordService)
    {
        $this->workRecordService = $workRecordService;
    }

    public function getAll()
    {
        $user = Auth::user();
        $modify_requests = ModifyRequest::with(['work', 'worker'])
                    ->where('approval_status', ModifyRequestStatus::NO_APPROVE);
        
        if(!$user->hasRole('admin')){
            $modify_requests = $modify_requests->where('home_id', $user->id); 
        }
        
        $modify_requests = $modify_requests->get();

        foreach($modify_requests as $modify_request){
            if ($modify_request->work->worktime_start_at != $modify_request->scheduled_worktime_start_at) {
                $modify_request->text_scheduled = 'text-danger';
            }

            if ($modify_request->work->worktime_start_at != $modify_request->modify_worktime_start_at ||
                $modify_request->work->worktime_end_at != $modify_request->modify_worktime_end_at)
            {
                $modify_request->text_worktime = 'text-danger';
            }

            if ($modify_request->work->resttime_minutes != $modify_request->resttime_minutes) {
                $modify_request->text_resttime = 'text-danger';
            }

            if (($modify_request->work->base_wage + $modify_request->work->transportation_fee) != ($modify_request->base_wage + $modify_request->transportation_fee)) {
                $modify_request->text_base = 'text-danger';
            }

            $modify_request->total_paid = $modify_request->base_wage + $modify_request->ovetime_wage + $modify_request->nighttime_wage + $modify_request->transportation_fee;
        }
                    
        return $modify_requests;
    }

    public function updateStatus($id, $type = 'approve')
    {
        $modify = ModifyRequest::find($id);
        if($type == 'approve'){
            $modify->approval_status = ModifyRequestStatus::APPROVE;
            //Add new work record
            $record = $this->workRecordService->store($modify);
            //Update status work applicartion
            $application = WorkApplication::where('work_id', $modify->work_id)->where('worker_id', $modify->worker_id)->first();
            $application->status = WorkApplicationStatus::FINISH;
            $application->save();

            //update total_worktime and total_workcount
            $worker = Worker::findOrFail($modify->worker_id);
            $worker->total_worktime = $worker->total_worktime + $record->base_worktime + $record->overtime_worktime + $record->nighttime_worktime;
            $worker->total_workcount += 1;
            $worker->save();

        }
        else{
            $modify->approval_status = ModifyRequestStatus::REFUSE;
        }

        $modify->approved_at = Carbon::now();
        $modify->save();
    }
}