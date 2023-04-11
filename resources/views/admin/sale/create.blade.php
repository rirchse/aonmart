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
        <div class="d-flex mb-2 justify-content-between">
          @livewire('sale-return', compact('store'))
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
            <form action="{{ route('sale.store') }}" method="post" id="sale_form" style="height: 100%; position: relative">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group d-flex align-items-center">
                    <label for="number_sale" class="text-nowrap mr-2">Invoice</label>
                    <input type="text" name="number_sale" id="number_sale" class="form-control text-center" readonly value="{{ $sale_number }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div id="client" class="form-group">
                    <label for="client_id" class="sr-only">Customer</label>
                    <select name="client_id" id="client_id" class="form-control select2" style="width:100%">
                      @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ ((old('client_id') == $client->id) or $client->is_walk_in_customer) ? 'selected' : '' }}>{{ $client->name }}</option>
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
                      </tbody>
                      <tfoot>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              <div class="calculation-footer bg-white position-absolute fixed-bottom">
                <div class="row">
                  <div class="col-md-6 order-1 order-md-0">
                    <div class="d-flex align-items-end h-100">
                      <button type="submit" class="btn btn-primary btn-sm" id="ajax_save">Save & Print</button>
                      <button type="submit" class="btn btn-success btn-sm ml-2" href="{{ route('sale.store') }}">Save</button>
                    </div>
                  </div>
                  <div class="col-md-6 order-0 order-md-1">
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="payment_method">Payment Method</label>
                      </div>
                      <div class="col-sm-6">
                        <select name="payment_method" id="payment_method" class="form-control custom-input">
                          @foreach($payment_methods as $key => $payment_method)
                            <option value="{{ $key }}">{{ $payment_method }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="total">Sub Total </label>
                      </div>
                      <div class="col-sm-6">
                        <input type="number" id="total" name="total" class="form-control total-price custom-input border-0" min="0" readonly value="0">
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="discount">Discount </label>
                      </div>
                      <div class="col-sm-6">
                        <input type="number" id="discount" name="discount" class="form-control discount custom-input" min="0" value="0">
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="total-amount">Total Payable </label>
                      </div>
                      <div class="col-sm-6">
                        <input type="number" id="total-amount" name="total_amount" class="form-control total-amount custom-input border-0" readonly min="0" value="0">
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="paid">Paid </label>
                      </div>
                      <div class="col-sm-6">
                        <input id="paid" type="number" name="paid" class="form-control paid custom-input" value="0"/>
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="credit">Due </label>
                      </div>
                      <div class="col-sm-6">
                        <input id="credit" type="number" name="credit" class="form-control credit custom-input border-0" readonly value="0"/>
                      </div>
                    </div>
                    <div class="form-group mb-0 row">
                      <div class="col-sm-6">
                        <label class="pull-right" for="cash_change">Change</label>
                      </div>
                      <div class="col-sm-6">
                        <input id="cash_change" type="number" name="cash_change" class="form-control credit custom-input" value="0"/>
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
                          <label>Full Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-9">
                          <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                      </div>
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>Email</label>
                        </div>
                        <div class="col-9">
                          <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        </div>
                      </div>
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>Telephone <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-9">
                          <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile') }}" required>
                        </div>
                      </div>
                      <div class="form-group form-row">
                        <div class="col-3">
                          <label>note</label>
                        </div>
                        <div class="col-9">
                          <textarea type="text" name="about" id="about" class="form-control">{{ old('about') }}</textarea>
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

      //sale store and print
      $('#ajax_save').on('click', function () {
        $('#sale_form').submit(function (e) {
          e.preventDefault()
          let formData = $(this).serialize();
          $.ajax({
            type: "POST",
            url: '{{ route('sale.store') }}',
            data: formData,
            success: function (response) {
              showAlert(response, 'success');

              $('.order-list').html('');
              document.getElementById("sale_form").reset();
              increaseInvoiceNumber();
              $("#reload_div_after_sale").load(window.location.href + " #pds");

              let newWindow = open('/dashboard/print_invoice/' + response.sale_id, 'Print Invoice', 'width=450, height=550');
              newWindow.print();
              Livewire.emit('sale_created');
            },
            error: function (error) {
              showAlert(error, 'error');
            }
          });
        });
      });

      // Show alert for ajax sale create
      function showAlert(response, type) {
        if(type === 'error') {
          $('.js_alert_error').empty().css('display', 'block');
          $.each(response.responseJSON.errors, function (index, value) {
            $('.js_alert_error').append(`<div>${value}</div>`);
          });
          setTimeout(function () {
            $('.js_alert_error').slideUp();
          }, 3000)
        }

        if(type === 'success') {
          $('.js_alert_success')
            .empty()
            .css('display', 'block')
            .append(`<div>${response.message}</div>`);

          setTimeout(function () {
            $('.js_alert_success').slideUp();
          }, 3000)
        }
      }

      function increaseInvoiceNumber() {
        let invoice_number = document.getElementById('number_sale');
        let y = +invoice_number.value;
        invoice_number.value = y + 1;
      }

      // add new client in sale page
      $(document).on('submit', '#new_client', function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
          type: "POST",
          url: '{{ route('add.user.ajax') }}',
          data: formData,
          success: function (response) {
            showAlert(response, 'success');
            $('.bd-example-modal-lg-client').modal('hide');
            $('#new_client')[0].reset();
            //select2 append new option
            let newOption = new Option(response.user.name, response.user.id, false, false);
            $('#client_id').prepend(newOption).trigger('change');
          },
          error: function (error) {
            showAlert(error, 'error');
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
            $('[data-toggle="tooltip"]').tooltip()
          }
        });
      });

      // Add product to sale by barcode scanner
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
              document.getElementById("product-" + data.product[0].id).click();
            }
          });
        },
        onKeyDetect: function (iKeyCode) {
          // console.log('Pressed: ' + iKeyCode);
        }
      });
    });
  </script>

@endpush
