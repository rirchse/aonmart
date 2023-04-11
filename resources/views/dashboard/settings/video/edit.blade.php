@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">New Video </h3>
      </div>
    </div>

    <div class="card-body">
      <form enctype="multipart/form-data" method="POST" action="{{ route('settings.video.update', $video->id) }}">
        @csrf
        @method('PUT')
        
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <x-dashboard.image-input name="thumbnail" label="Thumbnail" :default="$video->thumbnail"></x-dashboard.image-input>

            <x-dashboard.input name="title" label="Title" :default="$video->title"></x-dashboard.input>

            <x-dashboard.input name="link" label="Link" :default="$video->link"></x-dashboard.input>

            <x-dashboard.status-picker name="status" :default="$video->status"></x-dashboard.status-picker>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 offset-md-3">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('settings.video.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
