@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Purchase Return</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('purchase_return.index') }}" class="btn btn-primary font-weight-bolder">
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
                    </span>Purchase Return</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table" id="myTable" width="100%">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Purchase Date</th>
                        <th>Supplier Name</th>
                        <th>Total Quantity</th>
                        <th>Total Price</th>
                        <th style="width: 60px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->invoice_no }}</td>
                            <td>{{ $purchase->purchase_date }}</td>
                            <td>{{ $purchase->supplier->name }}</td>
                            <td>{{ $purchase->total_qty }}</td>
                            <td>{{ $purchase->grand_total }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId-{{ $purchase->id }}">Return
                                </button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="modelId-{{ $purchase->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Purchase Return <span class='text-muted small'>(select product)</span></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        @if ($errors->any())
                                            {!! implode('', $errors->all('<div class="alert text-danger">:message</div>')) !!}
                                        @endif
                                    </div>
                                    <form action="{{ route('purchase_return.store') }}" method="POST" class="form-inline">
                                        @csrf
                                        <div class="modal-body">
                                            @foreach ($purchase->products as $product)
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <div class="form-group p-0 m-0">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <input type="checkbox" name="product_id[]" id="" class="" value="{{ $product->id }}"
                                                                        onchange="enableInput({{ $product->id }},this)">
                                                                    <p for="name">Name :</p>
                                                                    <p for="name">Product Price :</p>
                                                                    <p for="name">Returable :</p>
                                                                </div>
                                                                <div class="col-4 mt-4">
                                                                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                                    <input type="hidden" name="product_price[]" id="product_price-{{ $product->id }}" value="{{ $product->pivot->product_price }}"
                                                                        disabled>
                                                                    <p>{{ $product->name }}</p>
                                                                    <p>{{ $product->pivot->product_price }}
                                                                        /{{ $product->unit->name }}</p>
                                                                    @php
                                                                        $totalQty = 0;
                                                                        foreach ($product->returns as $key => $productReturns) {
                                                                            $totalQty += $productReturns->pivot->qty;
                                                                        }
                                                                        // dd($totalQty);
                                                                    @endphp
                                                                    <p> {{ $productQty = $product->pivot->qty - $totalQty }}</p>200 649 Active
                                                                    2 Cleveland Legros Cleveland Legros

                                                                    Vegetables 200 20 957 Active
                                                                    1 Josephine Wolff Josephine Wolff

                                                                    Prof. Kelvin Yost MD 104 0 200 649 Active
                                                                    2 Cleveland Legros Cleveland Legros

                                                                    Vegetables 200 20 957 Active
                                                                    1 Josephine Wolff Josephine Wolff

                                                                    Prof. Kelvin Yost MD 104 0
                                                                </div>
                                                                <div class="col-4">
                                                                    <input type="number" class="form-control mb-1" name="return_qty[]" id="return_qty-{{ $product->id }}" aria-describedby="helpId"
                                                                        placeholder="Return Quantity" max="{{ $productQty }}" disabled>
                                                                    <input type="text" class="form-control" name="return_note[]" id="return_note-{{ $product->id }}" aria-describedby="helpId"
                                                                        placeholder="Note" disabled>
                                                                    <select class="form-control" name="return_type[]" id="return_type-{{ $product->id }}" disabled>
                                                                        <option selected disabled>select type</option>
                                                                        <option value="damage">Damage Return</option>
                                                                        <option value="normal">Normal Return</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" id="btn-submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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

        function enableInput(id, e) {
            if (e.checked) {
                $('#return_qty-' + id).prop('disabled', false);
                $('#return_note-' + id).prop('disabled', false);
                $('#return_type-' + id).prop('disabled', false);
                $('#product_price-' + id).prop('disabled', false);
            } else {
                $('#return_qty-' + id).prop('disabled', true);
                $('#return_note-' + id).prop('disabled', true);
                $('#return_type-' + id).prop('disabled', true);
                $('#product_price-' + id).prop('disabled', true);
            }
        }
    </script>

    @if (session()->has('errors'))
        <script>
            $('#modelId-' + {{ $purchase->id }}).modal({
                show: true
            });
        </script>
    @endif
@endpush
