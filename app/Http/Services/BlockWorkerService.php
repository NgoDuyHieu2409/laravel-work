<?php

namespace App\Http\Services;

use App\BlockWorker;
use Illuminate\Support\Facades\Auth;

class BlockWorkerService
{
    public function createBlockWorker($request)
    {
        $favorite = new BlockWorker();
        $favorite->fill([
            'worker_id' => $request->worker_id,
            'home_id' => Auth::id(),
        ])->save();
    }

    public function getWorkerIds()
    {
        $worker_ids = BlockWorker::pluck('worker_id')->toArray();

        return array_unique($worker_ids);
    }

}
