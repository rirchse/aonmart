@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Import Purchases Data</h3>
      </div>
    </div>

    <div class="card-body">
      <form enctype="multipart/form-data" method="POST" action="{{ route('import.purchases') }}">
        @csrf
        @method('PUT')

        <div class="row">
          <div class="col-md-6 offset-md-3">
            <div class="form-group">
              <label for="file">File To Import <span class="text-danger">*</span></label>
              <div class="input-group @error('file') is-invalid @enderror">
                <div class="input-group-prepend">
                  <span class="input-group-text">Ex.: .csv</span>
                </div>
                <div class="custom-file">
                  <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file" required>
                  <label class="custom-file-label" for="file">Choose file to import purchases</label>
                </div>
              </div>
              @error('file')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
              @enderror
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 offset-md-3">
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
