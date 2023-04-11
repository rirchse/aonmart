<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-8">
    <div>
      <label for="image"><b>{{__('Profile Image')}}</b></label>
    </div>
    <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
      <div id="image" class="kt-avatar__holder_icon"></div>
      <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
        <i class="fa fa-pen"></i>
        <input type="file" value="{{old('image')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="image" accept=".png, .jpg, .jpeg">
      </label>
      <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" onclick="ImageClear('#image')" data-original-title="Cancel">
        <i class="fa fa-times"></i>
      </span>
    </div>
    @error('image')
    <span style="display: block" class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resolution 940px X 720px (H x W). <br> Maximum size 512kb.</span>
  </div>
</div>

{{--<div class="form-group row">--}}
{{--  <div class="col-lg-2"></div>--}}
{{--  <div class="col-lg-8">--}}
{{--    <div>--}}
{{--      <label for="cover_image"><b>{{__('Cover Image')}}</b></label>--}}
{{--    </div>--}}
{{--    <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">--}}
{{--      <div id="cover_image" class="kt-avatar__holder_cover" style="background-image: url()"></div>--}}
{{--      <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">--}}
{{--        <i class="fa fa-pen"></i>--}}
{{--        <input type="file" value="{{old('cover_image')}}" class="@error('cover_image') is-invalid @enderror" onchange="readURLCover(this)" name="cover_image"--}}
{{--               accept=".png, .jpg, .jpeg">--}}
{{--      </label>--}}
{{--      <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" onclick="ImageClear('#cover_image')" data-original-title="Cancel">--}}
{{--        <i class="fa fa-times"></i>--}}
{{--      </span>--}}
{{--    </div>--}}
{{--    @error('cover_image')--}}
{{--    <span style="display: block" class="invalid-feedback" role="alert">--}}
{{--      <strong>{{ $message }}</strong>--}}
{{--    </span>--}}
{{--    @enderror--}}
{{--    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Maximum size 512kb.</span>--}}
{{--  </div>--}}
{{--</div>--}}

<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-8">
    <label for="name">{{ __('Name') }}&nbsp;<span class="text-danger">*</span>
    </label>
    <input name="name" id="name" value="{{ old('name') }}" placeholder="Customer Name" type="text" class="form-control  @error('name') is-invalid @enderror">
    @error('name')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-8">
    <label for="email">{{ __('Email') }}</label>
    <input name="email" id="email" value="{{ old('email') }}" placeholder="Ex: example@example.com" type="email" class="form-control  @error('email') is-invalid @enderror">
    @error('email')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-4">
    <label for="password">{{ __('Password') }}&nbsp;<span class="text-danger">*</span>
    </label>
    <input name="password" id="password" placeholder="" type="password" class="form-control  @error('password') is-invalid @enderror">
    @error('password')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="col-lg-4">
    <label for="password_confirmation">{{ __('Confirm Password') }}&nbsp;<span class="text-danger">*</span>
    </label>
    <input name="password_confirmation" id="password_confirmation" placeholder="" type="password" class="form-control  @error('password_confirmation') is-invalid @enderror">
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-8">
    <label for="mobile">{{ __('Mobile No.') }}&nbsp;<span class="text-danger">*</span>
    </label>
    <input name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="Ex: 01xxxxxxxxxx" type="tel" class="form-control  @error('mobile') is-invalid @enderror">
    @error('mobile')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-8">
    <label for="about">{{ __('About') }}</label>
    <textarea class="form-control @error('about') is-invalid @enderror" id="about" name="about" placeholder="Bio" minlength="100" maxlength="1000" rows="5">{{old('about')}}</textarea>
    @error('about')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-2"></div>
  <div class="col-lg-8">
    <label for="status">{{ __('Status') }}&nbsp;<span class="text-danger">*</span>
    </label>
    <select name="status" id="status" class="custom-select  @error('status') is-invalid @enderror">
      <option value="1" selected>Active</option>
      <option value="0" {{ old('status') == '0' ? 'selected' : null }}>Inactive</option>
    </select>
    @error('status')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

@push('scripts')
  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#image').css("background-image", "url(" + e.target.result + ")");

        };

        reader.readAsDataURL(input.files[0]);
      }
    }

    function readURLCover(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#cover_image').css("background-image", "url(" + e.target.result + ")");

        };

        reader.readAsDataURL(input.files[0]);
      }
    }

    function ImageClear(id) {
      $(id).css("background-image", "url()");

    }
  </script>
@endpush
