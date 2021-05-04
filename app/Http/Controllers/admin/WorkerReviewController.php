<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Http\Services\WorkerReviewService;
use App\Traits\AppUtility;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataAdded;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Events\BreadDataUpdated;

class WorkerReviewController extends VoyagerBaseController
{
    protected $reviewService;

    public function __construct(WorkerReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $slug = $this->getSlug($request);
        $user_id = Auth::id();

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));


        $works = $this->reviewService->getWorkNotReview();
        $worker_reviews = $this->reviewService->getTotalReviewHomeByWorker();
        $worker_comment = $this->reviewService->getAllComment();

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view)->with(compact('works', 'worker_reviews', 'worker_comment', 'dataType'));
    }
    

    public function detail($work_id)
    {
        $workers = $this->reviewService->getWokerIdNotReview($work_id);
        return view('home.reviews.detail')->with(compact('workers', 'work_id'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $review = $this->reviewService->createReview($request);
            if($review){
                DB::commit();
                return redirect()->route('home.reviews.index')->with('flash_message', '評価が完了しました。');
            }
            else{
                DB::rollBack();
                return redirect()->back()->withInput()->with('error_message', '全員を評価してください。');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return abort('500', $e->getMessage());
        }
    }
}
