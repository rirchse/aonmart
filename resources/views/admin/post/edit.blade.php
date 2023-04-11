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
              Edit Feature
            </h3>
          </div>
        </div>
        <!--begin::Form-->

        <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{route('post.update', $post->id)}}">
          @csrf
          @method('PUT')
          <div class="kt-portlet__body">
            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="name"><b>{{__('Name')}} <span class="text-danger">*</span></b></label>
                <input name="name" id="name" value="{{old('name') ?? $post->name}}" placeholder="Ex: " type="text" class="form-control  @error('name') is-invalid @enderror">
                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Please enter the post name.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="category_id"><b>{{__('Post Category')}}</b></label>
                <select class="form-control select2-withTag" name="category_id" value="{{ old('category_id') ?? $post->category_id }}">
                  <option value="">-- Select Post Category --</option>
                  @foreach ($postCategories as $category)
                    <option value="{{ $category->id }}" {{ (old('category_id') ?? $post->category_id) == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
                  @endforeach
                </select>
                @error('category_id')
                <span style="display: block" class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Select Post Category.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="tag_id"><b>{{__('Tags')}}</b></label>
                <select class="form-control select2-withTag" multiple name="tag_id[]" value="{{ old('tag_id') ?? $oldTags }}">
                  <option value="">-- Select Post Tag --</option>
                  @foreach ($blogTags as $tag)
                    <option value="{{ $tag->id }}" {{ (old('tag_id') ?? $oldTags)->contains($tag->id) ? 'selected' : null }}>{{ $tag->name }}</option>
                  @endforeach
                </select>
                @error('tag_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                <span class="form-text text-muted">Select Post Tags.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="seo_key_word"><b>{{__('Key Words')}}</b></label>
                <select class="form-control select2-withTag" multiple name="seo_key_word[]" value="{{ old('seo_key_word') ?? $old_seo_key_word }}">
                  <option value="">-- Select key words --</option>
                  @foreach ($old_seo_key_word as $keyWord)
                    <option value="{{ $keyWord }}" {{ (old('seo_key_word') ?? $old_seo_key_word)->contains($keyWord) ? 'selected' : null }}>{{ $keyWord }}</option>
                  @endforeach
                </select>
                @error('seo_key_word')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
                @enderror
                <span class="form-text text-muted">Select product Tags.</span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8">
                <label for="status"><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                <select name="status" id="status" value="{{old('status') ?? $post->status}}" class="custom-select  @error('status') is-invalid @enderror">
                  <option value="1" selected>Active</option>
                  <option value="0" {{ (old('status') ?? $post->status) ? null : 'selected' }}>Inactive</option>
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
                  <label for="image" class=""><b>{{__('Feature Image')}}</b></label>
                </div>
                <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                  <div id="image" class="kt-avatar__holder_cover" style="background-image: url({{asset('storage/'.$post->image)}})"> </div>
                  <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change">
                    <i class="fa fa-pen"></i>
                    <input type="file" class="@error('image') is-invalid @enderror" onchange="readURL(this)" name="image" accept=".png, .jpg, .jpeg">
                    <input type="text" value="{{$post->image}}" style="display: none" name="old_image">
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
                <span class="form-text text-muted">Allowed file types: png, jpg, jpeg. <br> Standard Resulation 940px X 720px (H x W). <br> Maximum size 512kb.</span>

              </div>
            </div>

            <div class="form-group">
              <textarea name="details" id="editor">{!! $post->details !!}</textarea>
              @error('details')
              <span style="display: block" class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
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
                  <a href="{{route('post.index')}}" class="btn btn-secondary">Cancel</a>
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
    function ImageClear() {
        $('#image').css("background-image", "url()");

    }
</script>
@endsection

@push('script')
  <script src="https://cdn.ckeditor.com/ckeditor5/24.0.0/classic/ckeditor.js"></script>
  <script>
    ClassicEditor
      .create(document.querySelector('#editor'))
      .catch(error => {
        console.error(error);
      });
  </script>
@endpush
