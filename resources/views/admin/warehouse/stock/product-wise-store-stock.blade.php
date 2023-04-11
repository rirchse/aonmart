@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Products Stock Store Wise</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('warehouse.stock') }}" class="btn btn-primary font-weight-bolder ml-2">
          <i class="fa fa-arrow-left"></i>&nbsp;Back
        </a>
      </div>
    </div>

    <div class="card-body">
      <table class="table" id="myTable" width="100%">
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Stock</th>
          </tr>
        </thead>
        <tbody>
          @if ($product->in_stock > 0)
            <tr>
              <td>
                <img src="{{ getImageUrl() }}" alt="Warehouse" height="50px" width="50px" loading="lazy" />
              </td>
              <td>Warehouse</td>

              <td>{{ $product->stock - $product->stock_out }}</td>
            </tr>
          @endif
          @foreach ($stores as $store)
            <tr>
              <td>
                <img src="{{ getImageUrl($store->image) }}" alt="{{ $store->name }}" height="50px" width="50px" loading="lazy" />
              </td>
              <td>{{ $store->name }}</td>

              <td>{{ $store->Products->first()->pivot->stock - $store->Products->first()->pivot->stock_out }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" />
@endpush

@push('script')
  <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    })
  </script>
@endpush
