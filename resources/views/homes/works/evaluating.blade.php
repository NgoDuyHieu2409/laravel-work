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

        <div class="row pt-3">
            <div class="col-md-9 col-sm-12">
                <div class="row mb-2">
                    <div class="col-sm-12" style="font-size: 15px;">
                        @if($request->isReview)
                        <a href="{{ route('work.evaluating_work', ['isReview' => 0]) }}" class="text-warning">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;
                            Danh sách chưa đánh giá
                        </a>
                        @else
                        <a href="{{ route('work.evaluating_work', ['isReview' => 1]) }}" class="text-success">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;
                            Danh sách đã đánh giá
                        </a>
                        @endif
                    </div>
                </div>
                <div class="max-w-7xl mx-auto">
                    @if($works->count())
                    @csrf
                    <div class="overflow-hidden sm:rounded-lg list-work-review">
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
                                        </div>
                                        <hr class="hr-custom mt-0">
                                        @auth
                                        <div id="accordion">
                                            <div class="text-right">
                                                <span id="btn-show-advanced-search" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne" class="mr-3"
                                                    data-target="#modify-request-{{$work->id}}" style="cursor: pointer;">
                                                    <a href="javascript:;" class="text-danger"><i class="fas fa-user-edit"></i>&nbsp;Request</a>
                                                </span>
                                                <span id="btn-show-advanced-search" data-toggle="collapse" aria-expanded="false" aria-controls="collapseTwo"
                                                    data-target="#home-review-{{$work->id}}" style="cursor: pointer;">
                                                    <a href="javascript:;"><i class="far fa-calendar-check"></i>&nbsp;Comment</a>
                                                </span>
                                            </div>

                                            <div id="home-review-{{$work->id}}" aria-labelledby="headingTwo" data-parent="#accordion"
                                                class="collapse mt-2 @if($request->isReview)show @endif">
                                                <div class="row">
                                                    <div class="col-sm-12 review_content">
                                                        <table class="table table-hover border-bottom">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Giờ làm việc của bạn có đáp ứng lịch trình của bạn không?</td>
                                                                    <td>
                                                                        <div class="form-group clearfix mb-0">
                                                                            <div class="icheck-primary d-inline mr-2">
                                                                                <input type="radio" id="good_yn1-1-{{$work->id}}" name="good_yn1"
                                                                                    value="y" @if(!$work->home_review || $work->home_review->good_yn1 == 'y') checked @endif>
                                                                                <label for="good_yn1-1-{{$work->id}}">Có</label>
                                                                            </div>
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="radio" id="good_yn1-2-{{$work->id}}" name="good_yn1"
                                                                                    value="n" @if($work->home_review && $work->home_review->good_yn1 == 'n') checked @endif>
                                                                                <label for="good_yn1-2-{{$work->id}}">Không</label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Bạn có làm theo mô tả doanh nghiệp được đăng không?
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group clearfix mb-0">
                                                                            <div class="icheck-primary d-inline mr-2">
                                                                                <input type="radio" id="good_yn2-1-{{$work->id}}" name="good_yn2"
                                                                                value="y" @if(!$work->home_review || $work->home_review->good_yn2 == 'y') checked @endif>
                                                                                <label for="good_yn2-1-{{$work->id}}">Có</label>
                                                                            </div>
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="radio" id="good_yn2-2-{{$work->id}}" name="good_yn2"
                                                                                    value="n" @if($work->home_review && $work->home_review->good_yn2 == 'n') checked @endif>
                                                                                <label for="good_yn2-2-{{$work->id}}">Không</label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Bạn có muốn làm việc ở đây một lần nữa không?</td>
                                                                    <td>
                                                                        <div class="form-group clearfix mb-0">
                                                                            <div class="icheck-primary d-inline mr-2">
                                                                                <input type="radio" id="good_yn3-1-{{$work->id}}" name="good_yn3"
                                                                                    value="y" @if(!$work->home_review || $work->home_review->good_yn3 == 'y') checked @endif>
                                                                                <label for="good_yn3-1-{{$work->id}}">Có</label>
                                                                            </div>
                                                                            <div class="icheck-primary d-inline">
                                                                                <input type="radio" id="good_yn3-2-{{$work->id}}" name="good_yn3"
                                                                                    value="n" @if($work->home_review && $work->home_review->good_yn3 == 'n') checked @endif>
                                                                                <label for="good_yn3-2-{{$work->id}}">Không</label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <textarea name="comment" rows="2" style="width: 100%;"
                                                            placeholder="Bạn có ý kiến gì khác không?">{{ $work->home_review->comment ?? ''}}</textarea>
                                                    </div>
                                                    @if(!$request->isReview)
                                                    <div class="col-sm-12 text-right">
                                                        <button type="button" data-id="{{$work->id}}"
                                                            class="btn btn-sm btn-success btn-js-review-work">Gửi đánh giá</button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div id="modify-request-{{$work->id}}" aria-labelledby="headingOne" data-parent="#accordion" class="collapse mt-2 js-custom-requests">
                                                <div class="row">
                                                    <div class="col-sm-12 worker_modify_request">
                                                        <span class="text-yellow-500" style="font-size: 13px;">
                                                            <i>Thời gian bắt đầu, Thời gian kết thúc và Thời gian nghỉ: là thời gian thực tế bạn thực hiện công việc cua mình.</i>
                                                        </span>
                                                    </div>

                                                    <div class="col-sm-6 mb-2">
                                                        <label>Thời gian bắt đầu</label>
                                                        <input class="form-control js-work-time-start" type="datetime-local" name="modify_worktime_start_at"
                                                            @if($work->modify_request) disabled @endif
                                                            value="{{ $work->modify_request ?
                                                                \Carbon\Carbon::create($work->modify_request->modify_worktime_start_at)->format('Y-m-d\TH:i') : ''}}">
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label>Thời gian kết thúc</label>
                                                        <input class="form-control" type="datetime-local" name="modify_worktime_end_at"
                                                            @if($work->modify_request) disabled @endif
                                                            value="{{ $work->modify_request ?
                                                                \Carbon\Carbon::create($work->modify_request->modify_worktime_end_at)->format('Y-m-d\TH:i') : ''}}">
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label>Thời gian nghỉ</label>
                                                        <input class="form-control" type="number" min="0" name="resttime_minutes"
                                                            @if($work->modify_request) disabled @endif
                                                            value="{{ $work->modify_request->resttime_minutes ?? ''}}"
                                                            placeholder="Thời gian tính bằng phút.">
                                                    </div>

                                                    <div class="col-sm-12 worker_modify_request">
                                                        <textarea name="comment" rows="3" style="width: 100%;" @if($work->modify_request) disabled @endif
                                                            placeholder="Bạn cần request vấn đề gì không?">{{ $work->modify_request->comment ?? ''}}</textarea>
                                                    </div>

                                                    @if(!$work->modify_request)
                                                    <div class="col-sm-12 text-right">
                                                        <button type="button" data-id="{{$work->id}}"
                                                            class="btn btn-sm btn-success btn-js-modify-request">Gửi yêu cầu</button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
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
                        @if($request->isReview)
                        Bạn chưa đánh giá công việc nào.
                        @else
                        Bạn không có công việc nào cần đánh giá.
                        @endif
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
        const HOME_IS_REVIEW = @JSON($request->isReview ?? 0);
    </script>

    <script src="{{ asset('js/work_review.js') }}"></script>
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
