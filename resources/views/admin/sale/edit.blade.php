@extends('layouts.default')

@push('style')
  <style>
    .form-group {
      margin-bottom: 0.5rem;
    }

    .custom-input {
      height: 24px;
      padding: 0 5px;
    }

  </style>
@endpush

@section('content')
  <div class="container-fluid">
    <div class="form-row">
      <div class="col-12">
        <div class="alert alert-danger js_alert_error" style="display: none"></div>
        <div class="alert alert-success js_alert_success" style="display: none"></div>
      </div>

      <div class="col-12">
        <div class="d-flex mb-2">
          @livewire('sale-return')
          <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target=".bd-example-modal-lg-client">Add New Customer</button>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card" style="height:80vh;">
          <div class="card-body overflow-hidden py-4 px-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="searchByCategory" class="sr-only">Categories</label>
                  <select id="searchByCategory" name="category_id" class="form-control select2" style="width:100%">
                    <option value="all">All Categories</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="searchByProduct" class="sr-only">Search Product</label>
                  <input id="searchByProduct" class="form-control" type="text" name="searchproduct" placeholder="Search by name or barcode" autocomplete="off">
                </div>
              </div>
            </div>

            <div id="reload_div_after_sale" style="max-height: calc(100% - 50px); overflow-y: scroll">
              <div id="pds">
                @include('admin.ajax.sale_products')
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card card-primary card-outline" style="height:80vh;">
          <div class="card-body overflow-hidden py-4 px-6">
            <form action="{{ route('sale.update', $sale->id) }}" method="post" style="height: 100%; position: relative">
              @csrf
              @method('PUT')
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="number_sale" class="sr-only">Invoice</label>
                    <input type="text" id="number_sale" name="number_sale" class="form-control text-center" readonly value="{{ $sale->number_sale }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div id="client" class="form-group">
                    <label for="client_id" class="sr-only">Customer</label>
                    <select name="client_id" id="client_id" class="form-control select2" style="width:100%">
                      @foreach ($customers as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $customer->id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div
                style="border-bottom: 2px dashed #d2d2d2; border-top: 2px dashed #d2d2d2; height: calc(100% - 210px); max-height: calc(100% - 210px); overflow-y: auto; overflow-x: hidden">
                <div class="row">
                  <div class="col-md-12">
                    <table id="sale" class="table table-sm table-borderless">
                      <thead>
                      <tr>
                        <th class="border-bottom">Name</th>
                        <th class="border-bottom" width="80">Quantity</th>
                        <th class="border-bottom text-center" width="80">Price</th>
                        <th class="border-bottom text-center" style="width: 25px;">Action</th>
                      </tr>
                      </thead>
                      <tbody class="order-list">
                      @foreach ($sale->products as $product)
                        @php
                          $store_product = $sale->store
                              ->products()
                              ->where('product_id', $product->id)
                              ->first();
                        @endphp
                        <tr id="{{ $product->id }}" class="form-group items">
                          <td id="name" class="name">{{ $product->name }}</td>
                          <input type="hidden" name="product[]" value="{{ $product->id }}">
                          <td style="display: flex;">
                            <input id="qty" type="number" step="0.1" name="quantity[]" data-price="{{ $product->sell_price ?: $product->regular_price }}"
                                   data-stock="{{ $store_product->pivot->stock - $store_product->pivot->stock_out + $product->pivot->quantity }}"
                                   class="form-control custom-input input-sm product-quantity" min="1"
                                   max="{{ $store_product->pivot->stock - $store_product->pivot->stock_out + $product->pivot->quantity }}"
                                   value="{{ $product->pivot->quantity }}">
                          </td>
                          <td class="text-center product-price">{{ ($product->sell_price ?: $product->regular_price) * $product->pivot->quantity }}</td>
                          <td class="text-center">
                            <button type="button" class="btn btn-light btn-sm remove-product-btn" data-id="{{ $product->id }}"><span class="fa fa-trash"></span></button>
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="calculation-footer bg-white position-absolute fixed-bottom">
                <div class="row">
                  <div class="col-md-6 order-1 order-md-0">
                    <div class="d-flex align-items-end h-100">
                      <button type="submit" class="btn btn-success btn-sm" href="{{ route('sale.update', $sale->id) }}">Update</button>
                    </div>
                  </div>
                  <div class="col-md-6 order-0 order-md-1">
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="total">Paid by</label>
                      </div>
                      <div class="col-sm-6">
                        <select name="pay_by" id="pay_by" class="form-control custom-input">
                          <option value="cash">Cash</option>
                          <option value="bkash">Bkash</option>
                          <option value="nagad">Nagad</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="total">Sub Total </label>
                      </div>
                      <div class="col-sm-6">
                        <input type="number" id="total" name="total" class="form-control total-price custom-input border-0" min="0" readonly value="{{ $sale->total }}">
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="discount">Discount </label>
                      </div>
                      <div class="col-sm-6">
                        <input type="number" id="discount" name="discount" class="form-control discount custom-input" min="0" value="{{ $sale->discount }}">
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="total-amount">Total Payable </label>
                      </div>
                      <div class="col-sm-6">
                        <input type="number" id="total-amount" name="total_amount" class="form-control total-amount custom-input border-0" readonly min="0"
                               value="{{ $sale->total_amount }}">
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="paid">Paid </label>
                      </div>
                      <div class="col-sm-6">
                        <input id="paid" type="number" name="paid" class="form-control paid custom-input" value="{{ $sale->paid }}"/>
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="credit">Due </label>
                      </div>
                      <div class="col-sm-6">
                        <input id="credit" type="number" name="credit" class="form-control credit custom-input border-0" readonly value="{{ $sale->due }}"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            {{-- create user modal --}}
            <div class="modal fade bd-example-modal-lg-client" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="new_client" enctype="multipart/form-data" method="get">
                    @csrf
                    <div class="modal-body">
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>Full Name</label>
                        </div>
                        <div class="col-9">
                          <input type="text" name="name" id="" class="form-control" value="{{ old('name') }}">
                        </div>
                      </div>
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>Email</label>
                        </div>
                        <div class="col-9">
                          <input type="text" name="email" id="" class="form-control" value="{{ old('email') }}">
                        </div>
                      </div>
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>Telephone</label>
                        </div>
                        <div class="col-9">
                          <input type="text" name="mobile" id="" class="form-control" value="{{ old('mobile') }}">
                        </div>
                      </div>
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>note</label>
                        </div>
                        <div class="col-9">
                          <textarea type="text" name="about" id="" class="form-control">{{ old('about') }}</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                      <button type="submit" class="btn btn-primary">save</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script src="{{ asset('assets/plugins/onscan.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/sale.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      $('[data-toggle="tooltip"]').tooltip();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      setInterval(function () {
        let momentNow = moment();
        $('#date-part').html(momentNow.format('dddd') + ' ' + momentNow.format('DD MMMM YYYY'));
        $('#time-part').html(momentNow.format('kk:mm:ss'));
      }, 100);

      // add new client in sale page
      $(document).on('submit', '#new_client', function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
          type: "GET",
          url: '{{ route('add.user.ajax') }}',
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            console.log(response);
            $('.bd-example-modal-lg-client').modal('hide');
            $('#new_client')[0].reset();
            //select2 append new option
            let newOption = new Option(response.name, response.id, false, false);
            $('#client_id').append(newOption).trigger('change');
          },
          error: function (error) {
            console.log(error.responseJSON.errors);
          }
        });
      });

      // Search for product to sale by Category Product and by product name
      $("#searchByCategory").add("#searchByProduct").on('change input', function () {
        let searchByCategory = $("#searchByCategory").val();
        let searchByProduct = $("#searchByProduct").val();
        $.ajax({
          type: "GET",
          url: '{{ route('product.saleProductSearch') }}',
          data: {
            'searchByProduct': searchByProduct,
            'searchByCategory': searchByCategory,
          },
          dataType: 'html',
          success: function (data) {
            $('#pds').html(data);
            $('body .tooltip').remove();
            $('[data-toggle="tooltip"]').tooltip();
          }
        });
      });

      onScan.attachTo(document, {
        suffixKeyCodes: [13], // enter-key expected at the end of a scan
        reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
        onScan: function (sCode, iQty) { // Alternative to document.addEventListener('scan')
          console.log('Scanned: ' + iQty + 'x ' + sCode);
          $.ajax({
            type: "GET",
            url: '{{ route('getBarcodeScannedProduct.ajax') }}',
            data: 'code=' + sCode,
            dataType: 'json',
            success: function (data) {
              if (data.product[0].id) {
                document.getElementById("product-" + data.product[0].id).click();
              }
            }
          });
        },
        onKeyDetect: function (iKeyCode) {
          console.log('Pressed: ' + iKeyCode);
        }
      });
    });
  </script>

@endpush
