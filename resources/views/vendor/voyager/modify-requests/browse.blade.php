@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('content')
@php
use Illuminate\Support\Str;
@endphp
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

                    <div class="text-left mt-2">
                        <a href="{{ route('voyager.works.show', ['id' => $modify_request->work->id]) }}"
                            title="{{ $modify_request->work->title }}">
                            {{ Str::limit($modify_request->work->title, 30) }}
                        </a>
                    </div>

                    <div class="font-size-sm">
                        <form action="#" method="POST">
                            @csrf
                            @method('POST')

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
                                            <th>Work</th>
                                            <th>Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Date</td>
                                            <td>{{ \Carbon\Carbon::parse($modify_request->work->worktime_start_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="{{ $modify_request->text_scheduled ?? '' }}">
                                                {{ \Carbon\Carbon::parse($modify_request->scheduled_worktime_start_at)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Time</td>
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


                                            <td>Resttime</td>
                                            <td>{{ number_format($modify_request->work->resttime_minutes) }} phút</td>
                                            <td class="{{ $modify_request->text_resttime ?? '' }}">
                                                {{ number_format($modify_request->resttime_minutes) }} phút</td>
                                        </tr>
                                        <tr>
                                            <td>Total wages</td>
                                            <td>
                                                <span>{{ number_format($modify_request->work->base_wage + $modify_request->work->transportation_fee) }} VNĐ</span>
                                                <p class="text-info">(Đã bao gồm {{ $modify_request->work->transportation_fee }} VNĐ chi phí đi lại.)</p>
                                            </td>
                                            <td>
                                                <span class="{{ $modify_request->text_base ?? '' }}">{{ number_format($modify_request->total_paid) }} VNĐ</span>
                                                <p class="text-info">(Đã bao gồm {{ $modify_request->transportation_fee }} VNĐ chi phí đi lại.)</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-2">
                                <p>Comment</p>
                                <p>{!! $modify_request->comment !!}</p>
                            </div>
                            <hr class="mt-0">

                            <div class="text-right">
                                <button type="button" class="btn btn-xs btn-danger js-btn-refuse-request"
                                    data-id="{{ $modify_request->id }}">
                                    Từ chối
                                </button>
                                <button type="button" class="btn btn-xs btn-primary js-btn-approve-request"
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

@section('javascript')
<script src="{{ asset('js/modify_requests/modify_requests.js') }}"></script>
@stop