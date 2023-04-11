@extends('layouts.default')

@section('content')

<?php $stock = $total_stock = $total_buying_price = $buying_price = 0; ?>

  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Products Stock</h3>
      </div>
      <div class="card-toolbar">
      </div>
    </div>

    <div class="card-body">
      <form class="" action="{{route('report.stock')}}">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="">Category</label>
              <select class="form-control" name="category" id="">
                <option value="">Select Category</option>
                @foreach(App\Models\Category::where('status', 1)->get() as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <!-- <div class="col-md-3">
            <div class="form-group">
              <label for="">Product</label>
              <select class="form-control" name="most-buying" id="">
                <option value="">Select Item</option>
                <option value="Fair & Lovely">7up 1 Ltr</option>
              </select>
            </div>
          </div> -->
          <div class="col-md-9 pull-right">
            <div style="text-align:right;font-size:15px">
              <label class="control-label">Stock: <b><span id="stock_show">00</span>, <span id="stock_amount_show">00</span></b></label></div>
          </div>
        </div>
        <div class="form-row">
          <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        </div>
      </form>
      <br>
      <table class="table" id="myTable" width="100%">
        <thead>
        <tr>
          <th style="width: 10px">SL</th>
          <th>Image</th>
          <th>Name</th>
          <th>Category</th>
          <th>Stock</th>
          <th>Buying Price</th>
          <th>Total Price</th>
          <th>Sale Price</th>
          <th style="width: 60px">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" height="50px" width="50px" loading="lazy"/>
            </td>
            <td>{{ $product->name }}</td>
            <td>
              @foreach ($product->categories as $category)
                <p class="badge badge-secondary d-inline-block w-auto">{{ $category->name }}</p>
              @endforeach
            </td>
            <td>
              <a target="_blank" href="{{ route('warehouse.stock.store-wise', $product->id) }}">
                @unless ($store)
                  {{ $product->stock - ($product->stores->sum('pivot.stock_out')) }}
                @else
                  {{ $product->storeStock($store->id) }}
                  <?php
                  $total_stock += $product->storeStock($store->id);
                  $stock = $product->storeStock($store->id);
                  ?>
                @endunless
              </a>
            </td>
            <?php $prod_price = $product->product_purchase($product->id) ?>
            <td>
              @if($prod_price)
              {{$prod_price->product_price}}
              <?php
              $total_buying_price += $prod_price->product_price*$stock;
              $buying_price = $prod_price->product_price;
              ?>
              @endif
            </td>
            <td>{{$stock*$buying_price}} Tk.</td>
            <td>{{ $product->regular_price }} Tk.</td>
            <td><a target="_blank" title="Purchase History" href="{{route('report.stockHistory', $product->id)}}"><i class="fa fa-eye"></i></a></td>
          </tr>
        @endforeach
        </tbody>
        <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th>Total=</th>
          <th id="stock_input">{{$total_stock}} Pcs</th>
          <th></th>
          <th id="total_amount_input">{{$total_buying_price}} Tk.</th>
          <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <script>
    function setStock(){
      document.getElementById('stock_show').innerHTML = document.getElementById('stock_input').innerHTML;
      document.getElementById('stock_amount_show').innerHTML = document.getElementById('total_amount_input').innerHTML;
    }
    setStock();

  </script>

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
