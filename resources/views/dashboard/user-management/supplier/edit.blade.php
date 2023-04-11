@extends('layouts.default')
@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Edit Supplier</h3>
      </div>
    </div>
    <div class="card-body">
      <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{ route('supplier.update', $supplier->id) }}">
        @csrf
        @method('PUT')

        <div class="kt-portlet__body">

          @include('dashboard.user-management.partials.edit-form-fields', ['user' => $supplier])

          <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-md-8">
              <h3 class="mb-2">Suppler Information</h3>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-2"></div>
            <div class="col-md-8">
              <label for="company_id"><b>{{__('Company')}}</b></label>
              <select class="form-control select2-withTag" name="company_id" id='company_id'>
                <option value=""></option>
                @foreach ($companies as $company)
                  <option value="{{ $company->name }}" {{ collect(old('company_id', $supplier->company->name ?? ''))->contains($company->name) ? 'selected' : null }}>{{ $company->name }}</option>
                @endforeach
              </select>
              @error('company_id')
              <span class="text-danger"><strong>{{ $message }}</strong></span>
              @enderror
            </div>
          </div>

          <div class="form-group row">
            <div class="offset-lg-2 col-lg-8">
              <label for="product_id">{{ __('Products') }}&nbsp;<span class="text-danger">*</span>
              </label>
              <select class="form-control select2" multiple id="product_id" name="product_id[]">
                @foreach ($products as $product)
                  <option value="{{ $product->id }}" {{ collect(old('product_id', $supplier->Products->pluck('id')))->contains($product->id) ? 'selected' : null }}>{{ $product->name }}</option>
                @endforeach
              </select>
              @error('product_id')
              <span class="text-danger"><strong>{{ $message }}</strong></span>
              @enderror
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
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
