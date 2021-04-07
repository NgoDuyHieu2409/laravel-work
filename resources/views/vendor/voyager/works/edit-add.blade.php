@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/template/plugins/dropzone/min/dropzone.min.css') }}">
    
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <!-- form start -->
        <form role="form"
                class="form-edit-add"
                action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
            @if($edit)
                {{ method_field("PUT") }}
            @endif

            <!-- CSRF TOKEN -->
            {{ csrf_field() }}

            @php
                $work = $dataTypeContent;
                if( isset($work->id) && !isset($copy)){
                    $work_id = $work->id;
                }
            @endphp
            <input type="hidden" name="work_id" value="{{ $work_id ?? '' }}">

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><b><i class="far fa-edit"></i> Thông tin tuyển dụng</b></h4>
                            <div class="card-tools">
                                <span class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Thời gian bắt đầu<span class="text-danger ml-1">*</span></label>
                                        <input class="form-control js-work-time-start @error('worktime_start_at') is-invalid @enderror" type="datetime-local" name="worktime_start_at"
                                            value="{{ old('worktime_start_at', $work->input_worktime_start) }}">

                                        @error('worktime_start_at')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Thời gian kết thúc<span class="text-danger ml-1">*</span></label>
                                        <input class="form-control @error('worktime_end_at') is-invalid @enderror" type="datetime-local" name="worktime_end_at"
                                            value="{{  old('worktime_end_at', $work->input_worktime_end) }}">

                                        @error('worktime_end_at')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Thời gian bắt đầu giải lao</label>
                                        <input class="form-control js-resttime-minutes @error('resttime_start_at') is-invalid @enderror" type="datetime-local"
                                            name="resttime_start_at" value="{{ old('resttime_start_at', $work->input_resttime_start) }}">

                                        @error('resttime_start_at')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4">
                                        <label>Thời gian kết thúc giải lao</label>
                                        <input class="form-control js-resttime-minutes @error('resttime_end_at') is-invalid @enderror" type="datetime-local" name="resttime_end_at"
                                                value="{{ old('resttime_end_at', $work->input_resttime_end) }}">

                                        @error('resttime_end_at')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-2">
                                        <label>Giờ giải lao (phút)</label>
                                        <input class="form-control" type="text" name="resttime_minutes" disabled value="{{ old('resttime_minutes', $work->resttime_minutes) }}">
                                        <input class="form-control" type="hidden" name="resttime_minutes" value="{{ old('resttime_minutes', $work->resttime_minutes) }}">
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Thời gian bắt đầu tuyển dụng</label>
                                        <input class="form-control @error('recruitment_start_at') is-invalid @enderror" type="datetime-local" name="recruitment_start_at"
                                                value="{{ old('recruitment_start_at', $work->input_recruitment_start) }}">
                                        
                                                @error('recruitment_start_at')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Thời hạn tuyển dụng</label>
                                        <select class="form-control select-search" name="deadline_type">
                                            @foreach($dataSelect['deadline_types'] as $k => $v)
                                            <option value="{{$k}}" @if( old('deadline_type', $work->deadline_type) == $k) selected @endif>{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                           
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Số lượng tuyển<span class="text-danger ml-1">*</span></label>
                                            <input type="number" class="form-control @error('recruitment_person_count') is-invalid @enderror"
                                                name="recruitment_person_count" placeholder="" min="1"
                                                value="{{ old('recruitment_person_count', 1, $work->recruitment_person_count) }}">
                                            
                                        @error('recruitment_person_count')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Lương (theo giờ)<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control @error('hourly_wage') is-invalid @enderror"
                                            name="hourly_wage" id="hourly_wage" placeholder="" value="{{ old('hourly_wage', $work->hourly_wage) }}" style="display: block;">
                                        
                                        <span class="text-muted font-size-sm">
                                            Kiểm tra mức lương tối thiểu <a href="https://luatvietnam.vn/lao-dong-tien-luong/bang-tra-cuu-luong-toi-thieu-vung-nam-2021-562-23102-article.html" target="_blank">ở đây.</a>
                                        </span>
                                        @error('hourly_wage')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Chi phí vận chuyển<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control @error('transportation_fee') is-invalid @enderror" placeholder="" name="transportation_fee"
                                            value="{{ old('transportation_fee', $work->transportation_fee) }}">
                                        @error('transportation_fee')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><b><i class="far fa-file-alt"></i> Nội dung công việc </b></h4>
                            <div class="card-tools">
                                <span class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label>Tiêu đề<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $work->title) }}"
                                            placeholder="VD. Senior UX Designer">
                                        @error('title')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>Hình thức tuyển dụng</label>
                                        <select class="form-control select-search" name="work_type">
                                            @foreach($dataSelect['employments'] as $k => $v)
                                            <option value="{{$k}}" @if(old('work_type', $work->work_type) == $k) selected @endif>{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Ngành nghề<span class="text-danger ml-1">*</span></label>
                                        <select class="form-control select-search @error('category_id') is-invalid @enderror" name="category_id">
                                            @foreach($dataSelect['categories'] as $k => $v)
                                            <option value="{{$k}}" @if(old('category_id', $work->category_id) == $k) selected @endif>{{$v}}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="validation-invalid-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Loại hình công việc</label>
                                        <select class="form-control select-search" name="occupation_id">
                                            @foreach($dataSelect['occupations'] as $k => $v)
                                                <option value="{{$k}}" @if(old('occupation_id', $work->occupation_id) == $k) selected @endif>{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Nội dung công việc</label>
                                <textarea name="content" class="form-control richTextBox" placeholder="">{!! old('content', $work->content) !!}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Địa chỉ cơ quan<span class="text-danger ml-1">*</span></label>
                                <input type="text"  name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $work->address,) }}"
                                    placeholder="Hà Nội">
                                @error('address')
                                    <div class="validation-invalid-label">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Chỉ dẫn tới nơi làm việc</label>
                                <textarea rows="5" cols="5" name="access" class="form-control" placeholder="Đi bộ 10 phút từ bến xe.">{!! old('access', $work->access) !!}</textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Người Liên Hệ</label>
                                    <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $work->contact_name) }}" placeholder="VD. Nguyễn Văn Hiệp">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Số liên lạc</label>
                                    <input type="text" class="form-control contact_tel" name="contact_tel" value="{{ old('contact_tel', $work->contact_tel) }}" placeholder="090-1234-5678">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Yêu cầu dụng cụ của bạn</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="things_to_bring1"
                                            value="{{ old('things_to_bring1', $work->things_to_bring1) }}"
                                            placeholder="Bút bi">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="things_to_bring2"
                                            value="{{ old('things_to_bring2', $work->things_to_bring2) }}"
                                            placeholder="Sổ tay">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="things_to_bring3"
                                            value="{{ old('things_to_bring3', $work->things_to_bring3) }}"
                                            placeholder="Phương tiện di chuyển">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="things_to_bring4"
                                            value="{{ old('things_to_bring4', $work->things_to_bring4,) }}"
                                            placeholder="Quần áo dễ di chuyển">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="things_to_bring5"
                                            value="{{ old('things_to_bring5', $work->things_to_bring5) }}"
                                            placeholder="Máy tính">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Yêu cầu tuyển dụng</label>
                                @php
                                    $tag = $dataTypeContent['tag_ids'] ?? [];
                                    $tag_ids = old('tags', $tag);
                                @endphp
                                @foreach($dataSelect['tags'] as $array_tag)
                                    <div class="row">
                                    @foreach($array_tag as $k => $v)
                                        <div class="col-sm-3 mb-10">
                                            <div class="checkbox icheck-primary">
                                                <input type="checkbox" class="custom-control-input" id="tags{{$k}}" name="tags[]" value="{{$k}}"
                                                    @if(in_array($k, $tag_ids)) checked @endif>
                                                <label class="custom-control-label" for="tags{{$k}}">{{$v}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Cấp bậc</label>
                                @php
                                    $qualification = $dataTypeContent['qualification_ids'] ?? [];
                                    $qualification_ids = old('qualifications', $qualification);
                                @endphp
                                @foreach($dataSelect['qualifications'] as $array_qualification)
                                <div class="row">
                                    @foreach($array_qualification as $k => $v)
                                    <div class="col-lg-3 mb-10">
                                        <div class="checkbox icheck-primary">
                                            <input type="checkbox" class="custom-control-input" name="qualifications[]" value="{{$k}}" id="qualification{{$k}}"
                                                @if(in_array($k, $qualification_ids)) checked @endif>
                                            <label class="custom-control-label" for="qualification{{$k}}">{{$v}}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea class="form-control richTextBox" placeholder="" name="notes">{!! old('notes', $work->notes) !!}</textarea>
                            </div>

                            <div class="form-group">
                                <label>File mô tả</label>
                                <div id="actions" class="row">
                                    <div class="col-sm-6 mb-0">
                                        <div class="btn-group w-100">
                                            <span class="btn btn-success col fileinput-button">
                                                <i class="fas fa-plus"></i> <span>Add files</span>
                                            </span>
                                            <button type="submit" class="btn btn-primary col start">
                                                <i class="fas fa-upload"></i> <span>Start upload</span>
                                            </button>
                                            <button type="reset" class="btn btn-danger col delete">
                                                <i class="fas fa-times-circle"></i> <span>Delete upload</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6  align-items-center hidden">
                                        <div class="fileupload-process w-100">
                                            <div id="total-progress" class="progress progress-striped active mb-0" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table table-striped files" id="previews">
                                    <div id="template" class="row mt-2 d-flex col-sm-12">
                                        <div class="col-auto">
                                            <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                        </div>
                                        <div class="col-sm-6 d-flex align-items-center mb-0">
                                            <p class="mb-0">
                                                <span class="lead" data-dz-name></span>
                                                (<span data-dz-size></span>)
                                            </p>
                                            <strong class="error text-danger" data-dz-errormessage></strong>
                                        </div>
                                        <div class="col-sm-3 d-flex align-items-center mb-0">
                                            <div class="progress progress-striped active w-100 mb-0" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 d-flex align-items-center mb-0">
                                            <div class="btn-group">
                                                <button class="btn btn-primary start"><i class="fas fa-upload"></i> <span>Start</span></button>
                                                <button data-dz-remove class="btn btn-danger delete"><i class="fas fa-trash"></i> <span>Delete</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(isset($dataTypeContent->work_photos))
                                <div class="row">
                                    <div class="col-sm-12 list_file">
                                        <label><b>Danh sách file mô tả đã tải lên</b></label>
                                        <ol style="padding-left: 15px;">
                                            @foreach ($dataTypeContent->work_photos as $photo)
                                            <li class="mb-1" id="filr-list-{{$dataTypeContent->id}}-{{$photo->id}}">
                                                <a href="{{ Storage::url($photo->url) }}" target="_blank">{{ $photo->title }}</a>
                                                <a href="javascript:;" class="btn btn-danger btn-sm delete delete_file_work" style="padding: 5px 10px;" title="Delete file" data-url="{{ $photo->url }}" data-id="{{ $photo->id }}" data-work_id="{{$dataTypeContent->id}}">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title"><b><i class="fas fa-cogs"></i> Kỹ năng</b></h5>
                            <div class="card-tools">
                                <span class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label>Chọn các kỹ năng bạn cần cho nhiệm vụ này</label>
                                @php
                                    $skill = $dataTypeContent['skill_ids'] ?? [];
                                    $skill_ids = old('skills', $skill);
                                @endphp
                                @foreach($dataSelect['skills'] as $data_skills)
                                    <div class="row">
                                        @foreach($data_skills as $k => $v)
                                        <div class="col-lg-3 mb-10">
                                            <div class="checkbox icheck-primary">
                                                <input type="checkbox" class="custom-control-input" id="skill_{{ $k }}" name="skills[]" value="{{$k}}"
                                                    @if(in_array($k, $skill_ids)) checked @endif>
                                                <label class="custom-control-label" for="skill_{{ $k }}">{{$v}}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Thêm điều kiện <span style="color: #9c9494; font-size:small;">(tối đa 5)</span></label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control mt-2" placeholder="" name="condition1" value="{{ old('condition1', $work->condition1) }}">
                                        <input type="text" class="form-control mt-2" placeholder="" name="condition2" value="{{ old('condition2', $work->condition2) }}">
                                        <input type="text" class="form-control mt-2" placeholder="" name="condition3" value="{{ old('condition3', $work->condition3) }}">
                                        <input type="text" class="form-control mt-2" placeholder="" name="condition4" value="{{ old('condition4', $work->condition4) }}">
                                        <input type="text" class="form-control mt-2" placeholder="" name="condition5" value="{{ old('condition5', $work->condition5) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-footer">
                @section('submit-buttons')
                    <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                @stop
                @yield('submit-buttons')
            </div>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
            <input name="image" id="upload_file" type="file"
                        onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
            {{ csrf_field() }}
        </form>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.are_you_sure_delete') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script src="{{ asset('/template/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('/template/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('/js/work/work.js') }}"></script>
   

    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        @php
            $work_time_start = (!$work->worktime_start_at || isset($copy)) ? \Carbon\Carbon::now() : null;
        @endphp
        const KAIGO_MIN_WORK_TIME = @JSON($work_time_start);
        const KAIGO_work_photo = @JSON($dataTypeContent['work_photos']);
    </script>

    <script>
        Dropzone.autoDiscover = false;

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var uploadedDocumentMap = {}
        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "{{ route('dropzone.upload')}}", // Set the url
            thumbnailWidth: 50,
            thumbnailHeight: 50,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (file, response) {
                $('form').append("<input type='hidden' name='work_photo[]' value='" + response + "'>");
                uploadedDocumentMap[file.name] = response
            },
        });

        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function (e) {
                e.preventDefault();
                myDropzone.enqueueFile(file);
            };
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function (progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function (file) {
            document.querySelector("#total-progress").style.opacity = "1";
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
            file.previewElement.querySelector(".delete").setAttribute("disabled", "disabled");
        });

        document.querySelector("#actions .start").onclick = function (e) {
            e.preventDefault();
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector("#actions .delete").onclick = function () {
            myDropzone.removeAllFiles(true);
        };
        // DropzoneJS Demo Code End
    </script>

    <script>
         // inputmask phone
         $(".contact_tel").inputmask({"mask": "999-999-9999"});
    </script>
@stop
