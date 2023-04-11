@extends('layouts.default')
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <!--begin::Portlet-->
        <div class="kt-portlet">
          @if(session()->has('success'))
            <div class="alert alert-success">
              {{session()->get('success')}}
            </div>
          @elseif(session()->has('error'))
            <div class="alert alert-danger">
              {{session()->get('error')}}
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
                Add Banners
              </h3>
            </div>
          </div>
          <!--begin::Form-->

          <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{route('store.banner')}}">
            @csrf
            <div class="kt-portlet__body">
              <h4 class="pb-3">Banner section 1 </h4>
              <div class="form-group row">
                <div class="col-lg-3">
                  <div>
                    <label for="image" class=""><b>{{__('Banner 1')}}</b></label>
                  </div>
                  <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                    <div id="image" class="kt-avatar__holder_icon" style="background-image: url('{{ asset('storage/' . (isset($banner->banner1) ? $banner->banner1 : '' )) }}')"></div>
                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                      <i class="fa fa-pen"></i>
                      <input type="file" value="{{old('banner1')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="banner1"
                             accept=".png, .jpg, .jpeg">
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

                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="banner1_url" class=""><b>{{__('Banner Url 1')}}</b></label>
                    <input type="text"
                      class="form-control" name="banner1_url" id="" aria-describedby="helpId" placeholder="">
                    <small id="helpId" class="form-text text-muted">Help text</small>
                  </div>
                </div>
              </div>

              <h4 class="pb-3">Banner section 2 </h4>

              <div class="form-group row">
                <div class="col-lg-3">
                  <div>
                    <label for="image" class=""><b>{{__('Banner 2')}}</b></label>
                  </div>
                  <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                    <div id="image" class="kt-avatar__holder_icon" style="background-image: url('{{ asset('storage/' . (isset($banner->banner2) ? $banner->banner2 : '' )) }}')"></div>
                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                      <i class="fa fa-pen"></i>
                      <input type="file" value="{{old('banner1')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="banner2"
                             accept=".png, .jpg, .jpeg">
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
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="banner1_url" class=""><b>{{__('Banner Url 2')}}</b></label>
                    <input type="text"
                      class="form-control" name="banner2_url" id="" aria-describedby="helpId" placeholder="">
                    <small id="helpId" class="form-text text-muted">Help text</small>
                  </div>
                </div>

                <div class="col-lg-3">
                  <div>
                    <label for="image" class=""><b>{{__('Banner 3')}}</b></label>
                  </div>
                  <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                    <div id="image" class="kt-avatar__holder_icon" style="background-image: url('{{ asset('storage/' . (isset($banner->banner3) ? $banner->banner3 : '' )) }}')"></div>
                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                      <i class="fa fa-pen"></i>
                      <input type="file" value="{{old('banner1')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="banner3"
                             accept=".png, .jpg, .jpeg">
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
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="banner1_url" class=""><b>{{__('Banner Url 3')}}</b></label>
                    <input type="text"
                      class="form-control" name="banner3_url" id="" aria-describedby="helpId" placeholder="">
                    <small id="helpId" class="form-text text-muted">Help text</small>
                  </div>
                </div>
              </div>

              <h4 class="pb-3">Banner section 3 </h4>
              <div class="form-group row">
                <div class="col-lg-3">
                  <div>
                    <label for="image" class=""><b>{{__('Banner 4')}}</b></label>
                  </div>
                  <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                    <div id="image" class="kt-avatar__holder_icon" style="background-image: url('{{ asset('storage/' . (isset($banner->banner4) ? $banner->banner4 : '' )) }}')"></div>
                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                      <i class="fa fa-pen"></i>
                      <input type="file" value="{{old('banner1')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="banner4"
                             accept=".png, .jpg, .jpeg">
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

                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="banner1_url" class=""><b>{{__('Banner Url 4')}}</b></label>
                    <input type="text"
                      class="form-control" name="banner4_url" id="" aria-describedby="helpId" placeholder="">
                    <small id="helpId" class="form-text text-muted">Help text</small>
                  </div>
                </div>
              </div>

              <h4 class="pb-3">Banner section 4 </h4>

              <div class="form-group row">
                <div class="col-lg-3">
                  <div>
                    <label for="image" class=""><b>{{__('Banner 5')}}</b></label>
                  </div>
                  <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                    <div id="image" class="kt-avatar__holder_icon" style="background-image: url('{{ asset('storage/' . (isset($banner->banner5) ? $banner->banner5 : '' )) }}')"></div>
                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                      <i class="fa fa-pen"></i>
                      <input type="file" value="{{old('banner1')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="banner5"
                             accept=".png, .jpg, .jpeg">
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
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="banner1_url" class=""><b>{{__('Banner Url 5')}}</b></label>
                    <input type="text"
                      class="form-control" name="banner5_url" id="" aria-describedby="helpId" placeholder="">
                    <small id="helpId" class="form-text text-muted">Help text</small>
                  </div>
                </div>

                <div class="col-lg-3">
                  <div>
                    <label for="image" class=""><b>{{__('Banner 6')}}</b></label>
                  </div>
                  <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                    <div id="image" class="kt-avatar__holder_icon" style="background-image: url('{{ asset('storage/' . (isset($banner->banner6) ? $banner->banner6 : '' )) }}')"></div>
                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                      <i class="fa fa-pen"></i>
                      <input type="file" value="{{old('banner1')}}" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="banner6"
                             accept=".png, .jpg, .jpeg">
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
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="banner1_url" class=""><b>{{__('Banner Url 6')}}</b></label>
                    <input type="text"
                      class="form-control" name="banner6_url" id="" aria-describedby="helpId" placeholder="">
                    <small id="helpId" class="form-text text-muted">Help text</small>
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
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{route('slider.index')}}" class="btn btn-secondary">Cancel</a>
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
        let reader = new FileReader();
        reader.onload = function (e) {
          $('#image').css("background-image", "url(" + e.target.result + ")");
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function ImageClear() {
      $('#image').css("background-image", "url()");
    }
  </script>
@endsection
