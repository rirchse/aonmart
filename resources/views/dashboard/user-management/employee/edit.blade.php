@extends('layouts.default')
@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Edit Employee</h3>
      </div>
    </div>
    <div class="card-body">
      <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{ route('employee.update', $employee->id) }}">
        @csrf
        @method('PUT')

        <div class="kt-portlet__body">

          @include('dashboard.user-management.partials.edit-form-fields', ['user' => $employee])

          <div class="row mt-2">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
              <h3>Employee Details</h3>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
              <label for="role">{{ __('Role') }}&nbsp;<span class="text-danger">*</span>
              </label>
              <select class="form-control select2" id="role" name="role" style="width: 100%">
                @foreach ($roles as $role)
                  <option value="{{ $role->name }}" {{ old('role', $employee->roles->first()->name) == $role->name ? 'selected' : null }}>{{ $role->name }}</option>
                @endforeach
              </select>
              @error('status')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
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
                <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
