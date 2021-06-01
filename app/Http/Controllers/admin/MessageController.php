<?php

namespace App\Http\Controllers\Admin;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use App\Http\Services\MessageService;
use App\Models\Work;
use App\Models\User as Worker;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MessageController extends VoyagerBaseController
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        $slug = $this->getSlug($request);
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $work_chats = $this->messageService->getRoomChat();
        $room_ids = $this->messageService->getAllRoomIdByWork();

        return Voyager::view('voyager::messages.browse')->with(compact('work_chats', 'room_ids', 'dataType'));
    }

    public function detail(Request $request, $work_id)
    {
        $slug = $this->getSlug($request);
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
    
        $workers = $this->messageService->getworkerByWork($work_id);

        $workerIds = $this->messageService->workService->getWorkerUid($work_id);
        $room_ids = $this->messageService->getRoomIdsbyWork($work_id, $workerIds);


        $worker_uid = $request->worker_id ?? $workerIds[0] ?? 0;
        $room_id = $this->messageService->getRoomIdbyWorker($work_id, $worker_uid);
        $worker_detail = Worker::findOrFail($worker_uid);

        $work = Work::findOrFail($work_id);
        $work_name = $work->title;
        $worker_avatar = $worker_detail->profile_photo_path ? Storage::url($worker_detail->profile_photo_path) : Voyager::image($worker_detail->avatar);

        $userManager = User::findOrFail($work->user_id);
        $user_avatar = Storage::url($userManager->avatar); 

        return Voyager::view('voyager::messages.detail')->with(compact(
            'dataType',
            'workers',
            'work_id',
            'worker_detail',
            'room_id',
            'workerIds',
            'work_name',
            'worker_avatar',
            'room_ids',
            'user_avatar'
        ));
    }

    // public function token(Request $request)
    // {
    //     return response()->json($request->all());
    // }

    // public function post(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $user_id = Auth::id();
    //         $message = new Message();

    //         $worker_id = Worker::where('uid', $request->worker_uid)->first()->id;

    //         if(!$request->comment){
    //             return false;
    //         }

    //         $message->fill([
    //             'worker_id' => $worker_id,
    //             'home_id' => $user_id,
    //             'work_id' => $request->work_id,
    //             'comment' => $request->comment,
    //             'from_worker_yn' => 'n',
    //             'read_at' => null,
    //         ])->save();

    //         DB::commit();
    //         return response()->json(['flash_message' => 'success']);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return abort('500', $e->getMessage());
    //     }
    // }

    public function getWorkerUidByWork(Request $request)
    {
        $worker_uids = $this->messageService->workService->getWorkerUid($request->work_id);
        return response()->json($worker_uids);
    }

    public function ajaxGetRoomIdByWork()
    {
        $room_ids = $this->messageService->getAllRoomIdByWork();
        return response()->json($room_ids);
    }

    public function ajaxGetRoomByWorkAndWorker(Request $request)
    {
        $room_id = $this->messageService->getRoomIdbyWorker($request->work_id, $request->worker_id);
        return response()->json($room_id);
    }

    public function ajaxGetWorkerId()
    {
        $room_works = [];
        $worker_ids = [];
        $work_chat_ids = $this->messageService->getRoomChat()->pluck('id');
        foreach($work_chat_ids as $work_id){
            $worker_ids[$work_id] =  $this->messageService->getworkerByWork($work_id)->pluck('id');
        }

        foreach($worker_ids as $work_id => $workerIds){
            foreach($workerIds as $id){
                $room_ids = $this->messageService->getRoomIdsbyWork($work_id, [$id]);
                $room_works[$work_id][$id] = $room_ids[0];
            }
        }

        return response()->json($room_works);
    }
}
