@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('content')
<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="icon-coin-dollar icon-4x text-success p-3 mb-3 mt-1"></i>
                            <h5 class="card-title">Hóa đơn của tháng này</h5>
                            <h4>{{ number_format($total_piad_now) }} VNĐ</h4>
                            <a href="{{ route('work-records.detail', ['month' => date('Y-m')]) }}" class="btn btn-success">
                                <i class="far fa-hand-point-right"></i> Kiểm tra chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title">Chi tiết sử dụng</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-3 border-bottom w-100 js-geenie-table">
                                    <thead>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($work_records as $key => $record)
                                        @php
                                        $month = \Carbon\Carbon::parse($key)->format('Y-m');
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('work-records.detail', ['month' => $key]) }}">
                                                    Chi tiết sử dụng đến {{ $month }}
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('work-records.csv', ['month' => $key]) }}" class="btn btn-sm btn-danger rounded-round">Dowload CSV</a>
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
        </div>
    </div>
</div>
@stop

@section('css')
<!-- DataTables -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
@stop