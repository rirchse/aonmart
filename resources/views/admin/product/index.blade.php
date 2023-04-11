@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Products</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('product.barcode.print.list') }}" class="btn btn-info mx-1 font-weight-bolder">
          <span class="svg-icon svg-icon-md">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path
                  d="M15,9 L13,9 L13,5 L15,5 L15,9 Z M15,15 L15,20 L13,20 L13,15 L15,15 Z M5,9 L2,9 L2,6 C2,5.44771525 2.44771525,5 3,5 L5,5 L5,9 Z M5,15 L5,20 L3,20 C2.44771525,20 2,19.5522847 2,19 L2,15 L5,15 Z M18,9 L16,9 L16,5 L18,5 L18,9 Z M18,15 L18,20 L16,20 L16,15 L18,15 Z M22,9 L20,9 L20,5 L21,5 C21.5522847,5 22,5.44771525 22,6 L22,9 Z M22,15 L22,19 C22,19.5522847 21.5522847,20 21,20 L20,20 L20,15 L22,15 Z"
                  fill="#000000"/>
                <path d="M9,9 L7,9 L7,5 L9,5 L9,9 Z M9,15 L9,20 L7,20 L7,15 L9,15 Z" fill="#000000" opacity="0.3"/>
                <rect fill="#000000" opacity="0.3" x="0" y="11" width="24" height="2" rx="1"/>
              </g>
            </svg>
          </span>Print Barcode
        </a>
        @canany(['product.all', 'product.add'])
          <a href="{{ route('product.create') }}" class="btn btn-primary mx-1 font-weight-bolder">
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
            </span>Add New Product
          </a>
        @endcanany
      </div>
    </div>

    <div class="card-body">
      <table class="table" id="myTable" width="100%">
        <thead>
        <tr>
          <th style="width: 10px">SL</th>
          <th width="10">Image</th>
          <th>Name</th>
          <th>Barcode</th>
          <th>Category</th>
          <th class="text-right">Sale&nbsp;Price</th>
          <th class="text-center">Status</th>
          <th style="width: 60px">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
          <tr>
            <td>
              {{ $loop->iteration }}
            </td>
            <td>
              <img src="{{ $product->image_url }}" alt="avatar" height="50px" width="50px" loading="lazy"/>
            </td>
            <td>
              {{ $product->name }}
            </td>
            <td>
              {{ $product->barcode }}
            </td>
            <td>
              @foreach ($product->categories as $category)
                <span class="label font-weight-bold label-lg label-light-info label-inline">{{ $category->name }}</span>
              @endforeach
            </td>
            <td class="text-right">{{ $product->regular_price }}&nbsp;TK</td>
            <td class="text-center">
                <span class="label font-weight-bold label-lg label-light-primary label-inline">
                  {{ $product->status ? 'Active' : 'Inactive' }}

                </span>
            </td>
            <td nowrap="nowrap">
              @canany(['product.all', 'product.edit'])
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-icon btn-clean text-hover-primary"><i class="la la-edit icon-lg"></i></a>
              @endcanany

              @canany(['product.all', 'product.delete'])
                <form method="post" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block">
                  @csrf
                  @method('DELETE')
                  <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm btn-icon btn-clean text-hover-danger">
                    <i
                      class="la la-trash icon-lg"></i>
                  </button>
                </form>
              @endcanany
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
