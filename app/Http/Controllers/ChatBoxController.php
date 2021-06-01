<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\MessageService;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Str;

class ChatBoxController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        $workerId = Auth::id();
        // Get list room chat to worker (Danh sách các work mà worker tham gia)
        $work_chats = $this->messageService->getWorkChatIdWorker()->get();
        foreach($work_chats as $work){
            $work->user_avatar = Voyager::image($work->user->avatar);
            $work->format_title = Str::limit($work->title, 20);
        }

        
        $workIds = $this->messageService->getWorkChatIdWorker()->pluck('id')->toArray();
        $room_ids = $this->messageService->getRoomIdsbyWork($workIds, $workerId);

        $workId = $request->work_id ?? $workIds[0] ?? 0;
        $room_id = $this->messageService->getRoomIdbyWorker($workId, $workerId);
        $work_detail = Work::findOrFail($workId);
        $work_detail->format_title = Str::limit($work_detail->title, 50);

        $worker = User::findOrFail($workerId);
        $user_avatar = Voyager::image($work_detail->user->avatar);
        $worker_avatar = $worker->profile_photo_path ? Storage::url($worker->profile_photo_path) : Voyager::image($worker->avatar);

        return view('homes.works.chat_box')->with(compact(
            'work_chats',
            'room_ids',
            'workerId',
            'work_detail',
            'worker_avatar',
            'user_avatar',
            'room_id',
            'workIds'
        ));
    }
}