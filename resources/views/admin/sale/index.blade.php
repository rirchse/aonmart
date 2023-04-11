@extends('layouts.default')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">All Sale </h3>
        </div>
        <div class="card-toolbar">
            @livewire('sale-return', compact('store'))
            <a href="{{route('sale.create')}}" class="btn btn-primary font-weight-bolder ml-2">
                <span class="svg-icon svg-icon-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                            <path
                                d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                </span>
                Sell Now
            </a>
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
            invbtn.href = '/dashboard/sale-invoice/'+this.value;
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
                    <th>Invoice No</th>
                    <th>Datetime</th>
                    <th>Customer</th>
                    <th>Sub Total</th>
                    <th>Discount</th>
                    <th>Total Payable</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th style="width: 60px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->number_sale }}</td>
                    <td>{{ datetimeFormat($sale->created_at) }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ $sale->total }}</td>
                    <td>{{ $sale->discount }}</td>
                    @if ($sale->status == "paid")
                    <td><span class="badge bg-success">{{ $sale->total_amount }}</span></td>
                    @endif
                    @if ($sale->status == "due")
                    <td><span class="badge bg-warning">{{ $sale->total_amount }}</span></td>
                    @endif
                    <td>{{ $sale->paid }}</td>
                    <td>{{ $sale->due }}</td>
                    <td>
                        @if ($sale->due != 0)
                        <button type="button" class="btn btn-outline-primary btn btn-sm btn-bold" data-action="{{ route('sale.payment.due') }}" data-toggle="modal"
                            onclick="modal({{ $sale->id }},{{ $sale->due  }},this)">Pay Due
                        </button>
                        @endif
                        <a href="{{ route('sale.show', $sale->id) }}" class="btn btn-outline-dark btn btn-sm btn-bold">View</a>
                        @canany(['sell.all','sell.edit'])
                        <a href="{{ route('sale.edit', $sale->id) }}" class="btn-label-brand btn btn-sm btn-bold">Edit</a>
                        @endcan
                        @canany(['sell.all', 'sell.delete'])
                        <form method="post" class="d-inline-block" action="{{ route('sale.destroy', $sale->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn-sm btn btn-danger">Delete</button>
                        </form>
                        @endcan
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
                    <div class="form-group form-row">
                        <div class="col-3">
                            <label>Amount</label>
                        </div>
                        <div class="col-9">
                            <input max="20" type="number" name="amount" id="" class="form-control amount" value="{{ old('name') }}">
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

        function modal(id, due, e) {
            let amount = $('.amount');
            let url = e.getAttribute('data-action');

            $('.bd-example-modal-lg-client').modal('show');
            amount.attr({
                'id': 'amount-' + id,
                'max': due
            });
            $('#purchase_id').attr('value', id);
            $('#new_client').attr('action', url);
        }
    </script>
@endpush
