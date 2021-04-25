<?php $index = !empty($dataIndex) ? $dataIndex : 0; ?>

@if (empty($isAdd))
    @if ($user->languages->count())
        @foreach ($user->languages as $key => $language)
            <div class="card border user_language_index" data-index="{{ $key }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Language</label>
                                <select name="languages[{{ $key }}][language_id]" class="form-control select-search">
                                    <option value="">Please select...</option>
                                    @foreach (config('constant.language_skills') as $k => $item)
                                        <option value="{{$k}}" @if ($language->language_id == $k) selected @endif>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group clearfix">
                                <label>Proficiency</label>
                                <div style="display: block; padding: .375rem 0;">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="beginner-{{ $key }}"
                                            name="languages[{{ $key }}][proficiency]" value="0" @if ($language->proficiency == 0) checked @endif>
                                        <label for="beginner-{{ $key }}" class="text-yellow">Beginner</label>
                                    </div>

                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="radio" id="intermediate-{{ $key }}"
                                            name="languages[{{ $key }}][proficiency]" value="1" @if ($language->proficiency == 1) checked @endif>
                                        <label for="intermediate-{{ $key }}"
                                            class="text-orange">Intermediate</label>
                                    </div>

                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="radio" id="advanced-{{ $key }}"
                                            name="languages[{{ $key }}][proficiency]" value="2" @if ($language->proficiency == 2) checked @endif>
                                        <label for="advanced-{{ $key }}" class="text-red-500">Advanced</label>
                                    </div>

                                    <div class="icheck-primary d-inline ml-2">
                                        <input type="radio" id="native-{{ $key }}"
                                            name="languages[{{ $key }}][proficiency]" value="3" @if ($language->proficiency == 3) checked @endif>
                                        <label for="native-{{ $key }}" class="text-success">Native</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="card border user_language_index" data-index="{{ $index }}">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Language</label>
                        <select name="languages[{{ $index }}][language_id]" class="form-control select-search">
                            <option value="">Please select...</option>
                            @foreach (config('constant.language_skills') as $k => $item)
                                <option value="{{$k}}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group clearfix">
                        <label>Proficiency</label>
                        <div style="display: block; padding: .375rem 0;">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="beginner-{{ $index }}"
                                    name="languages[{{ $index }}][proficiency]" value="0" checked>
                                <label for="beginner-{{ $index }}" class="text-yellow">Beginner</label>
                            </div>

                            <div class="icheck-primary d-inline ml-2">
                                <input type="radio" id="intermediate-{{ $index }}"
                                    name="languages[{{ $index }}][proficiency]" value="1">
                                <label for="intermediate-{{ $index }}" class="text-orange">Intermediate</label>
                            </div>

                            <div class="icheck-primary d-inline ml-2">
                                <input type="radio" id="advanced-{{ $index }}"
                                    name="languages[{{ $index }}][proficiency]" value="2">
                                <label for="advanced-{{ $index }}" class="text-red-500">Advanced</label>
                            </div>

                            <div class="icheck-primary d-inline ml-2">
                                <input type="radio" id="native-{{ $index }}"
                                    name="languages[{{ $index }}][proficiency]" value="3">
                                <label for="native-{{ $index }}" class="text-success">Native</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
