@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-header header-elements-inline">
                    <h5 class="panel-title">Danh sách chưa đánh giá</h5>
                </div>
                <div class="panel-body">
                    <p class="mb-2 font-weight-600">Lựa chọn và đánh giá các công việc mà việc đánh giá công nhân chưa được hoàn thành.</p>
                    <table class="table table-hover mb-3 border-bottom w-100 js-works-table">
                        <thead>
                            <tr>
                                <th>Working hours</th>
                                <th>Business title</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($works as $work)
                            <tr>
                                <td>
                                    @php
                                    $recruitment_start_at = \Carbon\Carbon::parse($work->recruitment_start_at)->locale('ja');
                                    $recruitment_start_at = $recruitment_start_at->isoFormat('LL (dddd)');
                                    @endphp

                                    {{ $recruitment_start_at }}
                                </td>
                                <td><a
                                        href="{{ route('home.reviews.detail',['work_id' => $work->id]) }}">{{ $work->title }}</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-bordered">
                <div class="panel-header header-elements-inline">
                    <h5 class="panel-title">Tỷ lệ tốt về công ty / cửa hàng của bạn</h5>
                </div>
                <div class="panel-body">
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
                                $good_name = '勤務時間は予定通りでしたか？';
                                break;
                                case 'good_yn2':
                                $good_name = '掲載されていた業務内容通りでしたか？';
                                break;
                                case 'good_yn3':
                                $good_name = 'またここで働きたいですか？';
                                break;
                                default:
                                break;
                                }
                                @endphp
                                <tr>
                                    <td>{{ $good_name ?? '' }}</td>
                                    <td>{{ $worker_review['y_crit'] ?? 0 }}&#37;</td>
                                    <td><i class="icon-thumbs-up2"></i> {{ $worker_review['y'] ?? 0 }}
                                        <i class="icon-thumbs-down2"></i> {{ $worker_review['n'] ?? 0 }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered">
                <div class="panel-header header-elements-inline">
                    <h5 class="panel-title">あなたの企業・店舗へのコメント一覧</h5>
                </div>
                <div class="panel-body">
                    @foreach ($worker_comment as $item)

                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="mr-3">
                                    @php
                                    $url = isset($item->worker->photo) ?
                                    Storage::url($item->worker->photo) :
                                    '/_Template/global_assets/images/placeholders/placeholder.jpg';
                                    @endphp
                                    <a href="#">
                                        <img src="{{ $url }}" class="rounded-circle" width="42" height="42" alt="">
                                    </a>
                                </div>

                                <div class="media-body">
                                    <h6 class="mb-0">
                                        {{ $item->worker->first_name . $item->worker->last_name }}
                                    </h6>
                                    <span class="text-muted">業務番号: {{ sprintf('%06d', $item->work_id) }}
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('Y年m月d日') }}</span>
                                </div>
                            </div>
                            <div class="mt-3">{{ $item->comment }}</div>
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