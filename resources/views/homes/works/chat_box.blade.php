<x-app-layout>
    <style>
    .sticky {
        position: fixed !important;
        top: 65px;
        width: 19% !important;
        right: 10%;
    }

    .mt-5 {
        margin-top: 50px;
    }
    </style>

    <div class="container">
        <div class="row pt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Worker</h5>
                    </div>

                    <div class="card-body">
                        <ul class="media-list media-chat-scrollable mb-3">
                            @foreach ($work_chats as $work)
                            <li>
                                
                                    <div class="widget-user-header" style="display: table;">
                                        <div class="widget-user-image">
                                            <img class="img-circle elevation-2" src="{{ $work->user_avatar }}"
                                                width="40" height="40" alt="Work Avatar">
                                                <span class="badge badge-danger navbar-badge js-count-message-{{ $workerId }}-{{ $work->id }}"
                                                    style="top: unset; right: unset; left: 50px; "></span>
                                        </div>
                                        <div class="widget-user-username">
                                            <a href="{{ route('work.chat', ['work_id' => $work->id]) }}"
                                                class="font-weight-semibold mr-3" title="{{ $work->title }}">
                                                {{ $work->format_title }}
                                            </a>
                                            <p><span class="font-size-sm text-gray js-comment-worker-{{ $work->id }}">Không có tin nhắn</span></p>
                                        </div>
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
                        <h6 class="card-title" title="{{ $work_detail->title }}">
                            Chat with: {{ $work_detail->format_title }}
                        </h6>
                    </div>

                    <div class="card-body">
                        <ul class="media-list media-chat media-chat-scrollable mb-3 js-list-messages" style="max-height: 300px"></ul>
                    </div>

                    <div class="card-footer p-3" style="border-top: 1px solid #ddd;">
                        <form action="" method="post">
                            @csrf
                            <input type="hidden" name="worker_uid" value="{{ $workerId }}">
                            <input type="hidden" name="work_id" value="{{ $work_detail->id ?? 0 }}">

                            <textarea name="comment" class="form-control js-input-comment" rows="1" placeholder="Messages"></textarea>

                            <div class="text-right mt-1">
                                <button type="button" class="btn btn-sm btn-raised btn-success js-btn-send-message">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    <link href="{{ asset('template/css/components.min.css') }}" rel="stylesheet" type="text/css">
    @endpush

    @push('scripts')
    <script>
    const KAIGO_USER_AVATAR = @JSON($user_avatar);
    const KAIGO_WORKER_AVATAR = @JSON($worker_avatar);
    const KAIGO_ROOM_IDs = @JSON($room_ids);
    const KAIGO_ROOM_ID = @JSON($room_id);
    const KAIGO_WORk_ID = @JSON($work_detail -> uid ?? 0);
    const KAIGO_WORKER_UID = @JSON($workerId);
    const KAIGO_WORK_IDS = @JSON($workIds);
    </script>
    <script src="{{ asset('js/messages/worker_send_message.js') }}"></script>
    @endpush
</x-app-layout>