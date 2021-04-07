<?php

namespace App\Services;

use App\Enums\WorkApplicationStatus;
use App\Http\Resources\WorksResource;
use App\Work;
use App\WorkApplication;
use App\Worker;
use App\WorkRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkApplicationService
{
    public function getApplicationByWork($work_id)
    {
        $work = Work::findOrFail($work_id);
        $worker_ids = WorkApplication::where('work_id', $work_id)->where('status', WorkApplicationStatus::APPLYING)->pluck('worker_id')->toArray();
        $workers = Worker::findOrFail($worker_ids);

        $workers = collect($workers)->map(function($worker) use($work){
            $worker->work_name = $work->title;
            return $worker;
        });

        return $workers;
    }

    public function updateApplication($request)
    {
        $worker_id = $request->worker_id;
        $work_id = $request->work_id;
        $application = WorkApplication::where('work_id', $work_id)->where('worker_id', $worker_id)->first();

        $application->status = WorkApplicationStatus::ASSIGNED;
        $application->assigned_at = Carbon::now();
        $application->save();

        $work_record = WorkRecord::where('work_application_id', $application->id)->first();
        if(!$work_record){
            return false;
        }

        $work_record->fixed_at = Carbon::now();
        $work_record->fixed_yn  = 'y';
        $work_record->save();
    }
}
