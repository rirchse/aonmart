@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">By</span> : {{ $warehouseToStoreRecord->User()->first()->name }}</h3>
            </div>
            <div class="card-title">
                <h3 class="card-label">Store</span> : {{ $warehouseToStoreRecord->store()->first()->name }}</h3>
            </div>
            <div class="card-title">
                <h3 class="card-label">Date</span> : {{ $warehouseToStoreRecord->date }}</h3>
            </div>
        </div>

        <div class="card-body">
            <table id="myTable" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th width="10px">SL</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($warehouseToStoreRecord->Products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" />
@endpush

@push('script')
    <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        })
    </script>
@endpush
