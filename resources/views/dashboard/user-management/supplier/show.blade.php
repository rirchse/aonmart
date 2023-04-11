@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Supplier Details</h3>
      </div>
    </div>
    <div class="card-body">
      <h3>Coming Soon</h3>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-lg-12 text-right">
          <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </div>
@endsection
