@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Edit Slide </h3>
      </div>
    </div>

    <div class="card-body">
      <form enctype="multipart/form-data" method="POST" action="{{ route('settings.slider.update', $slider->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
          <div class="col-md-8 offset-md-2">
            <x-dashboard.image-input name="image" label="Image" :default="getImageUrl($slider->image)"></x-dashboard.image-input>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="title"><b>{{ __('Title') }}&nbsp;<span class="text-danger">*</span></b></label>
            <input name="title" id="title" value="{{ old('title', $slider->title) }}" placeholder="Ex: Store Name" type="text" class="form-control  @error('title') is-invalid @enderror">
            @error('title')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="subtitle"><b>{{ __('Subtitle') }}</b></label>
            <input name="subtitle" id="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" placeholder="Ex: Store Name" type="text" class="form-control  @error('subtitle') is-invalid @enderror">
            @error('subtitle')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="button_text"><b>{{ __('Button Text') }}</b></label>
            <input name="button_text" id="button_text" value="{{ old('button_text', $slider->button_text) }}" placeholder="Ex: Store Name" type="text" class="form-control  @error('button_text') is-invalid @enderror">
            @error('button_text')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="button_link"><b>{{ __('Button Link') }}</b></label>
            <input name="button_link" id="button_link" value="{{ old('button_link', $slider->button_link) }}" placeholder="Ex: Store Name" type="text" class="form-control  @error('button_link') is-invalid @enderror">
            @error('button_link')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="type"><b>{{ __('Type') }}&nbsp;<span class="text-danger">*</span></b></label>
            <select name="type" id="type" class="custom-select  @error('type') is-invalid @enderror">
              @foreach($availableTypes as $key => $type)
                <option value="{{ $key }}" {{ old('type', $slider->type) == $key ? 'selected' : '' }}>{{ $type }}</option>
              @endforeach
            </select>
            @error('type')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-8 offset-lg-2">
            <label for="status"><b>{{ __('Status') }}&nbsp;<span class="text-danger">*</span></b></label>
            <select name="status" id="status" class="custom-select  @error('status') is-invalid @enderror">
              <option value="1" selected>Active</option>
              <option value="0" {{ old('status', $slider->status) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('settings.slider.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
