<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ companyInfo()->name }} | {{ $sale->number_sale }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    <style>
        .card {
            border: none;
        }

        .card-header {
            background-color: #fff;
            border-bottom: none
        }

        h3 {
            font-size: 20px
        }

        .table.table-bordered thead th,
        .table.table-bordered thead td,
        .table.table-bordered thead th,
        .table.table-bordered tbody td {
            border: 1px solid #e8e7e6 !important;
        }

        .table.table-bordered,
        .table.table-bordered tfoot th,
        .table.table-bordered tfoot td {
            border: none !important;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }
    </style>
</head>

<body>
<div style="width: 800px; margin: 20px auto;" class="no-print">
    <div class="mb-3">
        <a href="{{ route('sale.index') }}" class="btn btn-primary">Back</a>
        <button onclick="printDiv()" class="btn btn-primary">Print</button>
    </div>
</div>

<div class="card" id="print" style="width: 800px; margin: 20px auto;">
    <div class="card-header">
        <table style="width: 100%">
            <tr>
                <td>
                    <div class="text-left">
                        <h4 class="mt-2 mt-md-0">Invoice : {{ $sale->number_sale }}</h4>
                        <span>{{ $sale->created_at->format('F d, Y h:m:s A') }}</span>
                        <br>
                        <p class="mt-2 mb-0">Customer: <b>{{ $user->name }}</b></p>
                    </div>
                </td>
                <td>
                    <div class="text-right">
                        <h3 class="mb-0">{{ companyInfo()->name }}</h3>
                        <span>{{ $sale->store->name }}</span><br>
                        <span>{{ $sale->store->location }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table-bordered" style="margin-bottom: 0;">
                <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 500px;">Product</th>
                    <th style="width: 100px;text-align:center;">Price</th>
                    <th style="width: 100px;text-align:center;">Quantity</th>
                    <th style="width: 100px;text-align:center;">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($saleProducts as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td style="text-align:center;">৳{{ $product->pivot->product_price }}</td>
                        <td style="text-align:center;">{{ $product->pivot->quantity }} {{ $product->unit->name }}</td>
                        <td style="text-align:center;">৳{{ $product->pivot->total_price }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Sub total </strong></td>
                    <td style="text-align:center;">{{ $sale->total }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Discount </strong></td>
                    <td style="text-align:center;">{{ $sale->discount }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total Payable </strong></td>
                    <td style="text-align:center;">{{ $sale->total_amount }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Paid </strong></td>
                    <td style="text-align:center;">{{ $sale->paid }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Due </strong></td>
                    <td style="text-align:center;">{{ $sale->due }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    function printDiv() {
        window.print()
    }
</script>
</body>

</html>
