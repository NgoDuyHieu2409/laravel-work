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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
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
                                                    class="hidden input-checker-like-worker"
                                                    id="skill-{{ $worker->id }}" value="y">
                                            </span>

                                            <span class="js-btn-dislike-worker @if($old_like == 'n') kaigo-like @endif">
                                                <i class="cursor-pointer icon-thumbs-down2 animation"
                                                    data-animation="pulse" style="font-size:40px"></i>
                                                <input name="workers[{{ $worker->id }}][good_yn]" type="radio"
                                                    @if($old_like=='n' ) checked @endif
                                                    class="hidden input-checker-dislike-worker"
                                                    id="skill-{{ $worker->id }}" value="n">
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

                                            @foreach ($data['skills'] as $k => $skill_name)
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="skill_{{ $k . $worker->id }}" @if(in_array($k, $old_skill))
                                                    checked @endif name="workers[{{ $worker->id }}][skill][]"
                                                    value="{{ $k }}">

                                                <label class="custom-control-label"
                                                    for="skill_{{ $k . $worker->id }}">{{ $skill_name }}</label>
                                            </div>
                                            @endforeach
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
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
<link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
    <link rel="stylesheet" href="{{ asset('template/plugins/animation/animate.min.css') }}">
    <link href="{{ asset('global_assets/css/extras/animate.min.css') }}" rel="stylesheet" type="text/css">
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
@stop