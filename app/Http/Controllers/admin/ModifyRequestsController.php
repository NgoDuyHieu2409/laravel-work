<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\ModifyRequestService;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Facades\Voyager;

class ModifyRequestsController extends VoyagerBaseController
{
    protected $modifyRequestService;

    public function __construct(ModifyRequestService $modifyRequestService)
    {
        $this->modifyRequestService = $modifyRequestService;
    }

    public function index(Request $request)
    {
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $modify_requests = $this->modifyRequestService->getAll();

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }
        return view($view)->with(compact('modify_requests', 'dataType'));
    }

    public function refuse(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->modifyRequestService->updateStatus($request->modify_id, 'refuse');

            DB::commit();
            return response()->json([
                'status' => 'success',
                'flash_message' => "修正依頼が拒否されました。"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort('500', $e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->modifyRequestService->updateStatus($request->modify_id, 'approve');

            DB::commit();
            return response()->json([
                'status' => 'success',
                'flash_message' => "修正依頼が承認されました。"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort('500', $e->getMessage());
        }
    }

}
