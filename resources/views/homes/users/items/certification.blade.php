<?php $index = !empty($dataIndex) ? $dataIndex : 0; ?>

@if (empty($isAdd))
    @if ($user->certifications->count() > 0)
        @foreach ($user->certifications as $key => $item)
            <div class="card border user_certification_index" data-index="{{ $key }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Certification Name</label>
                                <input type="text" class="form-control" name="certifications[{{ $key }}][name]"
                                    value="{{ $item->name ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Institution</label>
                                <input type="text" class="form-control" name="certifications[{{ $key }}][institution]"
                                    value="{{ $item->institution ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="certifications[{{ $key }}][date]"
                                    value="{{ $item->date ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Certification Link</label>
                                <input type="text" class="form-control" name="certifications[{{ $key }}][link]"
                                    value="{{ $item->link ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="certifications[{{ $key }}][description]" id="description"
                                    class="form-control summernote_edit" cols="30" rows="10">
                                    {{ $item->description ?? '' }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="card border user_certification_index" data-index="{{ $index }}">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Certification Name</label>
                        <input type="text" class="form-control" name="certifications[{{ $index }}][name]"
                            value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Institution</label>
                        <input type="text" class="form-control" name="certifications[{{ $index }}][institution]"
                            value="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="certifications[{{ $index }}][date]"
                            value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Certification Link</label>
                        <input type="text" class="form-control" name="certifications[{{ $index }}][link]"
                            value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Descriptions</label>
                        <textarea name="certifications[{{ $index }}][descriptions]" id="descriptions"
                            class="form-control summernote_edit" cols="30" rows="10">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
