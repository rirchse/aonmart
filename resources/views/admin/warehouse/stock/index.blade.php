@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Products Stock</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('purchase.create') }}" class="btn btn-primary font-weight-bolder ml-2">
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
          </span>Purchase Goods
        </a>
      </div>
    </div>

    <div class="card-body">
      <table class="table" id="myTable" width="100%">
        <thead>
        <tr>
          <th style="width: 10px">SL</th>
          <th>Image</th>
          <th>Name</th>
          <th>Category</th>
          <th>Stock</th>
          <th>Sale Price</th>
          {{--          @if (!isset($stockreport))--}}
          {{--            <th style="width: 60px">Action</th>--}}
          {{--          @endif--}}
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
              <a target="__blank" href="{{ route("warehouse.stock.store-wise", $product->id) }}">
                @unless ($store)
                  {{ $product->stock - ($product->stores->sum('pivot.stock_out')) }}
                @else
                  {{ $product->storeStock($store->id) }}
                @endunless
              </a>
            </td>
            <td>{{ $product->regular_price }}</td>
            {{--            @if (!isset($stockreport))--}}
            {{--              <td class="text-nowrap">--}}
            {{--                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-clean btn-icon text-hover-primary"><i class="la la-edit icon-lg"></i></a>--}}
            {{--                <form method="post" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block">--}}
            {{--                  @csrf--}}
            {{--                  @method('DELETE')--}}
            {{--                  <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm text-hover-danger btn-clean btn-icon">--}}
            {{--                    <i class="la la-trash icon-lg"></i>--}}
            {{--                  </button>--}}
            {{--                </form>--}}
            {{--              </td>--}}
            {{--            @endif--}}
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
