@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Edit Store </h3>
      </div>
    </div>

    <div class="card-body">
      <form enctype="multipart/form-data" method="POST" action="{{ route('store.update', $store->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <div>
              <label for=image"><b>{{ __('Image') }}</b></label>
            </div>
            <div class="image-input image-input-empty image-input-outline" id="image" style="background-image: url({{ getImageUrl() }});background-size:cover;">
              <div class="image-input-wrapper"></div>
              <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                <i class="fa fa-pen icon-sm text-muted"></i>
                <input type="file" value="{{ old('image', $store->image) }}" name="image" onchange="readURLIcon(this)" accept=".png, .jpg, .jpeg"/>
                  <input type="hidden" name="image_remove"/>
              </label>

              <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                <i class="ki ki-bold-close icon-xs text-muted"></i>
              </span>
              <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                <i class="ki ki-bold-close icon-xs text-muted"></i>
              </span>
            </div>

            @error('icon')
            <span style="display: block" class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
            <span class="form-text text-muted">
              Allowed file types: png, jpg, jpeg. <br>
              Standard Resolution 940px X 720px (H x W). <br>
              Maximum size 512kb.
            </span>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="name"><b>{{ __('Store Name') }}&nbsp;<span class="text-danger">*</span></b></label>
            <input name="name" id="name" value="{{ old('name', $store->name) }}" placeholder="Ex: Store Name" type="text" class="form-control  @error('name') is-invalid @enderror">
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="categories"><b>{{__('Product Categories')}} <span class="text-danger">*</span></b></label>
            <select name="categories[]" id='categories' class="form-control select2" multiple>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ collect(old('categories', $store->categories->pluck('id')))->contains($category->id) ? 'selected' : null }}>{{ $category->name }}</option>
              @endforeach
            </select>
            @error('categories')
            <span class="text-danger"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="shipping_fee"><b>{{ __('Shipping fee') }}&nbsp;<span class="text-danger">*</span></b></label>
            <input name="shipping_fee" id="shipping_fee" value="{{ old('shipping_fee', $store->shipping_fee) }}" placeholder="Ex: Store Name" type="text" class="form-control  @error('shipping_fee') is-invalid @enderror">
            @error('shipping_fee')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="address"><b>{{ __('Address') }}&nbsp;<span class="text-danger">*</span></b></label>
            <textarea name="address" id="address" rows="3" placeholder="Ex: Store Address" class="form-control  @error('address') is-invalid @enderror">{{ old('address', $store->address) }}</textarea>
            @error('address')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="capital"><b>{{ __('Capital') }}</label>
            <input name="capital" id="capital" value="{{ old('capital', $store->capital) }}" placeholder="Ex: Store Name" type="number" class="form-control  @error('capital') is-invalid @enderror">
            @error('capital')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="capital_note"><b>{{ __('Capital Note') }}</label>
            <textarea name="capital_note" id="capital_note" rows="3" placeholder="Ex: Store capital_note" class="form-control  @error('capital_note') is-invalid @enderror">{{ old('capital_note', $store->capital_note) }}</textarea>
            @error('capital_note')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="status"><b>{{ __('Status') }}&nbsp;<span class="text-danger">*</span></b></label>
            <select name="status" id="status" class="custom-select  @error('status') is-invalid @enderror">
              <option value="1" selected>Active</option>
              <option value="0" {{ old('status', $store->status) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('store.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('script')
  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
          $('#cover_img').css("background-image", "url(" + e.target.result + ")");

        };

        reader.readAsDataURL(input.files[0]);
      }
    }

    function readURLIcon(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
          $('#icon').css("background-image", "url(" + e.target.result + ")");

        };

        reader.readAsDataURL(input.files[0]);
      }
    }

    function ImageClear(id) {
      $(id).css("background-image", "url()");
    }

  </script>

  <!--begin::Page Scripts(used by this page)-->
  <script src="{{ asset('assets/js/pages/crud/file-upload/image-input.js') }}"></script>
  <!--end::Page Scripts-->
@endpush
