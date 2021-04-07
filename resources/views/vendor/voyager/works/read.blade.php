@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;

        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <span class="glyphicon glyphicon-pencil"></span>&nbsp;
                {{ __('voyager::generic.edit') }}
            </a>
        @endcan
        @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan
        @can('browse', $dataTypeContent)
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            {{ __('voyager::generic.return_to_list') }}
        </a>
        @endcan
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel boder-top-success">
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-lg-6 mb-0">
                                <h5 class="title-read">募集人数</h5>
                                {{ $dataTypeContent->recruitment_person_count }}
                            </div>
                            <div class="col-lg-6 mb-0">
                                <h5 class="title-read">確定人数</h5>
                                {{ $dataTypeContent->count_confirmed }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel boder-top-warning">
                    <div class="panel-body text-center">
                        <h5 class="title-read">今回の予定額</h5>
                        <h4>{{ number_format($dataTypeContent->total_paid + $dataTypeContent->total_fee) }} VNĐ</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel boder-top-primary">
                    <div class="panel-body text-center">
                        <a href="#" class="btn btn-primary"><i class="icon-alarm mr-2"></i> チェックイン・アウト管理</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="panel-header header-elements-inline">
                        <h5 class="panel-title">Thông tin công việc</h5>
                    </div>
                    <!-- form start -->
                    <div class="panel-body">
                        @foreach($dataType->readRows as $row)
                            @php
                            if ($dataTypeContent->{$row->field.'_read'}) {
                                $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                            }
                            @endphp
                        <div class="row">
                            <div class="col-lg-2 mb-0"><b class="title-detail">{{ $row->getTranslatedAttribute('display_name') }}</b></div>
                            <div class="col-lg-10 mb-0">
                                @if (isset($row->details->view))
                                    @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details])
                                @elseif($row->type == "image")
                                    <img class="img-responsive"
                                        src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}">
                                @elseif($row->type == 'multiple_images')
                                    @if(json_decode($dataTypeContent->{$row->field}))
                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                            <img class="img-responsive"
                                                src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file) }}">
                                        @endforeach
                                    @else
                                        <img class="img-responsive"
                                            src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field}) }}">
                                    @endif
                                @elseif($row->type == 'relationship')
                                    @include('voyager::formfields.relationship', ['view' => 'read', 'options' => $row->details])
                                @elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options') &&
                                        !empty($row->details->options->{$dataTypeContent->{$row->field}})
                                )
                                    <?php echo $row->details->options->{$dataTypeContent->{$row->field}};?>
                                @elseif($row->type == 'select_multiple')
                                    @if(property_exists($row->details, 'relationship'))

                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                            {{ $item->{$row->field}  }}
                                        @endforeach

                                    @elseif(property_exists($row->details, 'options'))
                                        @if (!empty(json_decode($dataTypeContent->{$row->field})))
                                            @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                                @if (@$row->details->options->{$item})
                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                @endif
                                            @endforeach
                                        @else
                                            {{ __('voyager::generic.none') }}
                                        @endif
                                    @endif
                                @elseif($row->type == 'date' || $row->type == 'timestamp')
                                    @if ( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
                                        {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }}
                                    @else
                                        {{ $dataTypeContent->{$row->field} }}
                                    @endif
                                @elseif($row->type == 'checkbox')
                                    @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                        @if($dataTypeContent->{$row->field})
                                        <span class="label label-info">{{ $row->details->on }}</span>
                                        @else
                                        <span class="label label-primary">{{ $row->details->off }}</span>
                                        @endif
                                    @else
                                    {{ $dataTypeContent->{$row->field} }}
                                    @endif
                                @elseif($row->type == 'color')
                                    <span class="badge badge-lg" style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
                                @elseif($row->type == 'coordinates')
                                    @include('voyager::partials.coordinates')
                                @elseif($row->type == 'rich_text_box')
                                    @include('voyager::multilingual.input-hidden-bread-read')
                                    {!! $dataTypeContent->{$row->field} !!}
                                @elseif($row->type == 'file')
                                    @if(json_decode($dataTypeContent->{$row->field}))
                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                            <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}">
                                                {{ $file->original_name ?: '' }}
                                            </a>
                                            <br/>
                                        @endforeach
                                    @else
                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($row->field) ?: '' }}">
                                            {{ __('voyager::generic.download') }}
                                        </a>
                                    @endif
                                @else
                                    @include('voyager::multilingual.input-hidden-bread-read')
                                    <p>{{ $dataTypeContent->{$row->field} }}</p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        @endforeach

                        <div class="row">
                            <div class="col-sm-2 mb-0"><b class="title-detail">Lương theo giời</b></div>
                            <div class="col-sm-10 mb-0">
                                <p>{{ $dataTypeContent->hourly_wage }}</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2 mb-0"><b class="title-detail">Trợ cấp tăng ca</b><span style="padding-left: 10px;color: #777;">(theo giờ)</span></div>
                            <div class="col-lg-10 mb-0">
                                {{ number_format(setting('admin.ovetime_extra_percentages')) ?? 0 }} {{ \App\Enums\WorkConfig::CURRENCY}}
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2 mb-0"><b class="title-detail">Trợ cấp làm đêm</b><span style="padding-left: 10px;color: #777;">(theo giờ)</span></div>
                            <div class="col-lg-10 mb-0">
                                {{ number_format(setting('admin.night_extra_percentages')) ?? 0 }} {{ \App\Enums\WorkConfig::CURRENCY}}
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2 mb-0"><b class="title-detail">Chi phí vận chuyển</b></div>
                            <div class="col-lg-10 mb-0">
                                {{ $dataTypeContent->transportation_fee }}
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2 mb-0"><b class="title-detail">Tổng lương trả cho người làm</b></div>
                            <div class="col-lg-10 mb-0">
                                {{ number_format($dataTypeContent->total_paid) }} {{ \App\Enums\WorkConfig::CURRENCY}}
                            </div>
                        </div>
                        <hr>

                        <div class="clear-fix" style="height: 20px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::generic.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script>
@stop
