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
                            <h4 class="card-title"><b><i class="far fa-edit"></i> Thông tin tuyển dụng</b></h4>
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
                                        <label>Lĩnh vực kinh doanh<span class="text-danger ml-1">*</span></label>
                                        <select class="form-control select-search @error('type') is-invalid @enderror" name="type">
                                            <option value="">Vui lòng chọn lĩnh vực kinh doanh</option>
                                            {{-- @foreach(Config::get('const.categorys') as $k => $v)
                                            <option value="{{$k}}" @if(old('type') == $k) selected @endif>{{$v}}</option>
                                            @endforeach --}}
                                        </select>
                                        @error('type')
                                        <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror   
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tên công ty<span class="text-danger ml-1">*</span></label>
                                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="" value="{{old('name')}}">
                                        @error('name')
                                        <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Tên công ty <span class="text-muted small">(Tiếng anh)</span></label>
                                        <input name="name_english" type="text" class="form-control" placeholder="" value="{{old('name_english')}}">
                                        
                                        @error('name_english')
                                        <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Email<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="" name="email" value="{{old('email')}}">
                                        
                                        @error('email')
                                        <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tel">Số điện thoại<span class="text-danger ml-1">*</span></label>
                                    <input type="text" class="form-control phone-number @error('tel') is-invalid @enderror" name="tel" id="tel" placeholder="00-0000-0000" value="{{old('tel')}}">
                                    
                                    @error('tel')
                                        <div class="validation-invalid-label">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Người phụ trách<span class="text-danger ml-1">*</span></label>
                                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{old('contact_name')}}">
                                        
                                        @error('contact_name')
                                        <div class="validation-invalid-label">{{$errors->first('contact_name')}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Người phụ trách <span class="text-muted small">(Tiếng anh)</span></label>
                                        <input type="text" class="form-control @error('contact_name_english') is-invalid @enderror" name="contact_name_english" value="{{old('contact_name_english')}}">
                                        
                                        @error('contact_name')
                                        <div class="validation-invalid-label">{{$errors->first('contact_name')}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('zipcode1') || $errors->has('zipcode2')) has-danger @endif">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tỉnh/ Thành phố<span class="text-danger ml-1">*</span></label>
                                        <select id="city" name="city" class="form-control select-search  @error('city') is-invalid @enderror">
                                            <option value="">Vui lòng chọn tỉnh/ thành phố</option>
                                            @foreach($dataSelect['city'] as $key => $value)
                                                <option value="{{$key}}" @if(old('city') == $key) selected @endif>{{$value}}</option>
                                            @endforeach
                                        </select>

                                        @error('city')
                                            <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="pref">Quận huyện<span class="text-danger ml-1">*</span></label><br>
                                        <select id="pref" name="pref" class="form-control select-search  @error('pref') is-invalid @enderror">
                                            <option value="">Vui lòng chọn quận huyện</option>
                                            {{-- @foreach(Config::get('const.pref') as $key => $value)
                                                <option value="{{$key}}" @if(old('pref') == $key) selected @endif>{{$value}}</option>
                                            @endforeach --}}
                                        </select>

                                        @error('pref')
                                            <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="address">Địa chỉ chi tiết</label>
                                        <textarea class="form-control" name="address" id="address" rows='1'>{{old('address')}}</textarea>

                                        @error('address')
                                            <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="zipcode1">Mã bưu điện<span class="text-danger ml-1">*</span></label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control @error('zipcode1') is-invalid @enderror" name="zipcode1" id="zipcode1" placeholder="" size="5" 
                                                value="{{old('zipcode1')}}"> -

                                            <input type="text" class="form-control @error('zipcode2') is-invalid @enderror" name="zipcode2" id="zipcode2" placeholder="" size="7"
                                                value="{{old('zipcode2')}}">
                                            {{-- <button type="submit" class="btn-sm btn-light ml-3">住所検索</button> --}}
                                        </div>
                                        @error('zipcode1')
                                            <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                        @error('zipcode2')
                                            <div class="validation-invalid-label">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                    
                            {{-- <div class="form-group">
                                <label>アクセス<span class="text-danger ml-1">*</span></label>
                                <textarea rows="15" cols="5" class="form-control @error('access') is-invalid @enderror" placeholder=""
                                    name="access">{{old('access')}}</textarea>
                                @if($errors->has('access'))
                                    <label id="access" class="error mt-2 text-danger" for="access">{{$errors->first('access')}}</label>
                                @endif
                            </div> --}}
                    
                            <div class="form-group">
                                <label for="website_url">Trang web công ty <span class="text-muted small">(URL)</span></label>
                                <input type="text" class="form-control @error('website_url') is-invalid @enderror" placeholder="" name="website_url" id="website_url" value="{{old('website_url')}}">
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
                    
                            <div class="form-group">
                                <label>Giới thiệu về công ty</label>
                                <textarea rows="15" cols="5" class="form-control richTextBox" placeholder="" name="description">{{old('description')}}</textarea>
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
        $(function(){
            $('#city').on('change', function () {
                var data = {
                    _token: "{{ csrf_token() }}",
                    city_id: $(this).val()
                };

                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:'/get-districts',
                    data: data,
                    success:function(response){
                        console.log(response)
                    }
                });
            });
        });
        
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

@stop
