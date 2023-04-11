@extends('layouts.default')
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">label</h3>
        </div>
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="card-body">
        <form class="form" enctype="multipart/form-data" method="POST" action="{{route('category.store')}}">
            @csrf
            <div class="kt-portlet__body">
              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="name"><b>{{__('Category Name')}} <span class="text-danger">*</span> </b></label>
                <div class="col-lg-8">
                  <input name="name" id="name" value="{{old('name')}}" placeholder="Ex: " type="text" class="form-control  @error('name') is-invalid @enderror">
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the category Name.</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="description"><b>{{__('Description')}}</b></label>
                <div class="col-lg-8">
                  <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Enter a category description..."
                    rows="4">{{old('description')}}</textarea>
                  @error('description')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter Category description within text length range 100 and 1000.</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="icon"><b>{{__('Icon')}}</b></label>
                <div class="col-lg-8">
                    <div class="image-input image-input-outline">
                        <div class="image-input-wrapper" id="icon" style="background-image: url({});">
                        </div>

                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" class="@error('icon') is-invalid @enderror" onchange="readURLIcon(this)" name="icon" accept=".png, .jpg, .jpeg" />
                        </label>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#icon')">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                  @error('icon')
                  <span style="display: block" class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>

                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="cover_img"><b>{{__('Cover Image')}}</b></label>
                <div class="col-lg-8">
                    <div class="image-input image-input-outline">
                        <div class="image-input-wrapper" id="cover_img" style="background-image: url();background-size:cover;">
                        </div>

                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" class="@error('cover_img') is-invalid @enderror" onchange="readURL(this)" name="cover_img" accept=".png, .jpg, .jpeg" />
                        </label>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#cover_img')">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                  @error('cover_img')
                  <span style="display: block" class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Maximum size 512kb.</span>


                </div>
              </div>

            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-6">
                    <!-- <button type="reset" class="btn btn-danger">Delete</button> -->
                  </div>
                  <div class="col-lg-6 kt-align-right">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{route('category.index')}}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
    </div>
</div>

<script>
  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cover_img').css("background-image", "url("+ e.target.result + ")");

            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLIcon(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#icon').css("background-image", "url("+ e.target.result + ")");

            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function ImageClear(id) {
        $(id).css("background-image", "url()");

    }
</script>
@endsection
