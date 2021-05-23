<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Http\Services\WorkerReviewService;
use App\Traits\AppUtility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class WorkerReviewController extends VoyagerBaseController
{
    use AppUtility;    

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
    

    public function detail(Request $request, $work_id)
    {
        $dataType = Voyager::model('DataType')->where('slug', '=', 'worker-reviews')->first();

        $workers = $this->reviewService->getWokerIdNotReview($work_id);

        $data = [
            'skills' => $this->getItemStringToArray(setting('admin.skills')),
        ];

        return Voyager::view('voyager::worker-reviews.detail')->with(compact('workers', 'work_id', 'dataType', 'data'));
    }

    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        try {
            DB::beginTransaction();
            $review = $this->reviewService->createReview($request);
            if($review){
                DB::commit();
                return redirect()->route('voyager.worker-reviews.index')->with([
                    'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
            }
            else{
                DB::rollBack();
                return redirect()->back()->withInput()->with([
                    'message'    => "Please rate everyone.",
                    'alert-type' => 'error',
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return abort('500', $e->getMessage());
        }
    }
}