<div class="form-group">
  <label for="{{ $name }}"><b>{{ __($label ?? 'Status') }}@if($required ?? false)&nbsp;<span class="text-danger">*</span>@endif</b></label>
  <select name="{{ $name }}" id="{{ $name }}" class="custom-select  @error($name) is-invalid @enderror">
    <option value="1" selected>Active</option>
    <option value="0" {{ old($name, ($default ?? '')) == '0' ? 'selected' : '' }}>Inactive</option>
  </select>
  @error($name)
  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
  @enderror
</div>
