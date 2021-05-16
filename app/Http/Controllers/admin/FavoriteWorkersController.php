<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Facades\Voyager;
use App\Http\Services\BlockWorkerService;
use App\Http\Services\FavoriteWorkerService;
use Illuminate\Support\Facades\DB;

class FavoriteWorkersController extends VoyagerBaseController
{
    protected $favoriteWorkrerService;
    protected $blockWorkerService;

    public function __construct(
        FavoriteWorkerService $favoriteWorkrerService,
        BlockWorkerService $blockWorkerService
    )
    {
        $this->favoriteWorkrerService = $favoriteWorkrerService;
        $this->blockWorkerService = $blockWorkerService;
    }

    public function index(Request $request)
    {
        $favorites = $this->favoriteWorkrerService->getAllFavorite();
        return view('home.favorite_workers.index')->with(compact('favorites'));
    }

    public function detail($id)
    {
        $favorite = $this->favoriteWorkrerService->getDetailFavorite($id);
        $worker_ids = $this->blockWorkerService->getWorkerIds();
        return view('home.favorite_workers.detail')->with(compact('favorite', 'worker_ids'));
    }

    public function addFavorite(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->favoriteWorkrerService->createFavoriteWorker($request);
            DB::commit();
            return response()->json(['flash_message' => 'Added to your favorites list success.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort('500', $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->favoriteWorkrerService->deleteFavoriteWorker($request->id);
            DB::commit();
            return response()->json(['flash_message' => '正常に削除しました。']);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort('500', $e->getMessage());
        }
    }
}
