<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <style>
        body {
            color: #000;
            font-size: 14px;
        }

        #wrapper {
            max-width: 480px;
        ;
            margin: 0 auto;
            padding-top: 20px;
        }

        .btn {
            border-radius: 0;
            margin-bottom: 5px;
        }

        .bootbox .modal-footer {
            border-top: 0;
            text-align: center;
        }

        h3 {
            margin: 5px 0;
        }

        .order_barcodes img {
            float: none !important;
            margin-top: 5px;
        }

        .table th,
        .table td {
            padding: 0.25em !important;
        }

        @media print {
            .no-print {
                display: none;
            }

            #wrapper {
                max-width: 480px;
                width: 100%;
                min-width: 250px;
                margin: 0 auto;
            }

            .no-border {
                border: none !important;
            }

            .border-bottom {
                border-bottom: 1px solid #ddd !important;
            }

            .border-top {
                border-top: 1px solid #ddd !important;
            }

            table tfoot {
                display: table-row-group;
            }
        }
    </style>
</head>
<body>

<div id="wrapper">
    <div id="receiptData">

        <div id="receipt-data">
            <div class="text-center">
                <h3>{{ $settings->name }}</h3>
                <p>
                    {{ $sale->store->name }} <br>
                    {{ $sale->store->location }}
                    Invoice No: {{ $sale->number_sale }} <br>
                </p>
            </div>
            <p>
                Date: {{ $sale->created_at->format('F d, Y') }}<br>
                Sales Associate: {{ auth()->user()->name }}<br>
                Customer: {{ $sale->User->name }}
            </p>
            <div style="clear:both;"></div>
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center" width="10">Price</th>
                    <th class="text-center" width="10">Qty</th>
                    <th class="text-center" width="10">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sale->Products()->get() as $saleProduct )
                    <tr>
                        <td class="no-border border-bottom">
                            <small>{{ $loop->iteration }}:&nbsp;{{ $saleProduct->name }} </small>
                        </td>
                        <td class="no-border border-bottom text-end">{{ $saleProduct->pivot->product_price }}</td>
                        <td class="no-border border-bottom text-end">{{ $saleProduct->pivot->quantity }}</td>
                        <td class="no-border border-bottom text-end">{{ $saleProduct->pivot->total_price }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="4">Total Items: {{ str_pad($sale->Products()->count(), 2, 0, STR_PAD_LEFT) }}</th>
                </tr>
                <tr>
                    <th colspan="3">Sub Total</th>
                    <th class="text-end" width="10">৳{{ $sale->total }}</th>
                </tr>
                <tr>
                    <th colspan="3">Discount</th>
                    <th class="text-end" width="10">৳{{ $sale->discount }}</th>
                </tr>
                <tr>
                    <th colspan="3">Total Payable</th>
                    <th class="text-end" width="10">৳{{ $sale->total_amount }}</th>
                </tr>
                <tr>
                    <th colspan="3">Paid</th>
                    <th class="text-end" width="10">৳{{ $sale->paid }}</th>
                </tr>
                <tr>
                    <th colspan="3">Due</th>
                    <th class="text-end" width="10">৳{{ $sale->due }}</th>
                </tr>
                </tfoot>
            </table>
            <p class="text-center"> Thank you for visiting us!</p>
        </div>
        <div style="clear:both;"></div>
    </div>

    <script>
        window.addEventListener('afterprint', function () {
            window.close();
        });
    </script>

</div>

</body>
</html>
