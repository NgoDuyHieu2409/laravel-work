<?php $index = !empty($dataIndex) ? $dataIndex : 0; ?>

@if (empty($isAdd))
    @if ($user->workHistories->count())
        @foreach ($user->workHistories as $key => $item)
            <div class="card border user_work_history_index" data-index="{{ $key }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" class="form-control" name="workHistories[{{ $key }}][position]"
                                    value="{{ $item->position ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" name="workHistories[{{ $key }}][company]"
                                    value="{{ $item->company ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>From</label>
                                <input type="date" name="workHistories[{{ $key }}][from_date]"
                                    class="form-control" value="{{ $item->form_date ?? '' }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>To</label>
                                <input type="date" class="form-control" name="workHistories[{{ $key }}][to_date]"
                                    value="{{ $item->to_date ?? '' }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label></label>
                                <div class="icheck-primary mt-3">
                                    <input type="checkbox" id="current_job_{{$key}}" value="1"
                                        name="workHistories[{{$key}}][current_job]" @if ($item->current_job ?? false) checked @endif>
                                    <label for="current_job_{{$key}}">Current Job</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Descriptions</label>
                                <textarea name=" workHistories[{{$key}}][description]" id="description"
                                    class="form-control summernote_edit" cols="30" rows="10">
                                    {{ $item->descriptions ?? '' }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="card border user_work_history_index" data-index="{{ $index }}">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" class="form-control" name="workHistories[{{ $index }}][position]"
                            value="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="form-control" name="workHistories[{{ $index }}][company]"
                            value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>From</label>
                        <input type="date" name="workHistories[{{ $index }}][from_date]" class="form-control"
                            value="">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label>To</label>
                        <input type="date" class="form-control" name="workHistories[{{ $index }}][to_date]"
                            value="">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label></label>
                        <div class="icheck-primary mt-3">
                            <input type="checkbox" id="current_job_{{$index}}" value="1"
                                name="workHistories[{{ $index }}][current_job]">
                            <label for="current_job_{{$index}}">Current Job</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Descriptions</label>
                        <textarea name="workHistories[{{ $index }}][description]" id="description"
                            class="form-control summernote_edit" cols="30" rows="10">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
