@extends('layouts.default')

@push('style')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sl-1.3.3/datatables.min.css"/>
@endpush

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Purchase Details</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('purchase.index') }}" class="btn btn-primary font-weight-bolder"><i class="fas fa-arrow-left fa-sm"></i>&nbsp;Back</a>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="d-flex justify-content-between">
            <h6>
              <strong>Invoice Number :</strong> {{ $purchase->invoice_no }}
            </h6>
            <span>
              <strong>Purchase Date :</strong> {{ $purchase->formattedDate }}
            </span>
          </div>
          <h6>
            <strong>Supplier Name :</strong> {{ $purchase->supplier->name }}
          </h6>
          <h6>
            <strong>Supplier Phone :</strong> {{ $purchase->supplier->mobile }}
          </h6>
        </div>
      </div>
      <table class="table table-bordered" id="myTable" width="100%">
        <thead>
        <tr>
          <th style="width: 10px">SL</th>
          <th>Product name</th>
          <th>Product Qty</th>
          <th>Unit Buy Price</th>
          <th>Discount</th>
          <th>Total Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($purchase->products as $product)
          <tr>
            <td>
              {{ $loop->iteration }}
            </td>
            <td>
              {{ $product->name }}
            </td>
            <td>
              {{ $product->pivot->qty }} {{ $product->unit->name }}
            </td>
            <td>
              {{ $product->pivot->product_price }} tk
            </td>
            <td>
              {{ $product->pivot->discount ? $product->pivot->discount . 'tk' : 0 }}
            </td>
            <td>
              {{ $product->pivot->total_price }} tk
            </td>
          </tr>
        @endforeach
        <tr>
          <th colspan="5">
            <p class="mb-0 text-right">Grand Total</p>
          </th>
          <td>
            <p class="mb-0">{{ $purchase->grand_total }} tk</p>
          </td>
        </tr>
        <tr>
          <th colspan="5">
            <p class="mb-0 text-right">Due</p>
          </th>
          <td>
            <p class="mb-0">{{ $purchase->due_amount }}</p>
          </td>
        </tr>
        <tr>
          <th colspan="5">
            <p class="mb-0 text-right">Paid</p>
          </th>
          <td>
            <p class="mb-0">{{ $purchase->grand_total - $purchase->due_amount }} tk</p>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('script')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sl-1.3.3/datatables.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    })
  </script>
@endpush
