@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '. 'Messages')

@section('content')
<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $work_name }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Worker</h5>
                </div>

                <div class="card-body">
                    <ul class="media-list media-chat-scrollable mb-3">
                        @foreach ($workers as $worker)
                        <li class="mb-4">
                            <div class="widget-user-header">
                                <div class="widget-user-image">
                                    <img class="img-circle elevation-2" src="{{ $worker->avatar }}" width="40" height="40" alt="Worker Avatar">
                                    <span class="badge badge-danger navbar-badge js-count-message-{{ $worker->id }}-{{ $work_id }}"
                                            style="top: unset; right: unset; left: 55px; "></span>
                                </div>
                                <h5 class="widget-user-username">
                                    <a href="{{ route('messages.detail', ['work_id' => $work_id, 'worker_id' => $worker->id]) }}" 
                                        class="font-weight-semibold mr-3">
                                        {{ $worker->name }}
                                    </a>
                                    <p><span class="font-size-sm text-gray js-comment-worker-{{ $worker->id }}">Không có tin nhắn</span></p>
                                </h5>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">
                        Chat with: {{ $worker_detail->name }}
                    </h6>
                </div>

                <div class="card-body">
                    <ul class="media-list media-chat media-chat-scrollable mb-3 js-list-messages" style="max-height: 300px"></ul>
                </div>

                <div class="card-footer p-3" style="border-top: 1px solid #ddd;">
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="worker_uid" value="{{ $worker_detail->uid ?? 0 }}">
                        <input type="hidden" name="work_id" value="{{ $work_id }}">

                        <textarea name="comment" class="form-control js-input-comment" rows="1" 
                           placeholder="Messages"></textarea>
                        
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-raised btn-success js-btn-send-message">
                                <i class="icon-paperplane"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<script>
const KAIGO_USER_AVATAR = @JSON($user_avatar);
const KAIGO_WORKER_AVATAR = @JSON($worker_avatar);
const KAIGO_WORKER_UID = @JSON($worker_detail -> id ?? 0);
const KAIGO_ROOM_ID = @JSON($room_id);
const KAIGO_WORKER_UIDS = @JSON($workerIds);
const KAIGO_WORk_ID = @JSON($work_id);
const KAIGO_ROOM_IDs = @JSON($room_ids);
</script>
@stop