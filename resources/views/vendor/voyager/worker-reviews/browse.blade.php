@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('content')
<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Danh sách chưa đánh giá</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-3 border-bottom w-100 js-geenie-table">
                            <thead>
                                <tr>
                                    <th>Working hours</th>
                                    <th>Business title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($works as $work)
                                <tr>
                                    @php
                                    $recruitment_start_at = \Carbon\Carbon::parse($work->recruitment_start_at)->format('d/m/Y');
                                    @endphp
                                    <td>{{ $recruitment_start_at }}</td>
                                    <td><a
                                            href="{{ route('worker-reviews.detail',['work_id' => $work->id]) }}">{{ $work->title }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Tỷ lệ tốt về công ty / cửa hàng của bạn</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-bottom">
                            <thead>
                                <tr>
                                    <th>Work title</th>
                                    <th>Good ratio</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($worker_reviews as $key => $worker_review)
                                @php
                                switch ($key) {
                                case 'good_yn1':
                                $good_name = 'Giờ làm việc của bạn có đáp ứng lịch trình của bạn không?';
                                break;
                                case 'good_yn2':
                                $good_name = 'Bạn có làm theo mô tả doanh nghiệp được đăng không?';
                                break;
                                case 'good_yn3':
                                $good_name = 'Bạn có muốn làm việc ở đây một lần nữa không?';
                                break;
                                default:
                                break;
                                }
                                @endphp
                                <tr>
                                    <td>{{ $good_name ?? '' }}</td>
                                    <td>{{ $worker_review['y_crit'] ?? 0 }}&#37;</td>
                                    <td>
                                        <i class="icon-thumbs-up2"></i> {{ $worker_review['y'] ?? 0 }}
                                        <i class="icon-thumbs-down2"></i> {{ $worker_review['n'] ?? 0 }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Danh sách nhận xét cho công ty / cửa hàng của bạn</h5>
                </div>
                <div class="card-body">
                    @foreach ($worker_comment as $item)
                    <div class="card">
                        <div class="card-header p-2">
                            <div class="widget-user-header">
                                @php
                                $url = isset($item->worker->profile_photo_path) ? Storage::url($item->worker->profile_photo_path) : Voyager::image($item->worker->avatar);
                                @endphp
                                <div class="widget-user-image">
                                    <img class="img-circle elevation-2" src="{{ $url }}"  width="42" height="42" alt="User Avatar">
                                </div>
                                <h5 class="widget-user-username">{{ $item->worker->name}}</h5>
                                <h6 class="widget-user-desc text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</h6>
                            </div>
                            <div class="mt-4 mb-3">{{ $item->comment }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
@stop