@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">{{ __('Order Details') }}</h3>
      </div>
      <div class="card-toolbar ">
        <x-dashboard.update-order-status :order="$order" class="btn btn-primary mr-1 ml-1"></x-dashboard.update-order-status>
        <a href="{{ route('order.index') }}" class="btn btn-primary font-weight-bolder mr-1 ml-1"><i class="fas fa-arrow-left fa-sm"></i>&nbsp;{{ __('Back') }}</a>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="d-flex justify-content-between">
            <div>
              <h6>
                <strong>Order Number :</strong> {{ $order->order_no }}
              </h6>
              <h6><strong>Customer :</strong> {{ $order->user?->name }}</h6>
              <h6><strong>Phone :</strong> {{ $order->user?->mobile }}</h6>
              @if($order->user?->email)
                <h6><strong>Email :</strong> {{ $order->user?->email }}</h6>
              @endif
            </div>
            <span>
              <strong>Order Date :</strong> {{ dateFormat($order->created_at) }}
              <br>
              <strong>Order Status :</strong> {{ $order->formatted_order_status }}
              <br>
              @if ($order->order_status == App\Models\Order::CANCELLED)
                <strong>Cancel Reason :</strong> {{ $order->cancel_reason }}
                <br>
              @endif
              <strong>Payment Method :</strong> {{ $order->formatted_payment_method }}
              <br>
              <strong>Payment Status :</strong> {{ $order->formatted_payment_status }}
            </span>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-12">
          <table class="table table-bordered" id="myTable" width="100%">
            <thead>
            <tr>
              <th style="width: 10px">SL</th>
              <th>Product name</th>
              <th>Product Qty</th>
              <th>Unit Price</th>
              <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->products as $product)
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
                  {{ $product->pivot->price }} tk
                </td>
                <td>
                  {{ $product->pivot->price * $product->pivot->qty }} tk
                </td>
              </tr>
            @endforeach
            <tr>
              <th colspan="4">
                <p class="mb-0 text-right">Grand Total</p>
              </th>
              <td>
                <p class="mb-0">{{ $order->total }} tk</p>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-4">
          <h4 class="font-weight-bolder">Shipping Info</h4>
          <table class="table table-sm table-borderless">
            <tr>
              <th>Address</th>
              <td>: {{ $order->address?->address }}</td>
            </tr>
          </table>
        </div>
      </div>
      @if($order->feedbacks->count())
        <div class="row border-top-lg pt-4">
          <div class="col-md-4">
            <h4 class="font-weight-bolder">Feedback</h4>
            <table class="table table-sm table-borderless">
              @foreach($order->feedbacks as $feedback)
                <tr>
                  <th><img class="w-40px rounded-circle" src="{{ getImageUrl($order->user?->profile_picture) }}" alt="{{ $order->user?->name }}" loading="lazy"> {{ $order->user?->name }}</th>
                </tr>
                <tr>
                  <td>{{ $feedback->content }}</td>
                </tr>
              @endforeach
            </table>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection
