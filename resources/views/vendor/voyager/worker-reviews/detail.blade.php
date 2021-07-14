@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('content')
<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('voyager.worker-reviews.store') }}" method="POST">
                @csrf
                @method('post')

                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Danh sách chưa được đánh giá</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <colgroup>
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="30%">
                                <col width="30%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Target</th>
                                    <th class="text-center">Good/Bad</th>
                                    <th class="text-center">Comment</th>
                                    <th class="text-center">Skill</th>
                                    <th class="text-center">Add to favorites</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="work_id" value="{{ $work_id ?? old('work_id') ?? '' }}">

                                @php
                                $old_workers = old('workers');
                                @endphp

                                @foreach ($workers as $worker)
                                <tr>
                                    <td>
                                        @php
                                        $url = isset($worker->profile_photo_path) ?
                                        Storage::url($worker->profile_photo_path) : Voyager::image($worker->avatar);
                                        @endphp
                                        <img src="{{ $url }}" class="rounded-circle" width="40" height="40" alt="">
                                    </td>
                                    <td>
                                        <p>{{ $worker->name}}</p>
                                        <p>{{ $worker->year_old }} Tuổi/ {{ $worker->sex ? 'Nam' : 'nữ' }}</p>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $old_like = $old_workers[$worker->id]['good_yn'] ?? '';
                                        @endphp

                                        <span class="js-btn-like-worker @if($old_like == 'y') kaigo-like @endif">
                                            <i class="mr-2 cursor-pointer icon-thumbs-up2 animation"
                                                data-animation="pulse" style="font-size:40px"></i>
                                            <input name="workers[{{ $worker->id }}][good_yn]" type="radio"
                                                @if($old_like=='y' ) checked @endif
                                                class="hidden input-checker-like-worker" id="skill-{{ $worker->id }}"
                                                value="y">
                                        </span>

                                        <span class="js-btn-dislike-worker @if($old_like == 'n') kaigo-like @endif">
                                            <i class="cursor-pointer icon-thumbs-down2 animation" data-animation="pulse"
                                                style="font-size:40px"></i>
                                            <input name="workers[{{ $worker->id }}][good_yn]" type="radio"
                                                @if($old_like=='n' ) checked @endif
                                                class="hidden input-checker-dislike-worker" id="skill-{{ $worker->id }}"
                                                value="n">
                                        </span>
                                    </td>

                                    <td>
                                        <textarea rows="3" class="form-control" placeholder=""
                                            name="workers[{{ $worker->id }}][comment]">{{ $old_workers[$worker->id]['comment'] ?? '' }}</textarea>
                                    </td>

                                    <td>
                                        @php
                                        $old_skill = $old_workers[$worker->id]['skill'] ?? [];
                                        @endphp
                                        <ul>
                                        @foreach ($data['skills'] as $k => $skill_name)
                                            <li>
                                                {{ $skill_name }}
                                                <input name="workers[{{ $worker->id }}][skill][{{$k}}]"
                                                    class="star_rating_skill rating-loading" data-min="0"
                                                    data-max="5" data-step="1" data-size="xs" value="{{old($old_skill[$k])}}">
                                            </li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @if (!$worker->favorite)
                                        <button type="button"
                                            class="btn btn-success js-btn-favorite btn-ladda btn-ladda-spinner"
                                            data-spinner-color="darkblue" data-style="zoom-out"
                                            data-id="{{ $worker->id }}">
                                            <i class="fas fa-share-square"></i> Add favorite
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer pr-3 pb-3" style="border-top: 1px solid #e4e4e4;">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('template/plugins/animation/animate.min.css') }}">
<link href="{{ asset('global_assets/css/extras/animate.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/star-rating.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/star-theme.css') }}" rel="stylesheet" type="text/css">
<style>
.kaigo-like {
    color: #0478d5;
}
</style>
@stop

@section('javascript')
<!-- DataTables -->
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
<script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
@endif
<script src="{{ asset('js/review/review_detail.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/buttons/spin.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/buttons/ladda.min.js') }}"></script>
<script src="{{ asset('js/star-rating.js') }}"></script>

<script>
$(function() {
    $(".star_rating_skill").rating();
});
</script>
@stop