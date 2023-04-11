@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Orders</h3>
      </div>
    </div>

    <div class="card-header form-inline">
      <div class="form-row">
        <label class="form-label">Search By Order Number: </label>
        <div class="form-group">
          <input type="text" class="form-control" value="" style="margin:0 10px" onkeyup="
          var invbtn = document.getElementById('invBtn');
          if(this.value != ''){
            invbtn.setAttribute('href');
            invbtn.href = '/dashboard/order-invoice/'+this.value;
          }else{
            invbtn.removeAttribute('href');
          }
          ">
          <a target="_blank" class="btn btn-primary" id="invBtn">View Invoice</a>
          </div>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col">
          <table class="table"  id="data-table">
            <thead>
            <tr>
              <th>Order No</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Shipping Info</th>
              <th>Payment Method</th>
              <th>Payment Status</th>
              <th>Status</th>
              <th>Order From</th>
              <th style="width: 60px">Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($orders as $order)
              <tr>
                <td class="align-middle">
                  {{ $order->order_no }}
                </td>
                <td class="align-middle">
                  {{ dateFormat($order->created_at) }}
                </td>
                <td class="align-middle">
                  {{ $order->user?->name ?? '-' }}
                </td>
                <td class="align-middle">
                  {{ $order->address?->address }}
                </td>
                <td class="align-middle">
                  {{ $order->formatted_payment_method }}
                </td>
                <td class="align-middle">
                  {{ $order->formatted_payment_status }}
                </td>
                <td class="align-middle">
                  {{ $order->formatted_order_status }}
                </td>
                <td>
                  @if($order->order_from == 1)
                  <label title="Website"><i class='fa fa-globe'></i></label>
                  @ifelse($order_form == 2)
                  <label title="Mobile"><i class='fa fa-mobile'></i></label>
                  @else
                  <label title="POS"><i class='fa fa-desktop'></i></label>
                  @endif

                </td>
                <td class="align-middle" nowrap="nowrap">
                  <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-icon btn-clean text-hover-primary" title="Order Details"><i class="far fa-eye"></i></a>
                  <x-dashboard.update-order-status :order="$order" class="btn btn-sm btn-icon btn-clean text-hover-primary" label="<i class='fa fa-wrench'></i>"></x-dashboard.update-order-status>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
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
      $('#data-table').DataTable();
    })
  </script>
@endpush
