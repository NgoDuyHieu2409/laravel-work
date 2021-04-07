<?php

namespace App\Services;

use App\Work;
use App\Worker;
use App\WorkerReview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkerService
{
    public function getWorkerIds($worker_id)
    {
        $worker = Worker::findOrFail($worker_id);
        $worker->avatar = $worker->photo ? Storage::url($worker->photo) : '/_Template/global_assets/images/placeholders/placeholder.jpg';
        return $worker;
    }

    public function getWorkerReview($worker_id)
    {
        $worker_reviews = WorkerReview::where('worker_id', $worker_id)->get()->groupBy(['work_id']);
        $new_worker_review = [];

        foreach($worker_reviews as $work_id => $worker_reivews){
            $work_name = Work::findOrFail($work_id)->title;
            foreach($worker_reivews as $worker_reivew){
                $worker_reivew->photo_url = $worker_reivew->home->photo_url ? Storage::url($worker_reivew->home->photo_url) : '/_Template/global_assets/images/placeholders/placeholder.jpg';
                $worker_reivew->home_name = $worker_reivew->home->name;
                $worker_reivew->format_time = Carbon::parse($worker_reivew->created_at)->format('Y年m月d日');
            }

            $new_worker_review[$work_name] = $worker_reivews;
        }

        return $new_worker_review;
    }
}
