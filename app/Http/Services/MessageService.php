<?php

namespace App\Services;

use App\Enums\WorkApplicationStatus;
use App\Message;
use App\Work;
use App\WorkApplication;
use App\Worker;
use App\WorkRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageService
{

    public function __construct(WorkService $workService)
    {
        $this->workService = $workService;
    }


    public function getworkerByWork($work_id)
    {
        $worker_uids = $this->workService->getWorkerUid($work_id);

        $workers = Worker::whereIn('uid', $worker_uids)->get();

        foreach($workers as $worker){
            $worker->avatar = $worker->photo ? Storage::url($worker->photo) : '/_Template/global_assets/images/placeholders/placeholder.jpg';
        }

        return $workers;
    }

    public function getRoomIdbyWorker($work_id, $worker_uid)
    {
        $worker = Worker::where('uid', $worker_uid)->first();

        if($worker){
            $worker_id = $worker->id;
            $room_id = WorkApplication::where('work_id', $work_id)
                ->where('worker_id', $worker_id)
                ->where('status', WorkApplicationStatus::ASSIGNED)
                ->first()
                ->room_id;
        }

        return $room_id ?? 0;
    }

    public function getRoomChat()
    {
        $home_id = Auth::id();
        $work_ids = $this->getWorkId();
        $works = Work::findOrFail($work_ids);

        $works = collect($works)->map(function($work) use($home_id, $work_ids){
            $worktime_start = Carbon::parse($work->worktime_start_at)->locale('ja')->isoFormat('LL(dddd) HH:mm');
            $worktime_end = Carbon::parse($work->worktime_end_at)->format('H:i');
            $work->worktime = $worktime_start . '~' . $worktime_end;
            return $work;
        });

        return $works;
    }

    public function getWorkId()
    {
        $home_id = Auth::id();
        $work_ids = Work::where('home_id', $home_id)
                ->whereHas('work_applications', function($query){
                    $query->where('status', WorkApplicationStatus::ASSIGNED)
                        ->where('confirm_yn', config('const.WorkApplications.CONFIRM_STATUS.YES'));
                })
                ->pluck('id')
                ->toArray();

        return $work_ids;
    }

    public function getRoomIdByWork()
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

    public function getRoomIdsbyWork($work_id, $worker_uids)
    {
        $worker_ids = Worker::whereIn('uid', $worker_uids)->pluck('id');

        $room_ids = WorkApplication::where('work_id', $work_id)
            ->whereIn('worker_id', $worker_ids)
            ->where('status', WorkApplicationStatus::ASSIGNED)
            ->pluck('room_id');

        return $room_ids ?? [];
    }
}
