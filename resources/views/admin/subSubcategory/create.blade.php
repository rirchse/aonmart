@extends('layouts.default')
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <!--begin::Portlet-->
        <div class="kt-portlet">
          @if(session()->has('success'))
            <div class="alert alert-success">
              {{session('success')}}
            </div>
          @elseif(session()->has('error'))
            <div class="alert alert-danger">
              {{session('error')}}
            </div>
          @endif
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
              <span class="kt-widget__icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                     class="kt-svg-icon">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24"/>
                    <rect fill="#000000" x="2" y="5" width="19" height="4" rx="1"/>
                    <rect fill="#000000" opacity="0.3" x="2" y="11" width="19" height="10" rx="1"/>
                  </g>
                </svg>
              </span>
            </span>
              <h3 class="kt-portlet__head-title">
                Add New Sub Category
              </h3>
            </div>
          </div>
          <!--begin::Form-->

          <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{route('subSubcategory.store')}}">
            @csrf
            <div class="kt-portlet__body">

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="category_id"><b>{{__('Parent Category')}} <span class="text-danger">*</span></b></label>
                  <select class="form-control select2" id="category_id" name="category_id" value="{{ old('category_id') }}" onchange="subcategoryFunction()">
                    <option value="">-- Select Parent Category --</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
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
                  <label for="subcategory_id"><b>{{__('Parent Subcategory')}} <span class="text-danger">*</span></b></label>
                  <select class="form-control select2" id="subcategory_id" name="subcategory_id" value="{{ old('subcategory_id') }}" disabled>
                  </select>
                  @error('subcategory_id')
                  <span class="text-danger"><strong>{{ $message }}</strong></span>
                  @enderror
                  <span class="form-text text-muted">Select Parent Subcategory.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="name" class=""><b>{{__('Sub Subcategory Name')}} <span class="text-danger">*</span></b></label>
                  <input name="name" id="name" value="{{old('name')}}" placeholder="Ex: " type="text"
                         class="form-control  @error('name') is-invalid @enderror">
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the Sub Subcategory name.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="status" class=""><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                  <select name="status" id="status" class="custom-select  @error('status') is-invalid @enderror">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
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
                    <div class="image-input-wrapper" id="icon" style="background-image: url()">
                    </div>

                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" d>
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
                  <span class="text-danger"><strong>{{ $message }}</strong></span>
                  @enderror
                  <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <div>
                    <label for="cover_image" class=""><b>{{__('Cover Image')}}</b></label>
                  </div>
                  <div class="image-input image-input-outline">
                    <div class="image-input-wrapper" id="cover_image" style="background-image: url()">
                    </div>

                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" d>
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
                  @error('cover_image')
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
                    <a href="{{route('language.index')}}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <!--end::Form-->
        </div>
        <!--end::Portlet-->
      </div>
    </div>
  </div>
  <script>
    //sub category
    let subcategoryFunction = function(){
      let category_id = $('#category_id').select2("val");
      console.log(category_id);
      $.ajax({
        method:"post",
        url:"{{ route('load.subcategory') }}",
        data:{category_id:category_id,"_token":"{{ csrf_token() }}"},
        dataType:"html",
        success:function(response){
          $("#subcategory_id").attr("disabled", false);
          $("#subcategory_id").html(response);
        },
        error:function(err){
          console.log(err);
        }
      });
    };

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
@endsection
