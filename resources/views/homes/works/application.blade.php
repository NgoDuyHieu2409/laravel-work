<x-app-layout>
    <style>
    .sticky {
        position: fixed !important;
        top: 65px;
        width: 19% !important;
        right: 10%;
    }

    .mt-5 {
        margin-top: 50px;
    }
    </style>

    <div class="container">
        <div class="row pt-3">
            <div class="col-md-9 col-sm-12">
                <div class="row mb-2">
                    <div class="col-sm-12" style="font-size: 15px;">
                        @if($request->isConfirm)
                        <a href="{{ route('work.application', ['isConfirm' => 0]) }}" class="text-warning">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;
                            Công việc chưa tham gia.
                        </a>
                        @else
                        <a href="{{ route('work.application', ['isConfirm' => 1]) }}" class="text-success">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;
                            Công việc đã tham gia.
                        </a>
                        @endif
                    </div>
                </div>

                <div class="max-w-7xl mx-auto">
                    @if($works->count())
                    @csrf
                    <div class="overflow-hidden sm:rounded-lg">
                        @foreach($works as $work)
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="info-box">
                                    <span class="info-box-icon border-r-2 border-red-500 pl-2 pr-2" style="width: 15%;">
                                        <img src="{{ Storage::url($work->company->logo ?? '')}}" alt="">
                                    </span>
                                    <div class="info-box-content">
                                        <div class="row ml-1 mb-2">
                                            <div class="col-12 col-sm-9">
                                                <span class="info-box-text text-left text-uppercase text-bold">
                                                    <a
                                                        href="{{ route('work.show', ['id' => $work->id]) }}">{{ $work->title }}</a>
                                                </span>
                                            </div>
                                            <div class="col-12 col-sm-3">
                                                <span class="info-box-text test-left">
                                                    <span class="float-right text-warning">
                                                        <i class="fas fa-dollar-sign"></i>
                                                        {{ number_format($work->hourly_wage) }}/giờ
                                                    </span>
                                                </span>
                                            </div>
                                        </div>

                                        @if($work->work_tags)
                                        <div class="row col-9 ml-1">
                                            @foreach($work->work_tags as $tag)
                                            <div class="callout callout-danger p-1 mr-2 small">
                                                {{ $workTags[$tag->tag_id] }}
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        <div class="row col-12">
                                            <span class="info-box-number">
                                                <ul class="ml-2 mb-0 fa-ul">
                                                    @if (isset($work->company->name))
                                                    <li class="small">
                                                        <i class="fas fa-lg fa-building"></i>
                                                        {{ $work->company->name}}
                                                    </li>
                                                    @endif

                                                    @if ($work->address)
                                                    <li class="small">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        {{ $work->address}}
                                                    </li>
                                                    @endif

                                                    @if ($work->contact_name)
                                                    <li class="small">
                                                        <i class="fas fa-lg fa-user"></i>
                                                        {{ $work->contact_name}}
                                                    </li>
                                                    @endif

                                                    @if ($work->contact_tel)
                                                    <li class="small">
                                                        <i class="fas fa-lg fa-phone"></i>
                                                        {{ $work->contact_tel}}
                                                    </li>
                                                    @endif
                                                </ul>
                                            </span>
                                        </div>

                                        <div class="row ml-1 mt-3">
                                            <div class="row col-12 col-sm-9">
                                                @if($work->work_skills)
                                                @foreach($work->work_skills as $skill)
                                                <div class="p-1 mr-2 small">
                                                    <i class="fas fa-arrow-right"></i>
                                                    {{ $workSkills[$skill->skill_id] }}
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                            @auth
                                            <div class="col-12 col-sm-3">
                                                <span class="info-box-text test-left save_work">
                                                    <span data-work="{{ $work->id }}"
                                                        class="js-btn-like float-right text-warning text-3xl animation"
                                                        data-animation="pulse"
                                                        style="cursor: pointer; @if($work->is_favorite) display: none; @endif">
                                                        <i class="far fa-heart"></i>
                                                    </span>

                                                    <span data-work="{{ $work->id }}"
                                                        class="js-btn-dislike float-right text-warning text-3xl animation"
                                                        data-animation="pulse"
                                                        style="cursor: pointer; @if(!$work->is_favorite) display: none; @endif">
                                                        <i class="fas fa-heart"></i>
                                                    </span>
                                                </span>
                                            </div>
                                            @endauth
                                        </div>
                                        @auth
                                        <hr class="hr-custom mt-0">
                                        @if(!$request->isConfirm)
                                        <div class="text-right">
                                            @if($work->is_confirm)
                                            <button data-id="{{$work->id}}" class="btn btn-sm btn-warning btn-js-confirm-work">Confirm Approval</button>
                                            @else
                                            <button data-id="{{$work->id}}" class="btn btn-sm btn-danger btn-js-un-application">Hủy yêu cầu</button>
                                            @endif
                                        </div>
                                        @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <?= $works->render(); ?>
                    @else
                    <div class="alert alert-warning alert-dismissible text-center">
                        <span class="text-2xl"><i class="icon fas fa-exclamation-triangle"></i></span>
                        Hiện không có công việc nào.
                    </div>
                    @endif
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
    <script src="{{ asset('js/favorite_work.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.btn-js-un-application').click(function() {
            var confirm = window.confirm('Bạn có muốn hủy yêu cầu công việc này?');
            if (confirm == true) {
                var data = {
                    work_id: $(this).data('id'),
                    _token: "{{ csrf_token() }}",
                };
                $.ajax({
                    url: "{{ route('work.unapply') }}",
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if (data.status) {
                            toastr.success(data.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(data.message)
                        }
                    }
                });
            }
        });

        $('.btn-js-confirm-work').click(function() {
            var confirm = window.confirm('Bạn có đồng ý tiếp nhận công việc này?');
            if (confirm == true) {
                var data = {
                    work_id: $(this).data('id'),
                    _token: "{{ csrf_token() }}",
                };
                $.ajax({
                    url: "{{ route('work.confirm') }}",
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if (data.status) {
                            toastr.success(data.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(data.message)
                        }
                    }
                });
            }
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