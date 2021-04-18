<x-app-layout>
    <x-slot name="header">
        <form class="form-inline mr-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden sm:rounded-lg">
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
        </div>
    </div>
</x-app-layout>
