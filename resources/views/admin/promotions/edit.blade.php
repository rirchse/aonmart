@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <h3 class="card-title">Edit Promotion</h3>
    </div>
    <div class="card-body">

      <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{ route('promotions.update', $promotion->id) }}">
        @csrf
        @method('PUT')
        <div class="kt-portlet__body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group form-row">
                <label for="title">Title :
                  <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : null }}" value="{{ old('title', $promotion->title) }}" name="title" id="title" required/>
                @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group form-row">
                <label for="start_date">Start Date :
                  <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control {{ $errors->has('title') ? 'is-invalid' : null }}" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" name="start_date" id="start_date" required/>
                @error('start_date')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group form-row">
                <label for="end_date">End Date :
                  <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control {{ $errors->has('title') ? 'is-invalid' : null }}" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" name="end_date" id="end_date" required/>
                @error('end_date')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group form-row">
                <label for="status">Status :
                  <span class="text-danger">*</span>
                </label>
                <select class="form-control {{ $errors->has('title') ? 'is-invalid' : null }}" name="status" id="status" required>
                  <option value="1" selected>Active</option>
                  <option value="0" {{ old('status', $promotion->status) == 0 ? 'selected' : null }}>Inactive</option>
                </select>
                @error('status')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>

          @livewire('dashboard.promotion-products', compact('products', 'promotion'))

        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <div class="row">
              <div class="col-lg-6">
                <!-- <button type="reset" class="btn btn-danger">Delete</button> -->
              </div>
              <div class="col-lg-6 kt-align-right">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

  </div>
@endsection
