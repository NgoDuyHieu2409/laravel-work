<?php

namespace App\Http\Services;

use App\Enums\WorkApplicationStatus;
use App\Models\WorkApplication;
use App\Models\User as Worker;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Facades\Voyager;

class MessageService
{

    public function __construct(WorkService $workService)
    {
        $this->workService = $workService;
    }


    public function getworkerByWork($work_id)
    {
        $workerIds = $this->workService->getWorkerUid($work_id);

        $workers = Worker::findOrFail($workerIds);

        foreach($workers as $worker){
            $worker->avatar = $worker->profile_photo_path ? Storage::url($worker->profile_photo_path) : Voyager::image($worker->avatar);
        }

        return $workers;
    }

    public function getRoomIdbyWorker($work_id, $workerId)
    {
        $worker = Worker::findOrFail($workerId);
        $work = Work::findOrFail($work_id);

        if($worker && $work){
            $worker_id = $worker->id;
            $room_id = WorkApplication::where('work_id', $work_id)
                ->where('worker_id', $worker_id)
                ->where('status', WorkApplicationStatus::ASSIGNED)
                ->where('confirm_yn',config('const.WorkApplications.CONFIRM_STATUS.YES'))
                ->first()
                ->room_id;
        }

        return $room_id ?? 0;
    }

    public function getRoomChat()
    {
        $work_ids = $this->getWorkId();
        $works = Work::findOrFail($work_ids);

        $works = collect($works)->map(function($work){
            $worktime_start = Carbon::parse($work->worktime_start_at)->format('d/m/Y H:i');
            $worktime_end = Carbon::parse($work->worktime_end_at)->format('d/m/Y H:i');
            $work->worktime = $worktime_start . ' ~ ' . $worktime_end;
            return $work;
        });

        return $works;
    }

    public function getWorkId()
    {
        $home_id = Auth::id();
        $work_ids = Work::where('user_id', $home_id)
                ->whereHas('work_applications', function($query){
                    $query->where('status', WorkApplicationStatus::ASSIGNED)
                        ->where('confirm_yn', config('const.WorkApplications.CONFIRM_STATUS.YES'));
                })
                ->pluck('id')
                ->toArray();

        return $work_ids;
    }

    public function getAllRoomIdByWork()
    {
        $work_ids = $this->getWorkId();

        $applications = WorkApplication::whereIn('work_id', $work_ids)->get()->groupBy('work_id');

        $room_ids = [];
        foreach($applications as $work_id => $application){
            foreach($application as $value){
                $room_ids[$work_id][] = $value->room_id;
            }
        }
        return $room_ids;
    }

    public function getRoomIdsbyWork($work_id, $workerIds)
    {
        $work_id = is_array($work_id) ? $work_id : (array) $work_id; 
        $workerIds = is_array($workerIds) ? $workerIds : (array) $workerIds; 

        $room_ids = WorkApplication::whereIn('work_id', $work_id)
            ->whereIn('worker_id', $workerIds)
            ->where('status', WorkApplicationStatus::ASSIGNED)
            ->where('confirm_yn', config('const.WorkApplications.CONFIRM_STATUS.YES'))
            ->pluck('room_id');

        return $room_ids ?? [];
    }

    public function getWorkChatIdWorker()
    {
        $workerId = Auth::id();
        return Work::whereHas('work_applications', function($query) use($workerId){
                    $query->where('status', WorkApplicationStatus::ASSIGNED)
                        ->where('confirm_yn', config('const.WorkApplications.CONFIRM_STATUS.YES'))
                        ->where('worker_id', $workerId);
                });
    }
}
