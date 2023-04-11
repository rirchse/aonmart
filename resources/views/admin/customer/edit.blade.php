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
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <rect fill="#000000" x="2" y="5" width="19" height="4" rx="1" />
                    <rect fill="#000000" opacity="0.3" x="2" y="11" width="19" height="10" rx="1" />
                  </g>
                </svg>
              </span>
            </span>
            <h3 class="kt-portlet__head-title">
              Edit User
            </h3>
          </div>
        </div>
        <!--begin::Form-->

        <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{route('supplier.update', $user->id)}}">
          @csrf
          @method('PUT')

          <div class="kt-portlet__body">
            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="name" class=""><b>{{__('User Name')}} <span class="text-danger">*</span></b></label>
                <input name="name" id="name" value="{{old('name') ?? $user->name}}" placeholder="Ex: Smith Jones" type="text" class="form-control  @error('name') is-invalid @enderror">
                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter the user name.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="email" class=""><b>{{__('User Email')}} <span class="text-danger">*</span></b></label>
                <input name="email" id="email" value="{{old('email') ?? $user->email}}" placeholder="Ex: example@example.com" type="email" class="form-control  @error('email') is-invalid @enderror">
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter the user email.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-4">
                <label for="password" class=""><b>{{__('Password')}} <span class="text-danger">*</span></b></label>
                <input name="password" id="password" placeholder="Ex: " type="password" class="form-control  @error('password') is-invalid @enderror">
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter the Password.</span>
              </div>
              <div class="col-lg-4">
                <label for="password_confirmation" class=""><b>{{__('Confirm Password')}} <span class="text-danger">*</span></b></label>
                <input name="password_confirmation" id="password_confirmation" placeholder="Ex: " type="password" class="form-control  @error('password_confirmation') is-invalid @enderror">
                <span class="form-text text-muted">Please enter the confirmation password.</span>
              </div>
            </div>


            <div class="form-group row">
              <div class="offset-lg-2 col-lg-8">
                <label for="product_id"><b>{{__('Product From Supplier')}} <span class="text-danger">*</span></b></label>
                <select class="form-control select2" multiple id="product_id" name="product_id[]">
                  @foreach ($products as $product)
                  <option value="{{ $product->id }}" {{ collect(old("product_id", $oldProducts))->contains($product->id) ? "selected": null }}>{{ $product->name }}</option>
                  @endforeach
                </select>
                @error('product_id')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
                <span class="form-text text-muted">Select products from supplier.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="mobile" class=""><b>{{__('User Mobile Number')}}</b></label>
                <input name="mobile" id="mobile" value="{{old('mobile') ?? $user->mobile}}" placeholder="Ex: 01xxxxxxxxxx" type="tel" class="form-control  @error('mobile') is-invalid @enderror">
                @error('mobile')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter the mobile number.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="phone" class=""><b>{{__('User Phone Number')}}</b></label>
                <input name="phone" id="phone" value="{{old('phone') ?? $user->phone}}" placeholder="Ex: 01xxxxxx" type="tel" class="form-control  @error('phone') is-invalid @enderror">
                @error('phone')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter the phone number.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="about" class=""><b>{{__('About')}}</b></label>
                <textarea class="form-control @error('about') is-invalid @enderror" name="about" placeholder="Enter User About..." minlength="100" maxlength="1000"
                  rows="5">{{old('about') ?? $user->about}}</textarea>
                @error('about')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter user about within text length range 100 and 1000.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="status" class=""><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                <select name="status" id="status" value="{{old('status') ?? $user->status}}" class="custom-select  @error('status') is-invalid @enderror">
                  <option value="1" selected>Active</option>
                  <option value="0" {{ (old('status') ?? $user->status) ? null : 'selected' }}>Inactive</option>
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
                  <label for="image" class=""><b>{{__('User Image')}}</b></label>
                </div>
                <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                  <div id="image" class="kt-avatar__holder_icon" style="background-image: url({{asset('storage/'.$user->image)}})"> </div>
                  <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                    <i class="fa fa-pen"></i>
                    <input type="file" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="image" accept=".png, .jpg, .jpeg">
                  </label>
                  <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" onclick="ImageClear()" data-original-title="Cancel">
                    <i class="fa fa-times"></i>
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
            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <div>
                  <label for="cover_image" class=""><b>{{__('Cover Image')}}</b></label>
                </div>
                <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                  <div id="cover_image" class="kt-avatar__holder_cover" style="background-image: url({{asset('storage/'.$user->cover_image)}})"> </div>
                  <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                    <i class="fa fa-pen"></i>
                    <input type="file" class="@error('cover_image') is-invalid @enderror" onchange="readURLCover(this)" name="cover_image" accept=".png, .jpg, .jpeg">
                  </label>
                  <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" onclick="ImageClear('#cover_image')" data-original-title="Cancel">
                    <i class="fa fa-times"></i>
                  </span>
                </div>
                @error('cover_image')
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
                  <a href="{{route('supplier.index')}}" class="btn btn-secondary">Cancel</a>
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
  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image').css("background-image", "url("+ e.target.result + ")");

            };

            reader.readAsDataURL(input.files[0]);
        }
    }
  function readURLCover(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cover_image').css("background-image", "url("+ e.target.result + ")");

            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function ImageClear(id) {
        $(id).css("background-image", "url()");

    }
</script>
@endsection
