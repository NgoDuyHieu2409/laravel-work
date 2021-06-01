<?php

namespace App\Http\Services;

use App\Models\Work;
use App\Models\User as Worker;
use App\Models\WorkerReview;
use Carbon\Carbon;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Storage;

class WorkerService
{
    public function getWorkerIds($worker_id)
    {
        $worker = Worker::findOrFail($worker_id);
        return $worker;
    }

    public function getWorkerReview($worker_id)
    {
        $worker_reviews = WorkerReview::where('worker_id', $worker_id)->get()->groupBy(['work_id']);
        $new_worker_review = [];

        foreach($worker_reviews as $work_id => $worker_reivews){
            $work_name = Work::findOrFail($work_id)->title;
            foreach($worker_reivews as $worker_reivew){
                $worker_reivew->photo_url = $worker_reivew->home->profile_photo_path ? Storage::url($worker_reivew->home->profile_photo_path) : Voyager::image($worker_reivew->home->avatar);
                $worker_reivew->home_name = $worker_reivew->home->name;
                $worker_reivew->format_time = Carbon::parse($worker_reivew->created_at)->format('d/m/Y');
            }

            $new_worker_review[$work_name] = $worker_reivews;
        }

        return $new_worker_review;
    }
}
