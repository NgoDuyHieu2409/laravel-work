<x-app-layout>
    <style>
    .sticky {
        position: fixed;
        top: 65px;
        width: 21%;
        right: 8%;
    }
    </style>

    <x-slot name="header">
        <form class="" method="get" action="">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <input type="search" class="form-control" name="work_name"
                                placeholder="Tên công việc hoặc vị trí muốn ứng tuyển..."
                                value="{{ $request->work_name ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mb-0">
                            <select class="select-search" name="city" style="width: 100%;">
                                <option value="">Tất cả địa điểm</option>
                                @foreach($advancedSearch['citys'] as $key => $value)
                                <option value="{{ $key }}" @if($key==$request->city) selected @endif>{{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mb-0">
                            <select class="select-search" name="category" style="width: 100%;">
                                <option value="">Tất cả ngành nghề</option>
                                @foreach($advancedSearch['categories'] as $key => $value)
                                <option value="{{ $key }}" @if($key==$request->category) selected @endif>{{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                {{-- Tìm kiếm nâng cao --}}
                <div class="col-xs-12">
                    <div class="text-right" id="show_advanced" style="color: #fff; padding-top: 10px; font-size: 13px;">
                        <span id="btn-show-advanced-search" data-toggle="collapse" data-target="#advanced_search"
                            style="cursor: pointer;">
                            <i class="fa fa-angle-down"></i>&nbsp;Chọn tìm kiếm nâng cao
                        </span>
                        <span id="delete_advanced_search" style="display: none;">
                            <span id="btn-clear-filter" style="cursor: pointer;">
                                <i class="fa fa-times"></i>&nbsp;Xóa tìm kiếm nâng cao
                                &nbsp; &#124; &nbsp;
                            </span>
                            <span id="btn-hidden-advanced-search" style="cursor: pointer;" data-toggle="collapse"
                                data-target="#advanced_search">
                                <i class="fa fa-angle-up"></i>&nbsp;Ẩn tìm kiếm nâng cao
                            </span>
                        </span>
                    </div>
                </div>

                <div id="advanced_search" class="collapse mt-2">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Mức lương</label>
                                <select class="select-search" name="hourly_wage" style="width: 100%;">
                                    <option value="">Tất cả mức lương</option>
                                    @foreach($advancedSearch['search_hourly_wage'] as $key => $value)
                                    <option value="{{$key}}" @if($key==$request->hourly_wage) selected
                                        @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Loại hình làm việc</label>
                                <select class="select-search" name="occupation" style="width: 100%;">
                                    <option value="">Tất cả loại hình</option>
                                    @foreach($advancedSearch['occupations'] as $key => $value)
                                    <option value="{{ $key }}" @if($key==$request->occupation) selected
                                        @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Khoản thời gian bắt đầu</label>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="date" name="form_date" id="" class="form-control"
                                            value="{{$request->form_date ?? ''}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="date" name="to_date" id="" class="form-control"
                                            value="{{$request->to_date ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <div class="max-w-7xl mx-auto">
                    @if($works->count())
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
                                                    <span
                                                        class="js-btn-like float-right text-warning text-3xl animation"
                                                        data-animation="pulse" style="cursor: pointer;">
                                                        <i class="far fa-heart"></i>
                                                    </span>

                                                    <span
                                                        class="js-btn-dislike float-right text-warning text-3xl animation"
                                                        data-animation="pulse" style="cursor: pointer;display: none;">
                                                        <i class="fas fa-heart"></i>
                                                    </span>
                                                </span>
                                            </div>
                                            @endauth
                                        </div>
                                        @auth
                                        <hr class="hr-custom mt-0">
                                        <div class="text-right">
                                            <a href="{{ route('work.show', ['id' => $work->id]) }}"
                                                class="btn btn-sm btn-success">Nộ hồ sơ</a>
                                        </div>
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
                        Hiện không có công việc nào phù hợp với tiêu chí này.
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#btn-show-advanced-search').on('click', function() {
            $(this).css('display', 'none');
            $('span#delete_advanced_search').css('display', 'unset');
        });

        $('#btn-hidden-advanced-search').on('click', function() {
            // $('#delete_advanced_search').trigger('click');
            $(this).parent().css('display', 'none');
            $('span#btn-show-advanced-search').css('display', 'unset');
        });

        $('#delete_advanced_search').on('click', function() {
            $('#advanced_search').find('input').val('');
            $('#advanced_search').find('select').val('').change();
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