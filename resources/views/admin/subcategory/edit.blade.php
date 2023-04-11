@extends('layouts.default')
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">Edit Sub Category</h3>
        </div>
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{session('error')}}
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
        <form class="form" enctype="multipart/form-data" method="POST" action="{{route('subcategory.update', $subcategory->id)}}">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="name"><b>{{__('Sub Category Name')}} <span class="text-danger">*</span></b></label>
                  <input name="name" id="name" value="{{old('name') ?? $subcategory->name}}" placeholder="Ex: " type="text" class="form-control  @error('name') is-invalid @enderror">
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the sub category name.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="category_id"><b>{{__('Parent Category')}} <span class="text-danger">*</span></b></label>
                  <select class="form-control select2" name="category_id" value="{{ old('category_id') ?? $subcategory->category_id }}">
                    <option value="">-- Select Parent Category --</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ (old('category_id') ?? $subcategory->category_id) == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
                    @endforeach
                  </select>
                  @error('category_id')
                  <span class="text-danger"><strong>{{ $message }}</strong></span>
                  @enderror
                  <span class="form-text text-muted">Select Parent Category.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="status"><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                  <select name="status" id="status" value="{{old('status') ?? $subcategory->status}}" class="custom-select  @error('status') is-invalid @enderror">
                    <option value="1" selected>Active</option>
                    <option value="0" {{ (old('status') ?? $subcategory->status) ? null : 'selected' }}>Inactive</option>
                  </select>
                  @error('status')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please select the status.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <div>
                    <label for="icon"><b>{{__('Icon')}}</b></label>
                  </div>
                  <div class="image-input image-input-outline">
                    <div class="image-input-wrapper" id="icon" style="background-image: url({{asset('storage/'.$subcategory->icon)}});background-size:cover;">
                    </div>

                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                        <i class="fa fa-pen icon-sm text-muted"></i>
                        <input type="file" class="@error('icon') is-invalid @enderror" onchange="readURL(this,'#icon')" name="icon" accept=".png, .jpg, .jpeg" />
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
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <div class="form-group">
                    <div>
                      <label for="cover_image" class=""><b>{{__('Cover Image')}}</b></label>
                    </div>

                    <div class="image-input image-input-outline">
                        <div class="image-input-wrapper" id="cover_image" style="background-image: url({{asset('storage/'.$subcategory->cover_image)}});background-size:cover;">
                        </div>

                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" class="@error('cover_image') is-invalid @enderror" onchange="readURL(this,'#cover_image')" name="cover_image" accept=".png, .jpg, .jpeg" />
                        </label>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar" onclick="ImageClear('#cover_image')">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>


                    {{-- <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                      <div id="cover_image" class="kt-avatar__holder_cover" style="background-image: url({{asset('storage/'.$subcategory->cover_image)}})"> </div>
                      <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                        <i class="fa fa-pen"></i>
                        <input type="file" class="@error('cover_image') is-invalid @enderror" onchange="readURL(this, '#cover_image')" name="cover_image" accept=".png, .jpg, .jpeg">
                      </label>
                      <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" onclick="ImageClear('#cover_image')" data-original-title="Cancel">
                        <i class="fa fa-times"></i>
                      </span>
                    </div> --}}
                    @error('cover_image')
                    <span style="display: block" class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>
                  </div>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('subcategory.index')}}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
    </div>
</div>

<script>
  function readURL(input, id) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
              $(id).css("background-image", "url("+ e.target.result + ")");

          };

          reader.readAsDataURL(input.files[0]);
      }
  }
  function ImageClear(id) {
      $(id).css("background-image", "url()");
  }
</script>
@endsection
