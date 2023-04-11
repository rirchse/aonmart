@extends('layouts.default')
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">Add New Cupon</h3>
        </div>
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
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

        <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{route('coupon.store')}}">
            @csrf
            <div class="kt-portlet__body">
              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="code"><b>{{__('Coupon Code')}} <span class="text-danger">*</span></b></label>
                  <input name="code" id="code" value="{{old('code')}}" placeholder="Ex: #SD3F34823" type="text" class="form-control  @error('code') is-invalid @enderror">
                  @error('code')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the coupon code.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="product_id"><b>{{__('Product')}} <span class="text-danger">*</span></b></label>
                  <select class="form-control select2" multiple name="product_id[]">
                    <option value="" disabled>-- Select Products --</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                  </select>
                  @error('product_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Select coupon product.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="discount"><b>{{__('Discount')}} <span class="text-danger">*</span></b></label>
                  <input name="discount" id="discount" value="{{old('discount')}}" placeholder="Ex: 5" type="number" class="form-control  @error('discount') is-invalid @enderror">
                  @error('discount')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the discount amount.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="expire_at"><b>{{__('Expire At')}} <span class="text-danger">*</span></b></label>
                  <input name="expire_at" id="expire_at" value="{{old('expire_at')}}" placeholder="Ex: " class="form-control datetimepicker @error('expire_at') is-invalid @enderror">
                  @error('expire_at')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please enter the expire date and time.</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <label for="status"><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                  <select name="status" id="status" value="{{old('status')}}" class="custom-select  @error('status') is-invalid @enderror">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                  </select>
                  @error('status')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <span class="form-text text-muted">Please select the status.</span>
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
                    <a href="{{route('coupon.index')}}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
    </div>
</div>

@endsection
