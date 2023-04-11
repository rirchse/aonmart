@extends('layouts.default')

@push('style')
    <style>
        @media print {
            .p-4 {
                padding: 0 !important;
            }
        }
    </style>
@endpush

@section('content')
<div class="card card-custom">
    <div class="card-body">
        <div id="print">
            @forelse($products as $key => $product)
                @for($i = 0; $i < $quantities[$key]; $i++)
                    @php
                        $item = explode('|', $product);
                    @endphp
                    <table style="display: inline-block;margin-bottom: 6px; margin-right: 6px">
                        <tbody>
                        <tr>
                            <td style="text-align: center;width: 15px;font-size: 13px"><span>{{ $item[1] }}</span></td>
                        </tr>
                        <tr>
                            <td>
                                <svg class="barcode"
                                     jsbarcode-height="50"
                                     jsbarcode-width="1"
                                     jsbarcode-textmargin="0"
                                     jsbarcode-margin="0"
                                     jsbarcode-value="{{ $item[2] }}">
                                </svg>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;width: 15px;font-size: 14px;"><span>৳ {{ $item[3] }}</span></td>
                        </tr>
                        </tbody>
                    </table>
                @endfor
            @empty
                <h1>No Product Selected!</h1>
            @endforelse
        </div>
        <div class="row no-print">
            <div class="col-md-12">
                <a href="{{ route('product.barcode.print.list') }}" class="btn btn-primary">Back</a>
                <button onclick="printDiv()" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script src="{{ asset('assets/plugins/JsBarcode.min.js') }}"></script>

    <script>
        JsBarcode(".barcode").init();

        function printDiv() {
            window.print();
        }
    </script>
@endpush

