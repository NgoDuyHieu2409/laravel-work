<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\HomeReview;
use App\Models\Work;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkerReview;
use App\Models\WorkerSkill;
use Carbon\Carbon;

class WorkerReviewService
{
    protected $favoriteWorkrerService;

    public function __construct(FavoriteWorkerService $favoriteWorkrerService)
    {
        $this->favoriteWorkrerService = $favoriteWorkrerService;
    }

    public function getWokerIdNotReview($work_id)
    {
        $user_id = Auth::id();
        $woker_all = WorkRecord::where('user_id', $user_id)->where('work_id', $work_id)->pluck('worker_id')->toArray();
        $woker_all = array_unique($woker_all);

        $woker_ids_review = WorkerReview::where('user_id', $user_id)->where('work_id', $work_id)->pluck('worker_id')->toArray();

        $woker_ids = array_diff($woker_all, $woker_ids_review);

        $workers = User::find($woker_ids);

        $woker_favorite_ids = $this->favoriteWorkrerService->getAllWorkerId();

        $workers = collect($workers)->map(function(&$worker) use($woker_favorite_ids){
            $date = Carbon::now();
            $worker->year_old = Carbon::create($worker->birthday)->diffInYears($date);

            if(in_array($worker->id, $woker_favorite_ids)){
                $worker->favorite = 1;
            }
            else{
                $worker->favorite = 0;
            }
            return $worker;
        });

        return $workers;
    }

    public function createReview($request)
    {
        foreach($request->workers as $id => $worker){

            if(!isset($worker['good_yn']) && !isset($worker['comment']) && !isset($worker['skill'])){
                return false;
            }

            $review = new WorkerReview();

            $review->fill([
                'worker_id' => $id,
                'user_id'   => Auth::id(),
                'work_id'   => $request->work_id,
                'good_yn'   => $worker['good_yn'] ?? null,
                'comment'   => $worker['comment'] ?? null,
            ])->save();

            $skill = $worker['skill'] ?? [];

            $this->createCurentSkill($request->work_id, $id, $skill);
        }
        return true;
    }

    /**
     * Undocumented function
     *
     * @param [int] $work_id  //仕事ID
     * @param [int] $worker_id  //ワーカーID
     * @param [array] $skills   //スキルID
     * @return void
     */
    public function createCurentSkill($work_id, $worker_id, $skills)
    {
        if(is_array($skills)){
            $skills = (array) $skills;
        }

        foreach($skills as $value){
            $skill = new WorkerSkill();
            $skill->fill([
                'worker_id' => $worker_id,
                'skill_id' => $value,
                'user_id' => Auth::id(),
                'work_id' => $work_id,
            ])->save();
        }
    }

    public function getWorkNotReview()
    {
        $woker_all = $this->getAllWorkerId();
        $woker_ids_review = $this->getWorkerIdReviewed();

        $work_ids = [];
        foreach($woker_all as $work_id => $worker_ids){
            $work_ids[] = $work_id;
            foreach($woker_ids_review as $work_id_review => $worker_ids_review){
                if($work_id == $work_id_review){
                    $check = array_diff($worker_ids, $worker_ids_review);
                    if(count($check) < 1){
                        $work_ids = array_diff($work_ids, [$work_id]);
                    }
                }
            }
        }

        $works = Work::find($work_ids);
        return $works;
    }

    public function getAllWorkerId()
    {
        $user_id = Auth::id();
        $woker_all = WorkRecord::where('user_id', $user_id)->get()->groupBy('work_id');

        $worker_ids = [];
        foreach($woker_all as $work_id => $workers){
            $array_ids = [];
            foreach($workers as $worker){
                $array_ids[] = $worker->worker_id;
            }

            $worker_ids[$work_id] = array_unique($array_ids);

        }

        return  $worker_ids;
    }

    public function getWorkerIdReviewed()
    {
        $user_id = Auth::id();
        $woker_ids_review = WorkerReview::where('user_id', $user_id)->get()->groupBy('work_id');

        $worker_ids = [];
        foreach($woker_ids_review as $work_id => $workers){
            $array_ids = [];
            foreach($workers as $worker){
                $array_ids[] = $worker->worker_id;
            }

            $worker_ids[$work_id] = array_unique($array_ids);
        }

        return $worker_ids;
    }

    public function getTotalReviewHomeByWorker()
    {
        $user_id = Auth::id();
        $home_review_good_yn1 = HomeReview::where('user_id', $user_id)->whereNotNull('good_yn1')->pluck('good_yn1')->countBy();
        $home_review_good_yn1 = $this->changeToPercentage($home_review_good_yn1);

        $home_review_good_yn2 = HomeReview::where('user_id', $user_id)->whereNotNull('good_yn2')->pluck('good_yn2')->countBy();
        $home_review_good_yn2 = $this->changeToPercentage($home_review_good_yn2);

        $home_review_good_yn3 = HomeReview::where('user_id', $user_id)->whereNotNull('good_yn3')->pluck('good_yn3')->countBy();
        $home_review_good_yn3 = $this->changeToPercentage($home_review_good_yn3);


        return [
            'good_yn1' => $home_review_good_yn1,
            'good_yn2' => $home_review_good_yn2,
            'good_yn3' => $home_review_good_yn3
        ];
    }

    public function changeToPercentage($criterias)
    {
        $total = 0;
        foreach($criterias as $value){
            $total += $value;
        }

        foreach($criterias as $key => $criteria){
            $criterias[$key . '_crit'] = number_format(($criteria / $total ) * 100, 2);
        }

        return $criterias;
    }

    public function getAllComment()
    {
        $user_id = Auth::id();
        $comments = HomeReview::select('user_id', 'worker_id', 'work_id', 'comment', 'created_at')
            ->where('user_id', $user_id)
            ->with('worker')
            ->get();

        return $comments;
    }
}
