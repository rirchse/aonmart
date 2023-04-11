@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">All Purchase Return</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('purchase_return.create') }}" class="btn btn-primary font-weight-bolder">
          <span class="svg-icon svg-icon-md">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <rect x="0" y="0" width="24" height="24"></rect>
                      <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                      <path
                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                        fill="#000000" opacity="0.3"></path>
                  </g>
              </svg>
          </span>Purchase Return</a>
      </div>
    </div>

    <div class="card-body">
      <table class="table" id="myTable" width="100%">
        <thead>
        <tr>
          <th>Invoice</th>
          <th>Return Quantity</th>
          <th>Return Amount</th>
          <th style="width: 60px">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($purchaseReturns as $purchaseReturn)
          <tr>
            <td>{{ $purchaseReturn->purchase->invoice_no }}</td>
            <td>{{ $purchaseReturn->total_qty }}</td>
            <td>{{ $purchaseReturn->return_amount }}</td>
            <td>
{{--              <a href="#" class="btn btn-primary">Details</a>--}}
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"/>
@endpush

@push('script')
  <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    })
  </script>
@endpush
