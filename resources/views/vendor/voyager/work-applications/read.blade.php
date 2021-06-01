@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
<div class="page-content read container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('work_applications.approval') }}" method="post">
                @csrf
                <input type="hidden" name="worker_id" value="{{ $dataTypeContent->worker_id }}">
                <input type="hidden" name="work_id" value="{{ $dataTypeContent->work_id }}">

                <div class="card" style="padding-bottom:5px;">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Thông tin công nhân</h5>
                    </div>
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="user-panel mt-3 mb-3 d-flex">
                                    <div class="image mr-3">
                                        <a href="#"><img src="{{ $worker->profile_photo_path }}" class="rounded-circle"
                                                width="42" height="42" alt=""></a>
                                    </div>
                                    <div class="info">
                                        <a href="#" class="d-block">
                                            <h6 class="mb-0">{{ $worker->name }}</h6>
                                            <span class="text-muted">{{ $worker->contact->job_title ?? '' }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2">Gender</div>
                            <div class="col-lg-10">{{ $worker->contact->sex ? 'Nam' : 'Nữ'}}</div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2">Birthday</div>
                            <div class="col-lg-10">
                                {{ \Carbon\Carbon::parse($worker->contact->birthday)->format('d/m/Y')}}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-2">Address</div>
                            <div class="col-lg-10">{{ $worker->address_format }}</div>
                        </div>
                        <div class="clear-fix" style="height: 20px;"></div>
                    </div>

                    <div class="card-footer" style="border-top: 1px solid #ddd;padding: 5px;">
                        <div class="col-lg-6">
                            <div class="text-center">
                                <button type="button" class="btn btn-primary" data-toggle="collapse"
                                    href="#collapse-link-collapsed"><i class="icon-eye"></i> Review Other</a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary js-btn-update-application"><i class="icon-checkmark4"></i> Approval</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="collapse" id="collapse-link-collapsed">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Danh sách nhận xét khác</h5>
                    </div>

                    <div class="card-body">
                        @foreach ($worker_reviews as $work_name => $worker_reivew)
                        <h4>{{ $work_name }}</h4>

                        @foreach ($worker_reivew as $item)
                        <div class="card">
                            <div class="card-header p-2">
                                <div class="widget-user-header">
                                    <div class="widget-user-image">
                                        <img class="img-circle elevation-2" src="{{ $item->photo_url }}" width="42"
                                            height="42" alt="User Avatar">
                                    </div>
                                    <h5 class="widget-user-username">{{ $item->home_name}}</h5>
                                    <h6 class="widget-user-desc text-muted">{{ $item->format_time}}</h6>
                                </div>
                                <div class="mt-4 mb-3">{{ $item->comment }}</div>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
@stop