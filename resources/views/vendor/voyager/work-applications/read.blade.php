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
                            <div class="col-lg-10 mb-0">
                                <div class="user-panel d-flex">
                                    <div class="image mr-3">
                                        <a href="#"><img src="{{ $worker->profile_photo_path }}" class="rounded-circle"
                                                width="50" height="50" alt=""></a>
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

                        @if($worker->contact)
                            <!-- Thông tin cá nhân -->
                            <div class="row">
                                <div class="col-lg-2 mb-0">Email</div>
                                <div class="col-lg-10 mb-0">{{ $worker->email ?? ''}}</div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-lg-2 mb-0">Gender</div>
                                <div class="col-lg-10 mb-0">{{ $worker->contact->sex ? 'Nam' : 'Nữ'}}</div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-lg-2 mb-0">Birthday</div>
                                <div class="col-lg-10 mb-0">
                                    {{ \Carbon\Carbon::parse($worker->contact->birthday)->format('d/m/Y')}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 mb-0">Address</div>
                                <div class="col-lg-10 mb-0">{{ $worker->address_format }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 mb-0">Skills</div>
                                <div class="col-lg-10 mb-0">
                                    <ol>
                                        @foreach ($worker->skills as $key => $skill)
                                        <li>{{ $skill->name }}</li>    
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 mb-0">Languages</div>
                                <div class="col-lg-10 mb-0">
                                    <ol>
                                        @foreach ($worker->languages as $language)
                                        @php
                                            switch ($language->proficiency){
                                                case 0:
                                                    $profy = '<span class="text-primary" style="font-size: 12px;">Beginner</span>';
                                                    break;
                                                case 1:
                                                    $profy = '<span class="text-warning" style="font-size: 12px;">Intermediate</span>';
                                                    break;
                                                case 2:
                                                    $profy = '<span class="text-danger" style="font-size: 12px;">Advanced</span>';
                                                    break;
                                                case 3:
                                                    $profy = '<span class="text-success" style="font-size: 12px;">Native</span>';
                                                    break;
                                            }
                                        @endphp
                                        <li>{{ $languages[$language->language_id] }} {!! $profy !!}</li>    
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 mb-0">Summary</div>
                                <div class="col-lg-10 mb-0">{!! $worker->contact->summary !!}</div>
                            </div>

                            <hr>
                            <!-- Thông tin lịch sử công việc -->
                            @if($worker->workHistories->count())
                            <div class="card">
                                <div class="card-header header-elements-inline">
                                    <h4 class="card-title">
                                        <b>Work Histories</b>
                                    </h4>
                                    <div class="card-tools">
                                        <span class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body pl-0 pr-0">
                                    <table class="table table-hover mb-3 border-bottom js-admin-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Position</th>
                                                <th>Company</th>
                                                <th>Time</th>
                                                <th>Descriptions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($worker->workHistories as $key => $item)
                                        <tr>
                                            <td>{{ $item->position ?? '' }}</td>
                                            <td>{{ $item->company ?? '' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->from_date)->format('d/m/Y')}}
                                                &nbsp;~&nbsp; 
                                                {{ \Carbon\Carbon::parse($item->to_date)->format('d/m/Y')}}
                                            </td>
                                            <td>{!! $item->description ?? '' !!}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            <!-- Thông tin Educations -->
                            @if($worker->educations->count())
                            <div class="card">
                                <div class="card-header header-elements-inline">
                                    <h4 class="card-title">
                                        <b>Educations</b>
                                    </h4>
                                    <div class="card-tools">
                                        <span class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body pl-0 pr-0">
                                    <table class="table table-hover mb-3 border-bottom js-admin-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>School</th>
                                                <th>Qualifications</th>
                                                <th>Time</th>
                                                <th>Descriptions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($worker->educations as $key => $item)
                                        <tr>
                                            <td>{{ $item->subject ?? '' }}</td>
                                            <td>{{ $item->school ?? '' }}</td>
                                            <td>{{ $item->qualification ?? '' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->from_date)->format('d/m/Y')}}
                                                &nbsp;~&nbsp; 
                                                {{ \Carbon\Carbon::parse($item->to_date)->format('d/m/Y')}}
                                            </td>
                                            <td>{!! $item->description ?? '' !!}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            <!-- Thông tin Educations -->
                            @if($worker->certifications->count())
                            <div class="card">
                                <div class="card-header header-elements-inline">
                                    <h4 class="card-title">
                                        <b>Certifications</b>
                                    </h4>
                                    <div class="card-tools">
                                        <span class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body pl-0 pr-0">
                                    <table class="table table-hover mb-3 border-bottom js-admin-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Institution</th>
                                                <th>Date</th>
                                                <th>Link</th>
                                                <th>Descriptions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($worker->certifications as $key => $item)
                                        <tr>
                                            <td>{{ $item->name ?? '' }}</td>
                                            <td>{{ $item->institution ?? '' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y')}}</td>
                                            <td>{!! $item->link ?? '' !!}</td>
                                            <td>{!! $item->description ?? '' !!}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        @else
                        <p>Hiên chưa có thông tin cá nhân</p>
                        @endif
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
                                <button type="submit" class="btn btn-primary js-btn-update-application"><i
                                        class="icon-checkmark4"></i> Approval</a>
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
                        <h5 class="card-title">Đánh giá kỹ năng</h5>
                    </div>

                    <div class="card-body pb-0">
                        @foreach($skills as $data_skills)
                            <ul style="display: flex;">
                                @foreach($data_skills as $k => $v)
                                @php
                                    $starKey = $k."_crit";
                                @endphp
                                <li style="margin-right: auto;">
                                    <label class="custom-control-label" for="skill_{{ $k }}">{{$v}}</label>
                                    <input name="workers[{{ $worker->id }}][skill][{{$k}}]" disabled
                                        class="star_rating_skill rating-loading" data-min="0"
                                        data-max="5" data-step="0.5" data-size="xs" value="{{ $skillStar[$starKey] }}">
                                </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Danh sách nhận xét khác</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-3 border-bottom w-100 js-geenie-table">
                                <colgroup>
                                    <col width="30%">
                                    <col width="70%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Người đánh giá</th>
                                        <th>Ý kiến đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($worker_reviews as $work_name => $worker_reivew)
                                        <tr><td colspan="2">{{ $work_name }}</td></tr>
                                        <tr>
                                        @foreach ($worker_reivew as $item)
                                        <td>
                                            <div class="widget-user-header">
                                                <div class="widget-user-image">
                                                    <img class="img-circle elevation-2" src="{{ $item->photo_url }}" width="42"
                                                        height="42" alt="User Avatar">
                                                </div>
                                                <h5 class="widget-user-username">{{ $item->home_name}}</h5>
                                                <h6 class="widget-user-desc text-muted">{{ $item->format_time}}</h6>
                                            </div>
                                        </td>
                                        <td>{{ $item->comment }}</td>
                                        @endforeach
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
</div>
@stop


@section('css')
<link href="{{ asset('css/star-rating.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/star-theme.css') }}" rel="stylesheet" type="text/css">

@stop

@section('javascript')
<script src="{{ asset('js/star-rating.js') }}"></script>

<script>
$(function() {
    $(".star_rating_skill").rating();
});
</script>
@stop