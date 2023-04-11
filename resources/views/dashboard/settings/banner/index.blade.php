@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Banners</h3>
      </div>
    </div>

    <div class="card-body">
      <form enctype="multipart/form-data" method="POST" action="{{ route('settings.banner.update') }}">
        @csrf
        @method('PUT')

        @foreach ($banners->groupBy('type') as $type_wise_banners)
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <h2 class="my-4">{{ $bannerTypes[$type_wise_banners->first()->type] }}s</h2>
            </div>
          </div>
          @foreach($type_wise_banners as $banner)
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <x-dashboard.image-input name="image[{{ $banner->key }}]" label="{{ $bannersConstants[$banner->key] }}" default="{{ $banner->getFirstMediaUrl() }}" ignore_set_image></x-dashboard.image-input>
              </div>
            </div>

            <div class="row">
              <div class="col-md-8 offset-md-2">
                <x-dashboard.input name="title[{{ $banner->key }}]" label="Title of {{ $bannersConstants[$banner->key] }}" default="{{ $banner->title }}" required></x-dashboard.input>
              </div>
            </div>

              @if($banner->type == \App\Models\Banner::TYPE_WHY_PEOPLE_LOVE)
                <div class="row">
                  <div class="col-md-8 offset-md-2">
                    <x-dashboard.input name="subtitle[{{ $banner->key }}]" label="Subtitle of {{ $bannersConstants[$banner->key] }}" default="{{ $banner->subtitle }}" required></x-dashboard.input>
                  </div>
                </div>
              @endif
          @endforeach
        @endforeach

        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('settings.banner.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
