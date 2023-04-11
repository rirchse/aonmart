
@extends('layouts.default')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">All Sale Return</h3>
        </div>
        <div class="card-toolbar">
            @livewire('sale-return', compact('store'))
        </div>
    </div>

    <div class="card-body">
        <table class="table" id="myTable" width="100%">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Return Quantity</th>
                    <th>Return Amount</th>
                    <th style="width: 60px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($saleReturns as $saleReturn)
                <tr>
                    <td>{{ $saleReturn->Sale->number_sale }}</td>
                    <td>{{ $saleReturn->total_qty }}</td>
                    <td>{{ $saleReturn->return_amount }}</td>
                    <td>
                        <a href="#" class="btn btn-primary">Details</a>
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
