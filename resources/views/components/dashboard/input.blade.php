<div class="form-group mb-3">
  <label for="{{ $name }}"><b>{{ __($label) }}@if($required ?? false)&nbsp;<span class="text-danger">*</span>@endif</b></label>
  <input name="{{ $name }}" id="{{ $name }}" value="{{ old($name, ($default ?? '')) }}" placeholder="{{ $placeholder ?? '' }}" type="{{ $type ?? 'text' }}" class="form-control  @error($name) is-invalid @enderror">
  @error($name)
  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
  @enderror
</div>
