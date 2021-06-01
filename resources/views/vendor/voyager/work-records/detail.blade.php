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
                            <h5 class="card-title text-capitalize text-center mb-3">
                                <span>Số tiền thanh toán tính tới ngày:</span><br/>
                                <span class="text-danger">{{ \Carbon\Carbon::parse($month)->format("Y-m-{$work_records['date']}") }}</span>
                            </h5>
                            <h4>{{ number_format($work_records['total_paid']) }} VNĐ</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title">
                                Báo cáo sử dụng:&nbsp;
                                {{ \Carbon\Carbon::parse($month)->format("Y-m-1") }}
                                &nbsp;&#126;&nbsp;
                                {{ \Carbon\Carbon::parse($month)->format("Y-m-{$work_records['date']}")}}
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border-bottom mb-3 table-hover table-bordered w-100 js-geenie-table">
                                    <thead>
                                        <tr>
                                            <th>Work name</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Work name</th>
                                            <th class="text-center">Gender</th>
                                            <th class="text-center">Base wage</th>
                                            <th class="text-center">Transportation-fee</th>
                                            <th class="text-center">Total wage</th>
                                            <th class="text-center">Transfer request</th>
                                            <th class="text-center">Transfered</th>
                                            <th class="text-center">Commission-fee</th>
                                            <th class="text-center">Commission-fee-tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($work_records['record_by_month'] as $work_id => $records)
                                        @foreach ($records as $record)
                                        <tr>
                                            <td class="text-primary"><span title="{{$record->work->title}}">{{ $record->work_title}}</span></td>
                                            <td>{{ $record->work_date }}</td>
                                            <td>{{ $record->worker->name ?? '' }}</td>
                                            <td class="text-center">{{ $record->gender }}</td>
                                            <td>{{ $record->base_wage . setting('admin.currency')}}</td>
                                            <td>{{ $record->transportation_fee . setting('admin.currency')}}</td>
                                            <td>{{ $record->total_wage . setting('admin.currency')}}</td>
                                            <td>{{ $record->transfer_requested_at }}</td>
                                            <td>{{ $record->transfered_at }}</td>
                                            <td>{{ $record->commission_fee . setting('admin.currency')}}</td>
                                            <td>{{ $record->commission_fee_tax . setting('admin.currency')}}</td>
                                        </tr>
                                        @endforeach
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

@section('javascript')
<!-- DataTables -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('js/work_record.js') }}"></script>
@stop