@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="panel-header header-elements-inline">
                        <h5 class="panel-title">Thông tin công nhân (Todo Cần lấy thông tin worker))</h5>
                    </div>
                    <!-- form start -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="user-panel mt-3 mb-3 d-flex">
                                    <div class="image mr-3">
                                        <a href="#"><img src="{{ Storage::url($worker->profile_photo_path) }}" class="rounded-circle" width="42" height="42" alt=""></a>
                                    </div>
                                    <div class="info">
                                      <a href="#" class="d-block">
                                        <h6 class="mb-0">Nguyễn Văn Hiệp</h6>
                                        <span class="text-muted">Hiep Nguyen</span>
                                      </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2">性別</div>
                            <div class="col-lg-10">
                               nam
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-2">生年月日</div>
                            <div class="col-lg-10">
                                2021-04-20
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-2">住所</div>
                            <div class="col-lg-10">
                                địa chỉ
                            </div>
                        </div>
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
