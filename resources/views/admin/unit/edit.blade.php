@extends('layouts.default')
@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Edit Unit</h3>
            </div>
            @if(session()->has('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
            @elseif(session()->has('error'))
            <div class="alert alert-danger">
                {{session('error')}}
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
            <form class="form" enctype="multipart/form-data" method="POST" action="{{route('unit.update', $unit->id)}}">
                @csrf
                @method('PUT')
                <div class="kt-portlet__body">
                  <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label"><b>{{__('Unit Name')}} <span class="text-danger">*</span></b></label>
                    <div class="col-lg-8">
                      <input name="name" id="name" value="{{old('name') ?? $unit->name}}" placeholder="Ex: " type="text" class="form-control  @error('name') is-invalid @enderror">
                      @error('name')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                      <span class="form-text text-muted">Please enter the unit name.</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="status" class="col-md-2 col-form-label"><b>{{__('Status')}} <span class="text-danger">*</span></b></label>
                    <div class="col-lg-8">
                      <select name="status" id="status" value="{{old('status') ?? $unit->status}}" class="custom-select  @error('status') is-invalid @enderror">
                        <option value="1" selected>Active</option>
                        <option value="0" {{ (old('status') ?? $unit->status) ? null : 'selected' }}>Inactive</option>
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
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{route('unit.index')}}" class="btn btn-secondary">Cancel</a>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
        </div>
    </div>

</div>
@endsection
