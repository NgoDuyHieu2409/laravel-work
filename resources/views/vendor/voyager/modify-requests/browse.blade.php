@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('content')
<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Danh sách các yêu cầu sửa chữa</h5>
                </div>

                <div class="card-body">
                    {{ count($modify_requests) }} Yêu cầu sửa đổi đã được gửi.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($modify_requests as $modify_request)
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="mr-3">
                            @php
                            $image = $modify_request->worker->profile_photo_path ? Storage::url($modify_request->worker->profile_photo_path) :
                                    Voyager::image($modify_request->worker->avatar);
                            @endphp
                            <a href="#">
                                <img src="{{ $image }}" class="rounded-circle" width="42" height="42" alt="">
                            </a>
                        </div>

                        <div class="">
                            <h6 class="mb-0"> {{ $modify_request->worker->name}}</h6>
                        </div>
                    </div>

                    <div class="text-left">
                        <a href="{{ route('voyager.works.show', ['id' => $modify_request->work->id]) }}"
                            class="btn btn-light">{{ $modify_request->work->title }}</a>
                    </div>

                    <div class="font-size-sm">
                        <form action="#" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table table-hover border-bottom w-100 mt-3">
                                    <colgroup>
                                        <col width="20%">
                                        <col width="40%">
                                        <col width="40%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>元の業務</th>
                                            <th>申請内容</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>業務日</td>
                                            <td>{{ \Carbon\Carbon::parse($modify_request->work->worktime_start_at)->format('m月d日') }}
                                            </td>
                                            <td class="{{ $modify_request->text_scheduled ?? '' }}">
                                                {{ \Carbon\Carbon::parse($modify_request->scheduled_worktime_start_at)->format('m月d日') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>業務時間</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($modify_request->work->worktime_start_at)->format('H:i') }}
                                                &#126;
                                                {{ \Carbon\Carbon::parse($modify_request->work->worktime_end_at)->format('H:i') }}
                                            </td>
                                            <td class="{{ $modify_request->text_worktime ?? '' }}">
                                                {{ \Carbon\Carbon::parse($modify_request->modify_worktime_start_at)->format('H:i') }}
                                                &#126;
                                                {{ \Carbon\Carbon::parse($modify_request->modify_worktime_end_at)->format('H:i') }}
                                            </td>
                                        </tr>
                                        <tr>


                                            <td>休憩時間</td>
                                            <td>{{ number_format($modify_request->work->resttime_minutes) }}分</td>
                                            <td class="{{ $modify_request->text_resttime ?? '' }}">
                                                {{ number_format($modify_request->resttime_minutes) }}分</td>
                                        </tr>
                                        <tr>
                                            <td>報酬額合計</td>
                                            <td>
                                                <span>{{ number_format($modify_request->work->base_wage + $modify_request->work->transportation_fee) }}円</span>
                                                <p>（交通費{{ $modify_request->work->transportation_fee }}円込）</p>
                                            </td>
                                            <td>
                                                <span
                                                    class="{{ $modify_request->text_base ?? '' }}">{{ number_format($modify_request->total_paid) }}円</span>
                                                <p>（交通費{{ $modify_request->transportation_fee }}円込）</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-3" style="padding-bottom: 0 !important;">
                                <p>修正依頼の理由</p>
                                <p>{!! $modify_request->comment !!}</p>
                            </div>
                            <hr>

                            <div class="text-right">
                                <button type="button" class="btn btn-sm btn-danger js-btn-refuse-request"
                                    data-id="{{ $modify_request->id }}">
                                    Từ chối
                                </button>
                                <button type="button" class="btn btn-sm btn-primary js-btn-approve-request"
                                    data-id="{{ $modify_request->id }}">
                                    Chấp thuận
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@stop

@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
<link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
@stop

@section('javascript')
<!-- DataTables -->
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
<script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
@endif

@stop