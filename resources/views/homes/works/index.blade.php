<x-app-layout>
    {{-- <x-slot name="header">
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
    </x-slot> --}}

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
                                        <span class="info-box-text test-left">
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
                                            <li class="small">
                                                <i class="fas fa-lg fa-building"></i>
                                                Tên công ty
                                            </li>
                                            <li class="small">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $work->address}}
                                            </li>
                                            <li class="small">
                                                <i class="fas fa-lg fa-user"></i>
                                                {{ $work->contact_name}}
                                            </li>
                                            <li class="small">
                                                <i class="fas fa-lg fa-phone"></i>
                                                {{ $work->contact_tel}}
                                            </li>
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