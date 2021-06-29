<x-app-layout>
    <style>
    .sticky {
        position: fixed !important;
        top: 65px;
        width: 19% !important;
        right: 10%;
    }
    </style>
    <div class="container">
        <div class="clearfix" style="height: 30px;"></div>
        <div class="row">
            <div class="col-sm-12 col-md-9">
                <div class="max-w-7xl mx-auto">
                    <div class="overflow-hidden sm:rounded-lg">
                        <div class="align-items-stretch">
                            <div class="card">
                                <div class="card-body">
                                    <blockquote>
                                        <div class="col-12">
                                            <h1 class="header-box-list detail-post-ntv fs-20-mb float-left mb-0">
                                                <span class="title text-uppercase">{{ $work->title }}</span>
                                            </h1>
                                            <button type="button" class="btn btn-success btn-sm float-right btn-apply-work" data-work_id="{{ $work->id }}">Nộp hồ sơ</button>
                                        </div>
                                        <div class="row col-12 small">
                                            <label class="mb-0 ml-2">Địa Điểm Làm Việc:</label> {{ $work->address }}
                                        </div>
                                    </blockquote>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul class="ul-list-item">
                                                <li><label>Mức lương:</label> {{ $work->hourly_wage }}/giờ</li>
                                                <li><label>Ngành nghề:</label> {{ $work->category_name }}</li>
                                                <li><label>Số lượng tuyển dụng:</label> {{ $work->recruitment_person_count }}</li>
                                                <li><label>Loại hình công việc:</label> {{ $work->work_type }}</li>
                                                <li><label>Trình độ:</label> {{ $work->qualification_name }}</li>
                                            </ul>
                                        </div>

                                        <div class="col-sm-6">
                                            <ul class="ul-list-item">
                                                <li><label>Liên hệ:</label> {{ $work->contact_name }}</li>
                                                <li><label>Sô điện thoại:</label> {{ $work->contact_tel }}</li>
                                                <li><label>Yêu cầu tuyển dụng:</label> {{ $work->tags }}</li>
                                                <li><label>Hạn nộp:</label> <span class="text-red">{{ date('d-m-Y', strtotime($work->worktime_start_at)) }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr class="hr-custom">

                                    <div class="row">
                                        <div class="col-12">
                                            <label>Nội Dung Công Việc</label>
                                            {!! $work->content !!}
                                        </div>

                                        <div class="col-12">
                                            <label>Yêu Cầu Kỹ Năng</label>
                                            <ul class="ul-list-item ml-3">
                                                @foreach ($work->skills as $item)
                                                <li>{{ $item }}</li>
                                                @endforeach

                                                @for ($i = 1; $i <= 5; $i++)
                                                {!! $work->{'condition' . $i} ? '<li>'. $work->{'condition' . $i} . '</li>' : null !!}
                                                @endfor
                                            </ul>
                                        </div>

                                        <div class="col-12">
                                            <label>Ghi Chú</label>
                                            {!! $work->notes ?? '<p>Không có yêu cầu gì thêm!</p>' !!}
                                        </div>

                                        <div class="col-12">
                                            <label>File Mô Tả</label>
                                            @if(isset($work->work_photos))
                                            <div class="row">
                                                <div class="col-sm-12 list_file">
                                                    <ul class="ul-list-item ml-3">
                                                        @foreach ($work->work_photos as $photo)
                                                        <li class="mb-1" id="filr-list-{{$work->id}}-{{$photo->id}}">
                                                            <a href="{{ Storage::url($photo->url) }}" target="_blank">{{ $photo->title }}</a>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <button type="button" class="btn btn-success btn-sm float-right btn-apply-work" data-work_id="{{$work->id}}">Nộp hồ sơ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-12" id="work-advandced">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title text-uppercase text-cyan">Công việc nổi bật</h3>
                    </div>
                    <div class="card-body">
                        @foreach($outstandingWorks as $work)
                        <div class="justify-content-between align-items-center border-bottom mb-3 small">
                            <span class="info-box-text text-uppercase mb-2">
                                <a href="{{ route('work.show', ['id' => $work->id]) }}">{{ $work->title }}</a>
                            </span>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <i class="fas fa-dollar-sign text-success"></i>
                                    {{ number_format($work->hourly_wage) }}/giờ
                                </div>
                                <div class="col-sm-6">
                                    <i class="far fa-clock text-success"></i>
                                    {{ date('d/m/Y', strtotime($work->worktime_start_at)) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('.btn-apply-work').click(function(){
                    $('.btn-apply-work').hide();
                    var data = {
                        work_id: $(this).data('work_id'),
                        _token: "{{ csrf_token() }}",
                    };
                    $.ajax({
                        url: "{{ route('work.apply') }}",
                        type:'post',
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                            if(data.status){
                                toastr.success(data.message);
                            }
                            else{
                                $('.btn-apply-work').show();
                                toastr.error(data.message);
                            }
                        }
                    });
                });
            })
        </script>

        <script>
            window.onscroll = function() {
                myFunction()
            };

            var navbar = document.getElementById("work-advandced");
            var sticky = navbar.offsetTop;
            var bottom = navbar.offsetHeight;

            function myFunction() {
                if (window.pageYOffset >= sticky) {
                    navbar.classList.add("sticky")
                } else {
                    navbar.classList.remove("sticky");
                }
            }
        </script>
    @endpush
</x-app-layout>