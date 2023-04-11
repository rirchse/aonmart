@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Purchase History By (Supplier: {{$supplier->name}}) </h3>
            </div>
        </div>

        <div class="card-body">
            <table class="table" id="myTable" width="100%">
                <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <!-- <th>Products</th> -->
                    <th>Total Qty</th>
                    <th>Due</th>
                    <th>Grand Total</th>
                    <th style="width: 60px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->formatted_date }}</td>
                        <td>{{ $purchase->total_qty }}</td>
                        <td>{{ $purchase->due_amount }}</td>
                        <td>{{ $purchase->grand_total }}</td>
                        <td nowrap='nowrap'>
                            <a target="_blank" href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-sm btn-icon btn-clean text-hover-primary"><i class="fa fa-eye"></i></a>
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
