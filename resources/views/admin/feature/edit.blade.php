@extends('layouts.default')
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">Edit Feature</h3>
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
        <form class="form" enctype="multipart/form-data" method="POST" action="{{route('feature.update', $feature->id)}}">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="name"><b>{{__('Feature Name')}} <span class="text-danger">*</span></b></label>
                <div class="col-lg-8">
                  <input name="name" id="name" value="{{old('name') ?? $feature->name}}" placeholder="Ex: " type="text" class="form-control  @error('name') is-invalid @enderror">
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the feature name.</span>
                </div>
              </div>


              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="priority"><b>{{__('Feature Priority')}}</b></label>
                <div class="col-lg-8">
                  <input name="priority" id="priority" min="0" value="{{old('priority') ?? $feature->priority}}" placeholder="Ex: 1" type="number" class="form-control  @error('priority') is-invalid @enderror">
                  @error('priority')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the Feature priority.</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="products_id"><b>{{__('Feature Products')}}</b></label>
                <div class="col-lg-8">
                  <select class="form-control select2" multiple name="products_id[]" value="{{ old('products_id') ?? $oldProducts }}">
                    <option value="">-- Select product Tag --</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ (old('products_id') ?? $oldProducts)->contains($product->id) ? 'selected' : null }}>{{ $product->name }}</option>
                    @endforeach
                  </select>
                  @error('products_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Select product Tags.</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 col-form-label" for="status"><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                <div class="col-lg-8">
                  <select name="status" id="status" value="{{old('status') ?? $feature->status}}" class="custom-select  @error('status') is-invalid @enderror">
                    <option value="1" selected>Active</option>
                    <option value="0" {{ (old('status') ?? $feature->status) ? null : 'selected' }}>Inactive</option>
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
                <label class="col-md-2 col-form-label" for="image" class=""><b>{{__('Cover Image')}}</b></label>
                <div class="col-lg-8">

                    <div class="image-input image-input-outline">
                        <div class="image-input-wrapper" id="image"
                            style="background-image: url({{asset('storage/'.$feature->image)}});background-size:cover;">
                        </div>

                        <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change" data-toggle="tooltip" title=""
                            data-original-title="Change avatar">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" class="@error('image') is-invalid @enderror"
                                onchange="readURL(this)" name="image"
                                accept=".png, .jpg, .jpeg" />
                            <input type="hidden" value="{{$feature->image}}" name="old_image" />
                        </label>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>

                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove" data-toggle="tooltip" title="Remove avatar"
                            onclick="ImageClear('#image')">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>


                  @error('image')
                  <span style="display: block" class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>

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
                    <a href="{{route('feature.index')}}" class="btn btn-secondary">Cancel</a>
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
                $('#image').css("background-image", "url("+ e.target.result + ")");

            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function ImageClear() {
        $('#image').css("background-image", "url()");

    }
</script>
@endsection
