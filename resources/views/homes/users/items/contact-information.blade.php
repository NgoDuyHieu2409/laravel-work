<div class="row">
    <div class="col-12">
        <h4><label>{{ $user->name }}</label></h4>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Email<span class="text-red ml-2">*</span></label>
            <input type="text" disabled class="form-control" name="email" value="{{ $user->email }}">
            <span class="text-danger email-error error-message"></span>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label>Phone number<span class="text-red ml-2">*</span></label>
            <input type="text" class="form-control phone-number" name="phone" value="{{ $user->contact->phone ?? '' }}">
            <span class="text-danger phone-error error-message"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Birthday<span class="text-red ml-2">*</span></label>
            <input type="date" name="birthday" class="form-control" value="{{ $user->contact->birthday ?? '' }}">
            <span class="text-danger birthday-error error-message"></span>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label>Gender</label>
            <div style="display: block; padding: .375rem 0;">
                <div class="icheck-primary d-inline">
                    <input type="radio" id="sex-0" name="sex" value="0" @if (($user->contact->sex ?? 0) == 0)
                    checked @endif>
                    <label for="sex-0">Nam</label>
                </div>

                <div class="icheck-primary d-inline ml-3">
                    <input type="radio" id="sex-1" name="sex" value="1" @if (($user->contact->sex ?? 0) == 1)
                    checked @endif>
                    <label for="sex-1">Nữ</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Job Title<span class="text-red ml-2">*</span></label>
            <input type="text" class="form-control" value="{{ $user->contact->job_ttle ?? '' }}" name="job_ttle">
            <span class="text-danger job_ttle-error error-message"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Province/City<span class="text-red ml-2">*</span></label>
            <select id="city" name="city" class="form-control select-search city-js">
                <option value="">Vui lòng chọn tỉnh/ thành phố</option>
                @foreach($city as $key => $value)
                <option value="{{$key}}" @if(old('city', $user->contact->city ?? '') == $key) selected
                    @endif>{{$value}}</option>
                @endforeach
            </select>
            <span class="text-danger city-error error-message"></span>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label>District<span class="text-red ml-2">*</span></label>
            <select id="pref" name="district" class="form-control select-search pref-js">
                <option value="">Vui lòng chọn quận huyện</option>
            </select>
            <span class="text-danger district-error error-message"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Address<span class="text-red ml-2">*</span></label>
            <input type="text" name="address" class="form-control" value="{{ $user->contact->birthday ?? '' }}">
            <span class="text-danger address-error error-message"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Summary</label>
            <textarea name="summary" id="summary" class="form-control summernote_edit" cols="30" rows="10">
        {{ $user->contact->summary ?? '' }}
    </textarea>
        </div>
    </div>
</div>