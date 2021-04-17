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
            @if($edit) {{ method_field("PUT") }} @endif

            <!-- CSRF TOKEN -->
            @csrf

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h4 class="card-title"><b><i class="far fa-edit"></i> Thông tin công ty</b></h4>
                            {{-- <div class="card-tools">
                                <span class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </span>
                            </div> --}}
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tên công ty<span class="text-danger ml-1">*</span></label>
                                        <input name="name" type="text" class="form-control" placeholder="" value="{{old('name', $dataTypeContent->name)}}">
                                        <div class="validation-invalid-label name-error"></div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Tên công ty <span class="text-muted small">(Tiếng anh)</span></label>
                                        <input name="name_english" type="text" class="form-control" placeholder="" value="{{old('name_english', $dataTypeContent->name_english)}}">
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Email<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control" placeholder="" name="email" value="{{old('email', $dataTypeContent->email)}}">
                                        <div class="validation-invalid-label email-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="tel">Số điện thoại<span class="text-danger ml-1">*</span></label>
                                    <input type="text" class="form-control phone-number" name="tel" id="tel" placeholder="00-0000-0000" value="{{old('tel', $dataTypeContent->tel)}}">
                                    <div class="validation-invalid-label tel-error"></div>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Người đại diện<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control" name="contact_name" value="{{old('contact_name', $dataTypeContent->contact_name)}}">
                                        <div class="validation-invalid-label contact_name-error"></div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Người đại diện <span class="text-muted small">(Tiếng anh)</span></label>
                                        <input type="text" class="form-control" name="contact_name_english" value="{{old('contact_name_english', $dataTypeContent->contact_name_english)}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tỉnh/ Thành phố<span class="text-danger ml-1">*</span></label>
                                        <select id="city" name="city" class="form-control select-search">
                                            <option value="">Vui lòng chọn tỉnh/ thành phố</option>
                                            @foreach($dataSelect['city'] as $key => $value)
                                                <option value="{{$key}}" @if(old('city', $dataTypeContent->city) == $key) selected @endif>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <div class="validation-invalid-label city-error"></div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="pref">Quận huyện<span class="text-danger ml-1">*</span></label><br>
                                        <select id="pref" name="pref" class="form-control select-search">
                                            <option value="">Vui lòng chọn quận huyện</option>
                                        </select>
                                        <div class="validation-invalid-label pref-error"></div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="address">Địa chỉ chi tiết</label>
                                        <textarea class="form-control" name="address" id="address" rows='1'>{{old('address', $dataTypeContent->address)}}</textarea>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="zipcode">Mã bưu điện<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="" size="10" value="{{old('zipcode', $dataTypeContent->zipcode)}}">
                                        <div class="validation-invalid-label zipcode-error"></div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <label for="website_url">Trang web công ty <span class="text-muted small">(URL)</span></label>
                                <input type="text" class="form-control" placeholder="" name="website_url" id="website_url" value="{{old('website_url', $dataTypeContent->website_url)}}">
                            </div>
                    
                            <div class="form-group">
                                <label>Logo công ty<span class="text-danger ml-1">*</span></label>
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
                                    <div class="template-upload row mt-2 d-flex col-sm-12">
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
                            </div>
                            @if(isset($dataTypeContent->logo))
                            <div class="form-group">
                                <img src="{{ Voyager::image($dataTypeContent->logo) }}" alt="Logo" width="100">
                            </div>
                            @endif
                    
                            <div class="form-group">
                                <label>Giới thiệu về công ty</label>
                                <textarea rows="15" cols="5" class="form-control" placeholder="" name="description">{!! old('description', $dataTypeContent->description) !!}</textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </div>
                </div>
            </div>	
        </form>
    </div>

    </div>
@stop

@section('javascript')
    <script src="{{ asset('/template/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('/js/company/company.js') }}"></script>

    <script>
        const COMPANY_PREF = @JSON($dataTypeContent['pref']);
    </script>

    <script>
        Dropzone.autoDiscover = false;

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector(".template-upload");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var uploadedDocumentMap = {}
        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: '{{ route("dropzone.upload", ["folder" => "Companies"]) }}',
            thumbnailWidth: 50,
            thumbnailHeight: 50,
            parallelUploads: 20,
            maxFiles: 1,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (file, response) {
                $('form').append("<input type='hidden' name='logo' value='" + response + "'>");
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
        });

        document.querySelector("#actions .start").onclick = function (e) {
            e.preventDefault();
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector("#actions .delete").onclick = function () {
            myDropzone.removeAllFiles(true);
            $('form').find("input[name='logo']").remove();
        };
        // DropzoneJS Demo Code End
    </script>

@stop
