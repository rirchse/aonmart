@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Buying History By (Customer: {{$user->name}}) </h3>
            </div>
        </div>

        <div class="card-body">
            <table class="table" id="myTable" width="100%">
                <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Due</th>
                    <th>Grand Total</th>
                    <th style="width: 60px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->number_sale }}</td>
                        <td>{{datetimeFormat($sale->created_at)}}</td>
                        <td>{{ $sale->total }}</td>
                        <td>{{ $sale->discount }}</td>
                        <td>{{ $sale->due }}</td>
                        <td>{{ $sale->total_amount }}</td>
                        <td nowrap='nowrap'>
                            <a target="_blank" href="{{ route('sale.show', $sale->id) }}" class="btn btn-sm btn-icon btn-clean text-hover-primary"><i class="fa fa-eye"></i></a>
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
