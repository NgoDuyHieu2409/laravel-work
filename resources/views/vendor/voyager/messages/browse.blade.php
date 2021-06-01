@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '. 'Messages')

@section('content')
<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Danh sách tin nhắn</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-3 w-100 js-geenie-table" >
                            <thead>
                                <tr>
                                    <th>Work time</th>
                                    <th>Work title</th>
                                    <th>Unread messages</th>
                                    <th>Last message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($work_chats as $work)
                                <tr>
                                    <td>{{ $work->worktime }}</td>
                                    <td>
                                        <a href="{{ route('messages.detail', ['work_id' => $work->id]) }}">{{ $work->title }}</a>
                                    </td>
                                    <td><span class="js-count-total-message-in-{{ $work->id }}">0</span> Tin nhắn</td>
                                    <td class="message_last_{{ $work->id }}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<script>
    const KAIGO_ROOM_IDS = @json($room_ids);
</script>

<!-- DataTables -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
@stop
