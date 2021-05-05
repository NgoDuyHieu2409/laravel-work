<x-app-layout>
    <x-slot name="header">
        <form class="" method="post" action="">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <input type="search" class="form-control" placeholder="Tên công việc hoặc vị trí muốn ứng tuyển..." value="Develop">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mb-0">
                            <select class="select-search" style="width: 100%;">
                                <option value="">Tất cả địa điểm</option>
                                <option value="1">ASC</option>
                                <option value="2">DESC</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mb-0">
                            <select class="select-search" style="width: 100%;">
                                <option value="">Tất cả ngành nghề</option>
                                <option value="1">ASC</option>
                                <option value="2">DESC</option>
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
                    <div class="text-right" id="show_advanced" style="color: #fff; padding-top: 10px;font-size: 13px">
                        <span id="btn-clear-filter" style="">
                            <i class="fa fa-times"></i>&nbsp;Xóa tìm kiếm nâng cao
                            &nbsp; &#124; &nbsp;
                        </span>

                        <span id="btn-show-advanced-search">
                            <i class="fa fa-angle-down"></i>&nbsp;Chọn tìm kiếm nâng cao
                        </span>
                        <span id="btn-hidden-advanced-search" style="">
                            &nbsp; &#124; &nbsp;
                            <i class="fa fa-angle-up"></i>&nbsp;Ẩn tìm kiếm nâng cao
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>

    <div class="container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                @foreach($works as $work)
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="info-box">
                            <span class="info-box-icon border-r-2 border-red-500 pl-2 pr-2" style="width: 15%;">
                                <img src="{{ Storage::url($work->company->logo)}}" alt="Logo Company">
                            </span>
                            <div class="info-box-content">
                                <div class="row ml-1 mb-2">
                                    <div class="col-12 col-sm-9">
                                        <span class="info-box-text text-left text-uppercase text-bold">
                                            <a href="{{ route('work.show', ['id' => $work->id]) }}">{{ $work->title }}</a>
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
                                            @if ($work->company->name)
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
                                            <span class="js-btn-like float-right text-warning text-3xl animation" data-animation="pulse" style="cursor: pointer;">
                                                <i class="far fa-heart"></i>
                                            </span>

                                            <span class="js-btn-dislike float-right text-warning text-3xl animation" data-animation="pulse" style="cursor: pointer;display: none;">
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
        </div>
    </div>

</x-app-layout>