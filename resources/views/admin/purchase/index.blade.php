@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Purchase List</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('purchase.create') }}" class="btn btn-primary font-weight-bolder">
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
                    </span>Purchase Now</a>
            </div>
        </div>
        <div class="card-header form-inline">
          <div class="form-row">
            <label class="form-label">Search By Invoice Number: </label>
            <div class="form-group">
              <input type="text" class="form-control" value="" style="margin:0 10px" onkeyup="
              var invbtn = document.getElementById('invBtn');
              if(this.value != ''){
                invbtn.setAttribute('href');
                invbtn.href = '/dashboard/purchase-invoice/'+this.value;
              }else{
                invbtn.removeAttribute('href');
              }
              ">
              <a target="_blank" class="btn btn-primary" id="invBtn">View Invoice</a>
              </div>
          </div>
        </div>

        <div class="card-body">
            <table class="table" id="myTable" width="100%">
                <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Products</th>
                    <th>Total Qty</th>
                    <th>Due</th>
                    <th>Grand Total</th>
                    <th>Update Stock</th>
                    <th style="width: 60px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->formatted_date }}</td>
                        <td>
                            @foreach($purchase->products as $product)
                                <div class="d-flex align-items-center {{ $loop->last ? 'mb-0' : 'mb-2' }}">
                                    <img src="{{ $product->image_url }}" alt="" class="mr-2" loading="lazy" style="width: 30px; max-height: 30px; object-fit: cover">
                                    <p class="mb-0">{{ $product->name }} ({{$product->pivot->qty}})</p>
                                </div>
                            @endforeach
                        </td>
                        <td>{{ $purchase->total_qty }}</td>
                        <td>{{ $purchase->due_amount }}</td>
                        <td>{{ $purchase->grand_total }}</td>
                        <td>
                            <span class="switch switch-brand">
                                <form action="{{ route('purchase.stock', $purchase->id) }}" class="d-inline-block">
                                    <label>
                                      <input type="checkbox" {{ $purchase->is_stocked ? 'checked disabled' : '' }} name="select" onclick="confirm('Are you sure to update stock for this purchase?') ? this.form.submit() : this.checked = false"/>
                                        <span></span>
                                    </label>
                                </form>
                            </span>
                        </td>
                        <td nowrap='nowrap'>
                            @if ($purchase->due_amount > 0)
                                <button type="button" class="btn btn-sm btn-light-danger btn-hover-danger text-dark" data-action="{{ route('purchase.paydue') }}" data-toggle="modal"
                                        onclick="modal({{ $purchase->id }},{{ $purchase->due_amount }}, this)">Pay Due
                                </button>
                            @endif
                            <a href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-sm btn-icon btn-clean text-hover-primary"><i class="la la-eye"></i></a>
                            @if (!$purchase->is_stocked)
                                <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-sm btn-icon btn-clean text-hover-primary"><i class="la la-edit icon-lg"></i></a>
                                <form method="post" action="{{ route('purchase.destroy', $purchase->id) }}" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm btn-icon btn-clean text-hover-danger">
                                        <i class="la la-trash icon-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg-client" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Pay Due</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="new_client" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <label for="" class="col-form-label mr-4">Amount</label>
                                <input type="number" name="amount" id="" class="form-control amount" value="{{ old('amount') }}" required>
                            </div>
                            <input type="hidden" name="id" id="purchase_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                        <button type="submit" class="btn btn-primary" id="button">Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function payDue(id) {
            $.ajax({
                type: "POST",
                url: '{{ route('purchase.paydue') }}',
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    alert(response)
                    $('#pay_' + id).hide()
                    $('.pay').removeClass('d-none').html(response).hide(2000);
                },
                error: function (error) {
                    $('.pay').removeClass('d-none').html(error).hide(2000);
                }
            });
        }

        function modal(id, due, e) {
            let url = e.getAttribute('data-action');
            $('.bd-example-modal-lg-client').modal('show');
            $('.amount').attr('id', 'amount-' + id).attr('max', due).val(due);
            $('#purchase_id').attr('value', id);
            $('#new_client').attr('action', url);
        }
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
