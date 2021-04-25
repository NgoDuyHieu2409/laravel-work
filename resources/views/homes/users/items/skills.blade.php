




<?php $index = !empty($dataIndex) ? $dataIndex : 0; ?>

@if (empty($isAdd))
    @if ($user->skills->count())
        @foreach ($user->skills as $key => $skill)
            <div class="card border user_skill_index" data-index="{{ $key }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="skills[{{ $key }}][name]"
                                    placeholder="Skill name" value="{{ $skill->name ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" name="skills[{{ $key }}][description]"
                                    placeholder="Skill description" value="{{ $skill->description ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="card border user_skill_index" data-index="{{ $index }}">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" name="skills[{{ $index }}][name]"
                            placeholder="Skill name" value="">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <input type="text" class="form-control" name="skills[{{ $index }}][description]"
                            placeholder="Skill description" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
