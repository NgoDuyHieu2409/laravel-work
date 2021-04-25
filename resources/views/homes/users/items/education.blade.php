<?php $index = !empty($dataIndex) ? $dataIndex : 0; ?>

@if (empty($isAdd))
    @if ($user->educations->count())
        @foreach ($user->educations as $key => $education)
            <div class="card border user_education_index" data-index="{{ $key }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" class="form-control" name="educations[{{ $key }}][subject]"
                                    value="{{ $education->subject ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>School</label>
                                <input type="text" class="form-control" name="educations[{{ $key }}][school]"
                                    value="{{ $education->school ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Qualifications</label>
                                <input type="text" class="form-control" name="educations[{{ $key }}][qualification]"
                                    value="{{ $education->qualification ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>From</label>
                                <input type="date" name="educations[{{ $key }}][from_date]"
                                    class="form-control" value="{{ $education->form_date ?? '' }}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>To</label>
                                <input type="date" class="form-control" name="educations[{{ $key }}][to_date]"
                                    value="{{ $education->to_date ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Descriptions</label>
                                <textarea name="educations[{{ $key }}][descriptions]" id="descriptions"
                                    class="form-control summernote_edit" cols="30" rows="10">
                                    {{ $education->descriptions ?? '' }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="card border user_education_index" data-index="{{ $index }}">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control" name="educations[{{ $index }}][position]"
                            value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>School</label>
                        <input type="text" class="form-control" name="educations[{{ $index }}][school]"
                            value="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Qualifications</label>
                        <input type="text" class="form-control" name="educations[{{ $index }}][qualification]"
                            value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>From</label>
                        <input type="date" name="educations[{{ $index }}][from_date]" class="form-control"
                            value="">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>To</label>
                        <input type="date" class="form-control" name="educations[{{ $index }}][to_date]"
                            value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Descriptions</label>
                        <textarea name="educations[{{ $index }}][descriptions]" id="descriptions"
                            class="form-control summernote_edit" cols="30" rows="10">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
