@php($name = $name ?? 'image')
<div class="mb-3">
  <label for="{{ $name }}"><b>{{ __($label ?? 'Image') }}&nbsp;<span class="text-danger">*</span></b></label>
  <div>
    <div class="image-input image-input-outline">
      <div class="image-input-wrapper" id="{{ $name }}" style="background-image: url('{{ !($ignoreSetImage ?? true) ? getImageUrl($default ?? '') : ($default ?? '') }}'); background-size:cover;"></div>
      <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
        <i class="fa fa-pen icon-sm text-muted"></i>
        <input type="file" class="@error($name) is-invalid @enderror" onchange="readURL(this, '#{{ $name }}')" name="{{ $name }}" accept=".png, .jpg, .jpeg"/>
      </label>
      <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
         <i class="ki ki-bold-close icon-xs text-muted"></i>
        </span>
      <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#{{ $name }}')">
          <i class="ki ki-bold-close icon-xs text-muted"></i>
        </span>
    </div>
  </div>
  @error($name)
  <span class="text-danger"><strong>{{ $message }}</strong></span>
  @enderror
</div>

@push('script')
  <script>
    function readURL(input, id) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $(id).css("background-image", "url(" + e.target.result + ")");
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function ImageClear(id) {
      $(id).css("background-image", "url()");
    }
  </script>
@endpush
