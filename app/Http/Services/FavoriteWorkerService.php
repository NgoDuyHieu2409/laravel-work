<?php

namespace App\Http\Services;

use App\Enums\WorkApplicationStatus;
use App\FavoriteWorker;
use App\WorkApplication;
use App\WorkRecord;
use Illuminate\Support\Facades\Auth;

class FavoriteWorkerService
{
    public function createFavoriteWorker($request)
    {
        $favorite = new FavoriteWorker();
        $favorite->fill([
            'worker_id' => $request->worker_id,
            'home_id' => Auth::id(),
        ])->save();
    }

    public function getAllWorkerId()
    {
        $home_id = Auth::id();
        return FavoriteWorker::where('home_id', $home_id)->pluck('worker_id')->toArray();
    }

    public function getAllFavorite()
    {
        $home_id = Auth::id();
        $favorites =  FavoriteWorker::where('home_id', $home_id)
                ->with('worker')
                ->get();

        $favorites = collect($favorites)->map(function($favorite){
            $favorite->total_works = $this->totalWorkByWorker($favorite->worker_id);
            $favorite->total_workTime = $this->totalWorkTimeByWorker($favorite->worker_id);
            return $favorite;
        });

                
        return $favorites;
    }

    public function getDetailFavorite($id)
    {
        $home_id = Auth::id();
        return FavoriteWorker::where('home_id', $home_id)->where('id', $id)
                ->with('worker')
                ->first();
    }

    public function deleteFavoriteWorker($id)
    {
        $favorite = FavoriteWorker::find($id);
        return $favorite->delete();
    }

    public function totalWorkByWorker($worker_id)
    {
        $applications = WorkApplication::where('worker_id', $worker_id)
                            ->whereIn('status', [WorkApplicationStatus::ASSIGNED, WorkApplicationStatus::FINISH])
                            ->where('confirm_yn', 'y')
                            ->count();
        
        return $applications;
    }

    public function totalWorkTimeByWorker($worker_id) 
    {
        $records = WorkRecord::select('base_worktime', 'overtime_worktime', 'nighttime_worktime')
                    ->where('worker_id', $worker_id)
                    ->get();

        $total_time = 0;
        if($records->toArray()){
            foreach($records as $record){
                $total_time = $total_time + $record->base_worktime + $record->overtime_worktime + $record->nighttime_worktime;
            }
        }            

        return $total_time;
    }
}
