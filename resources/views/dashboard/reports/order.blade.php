@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Order Report</h3>
      </div>
    </div>

    <div class="card-body">
      <div class="p-4">
        <form action="{{ route('report.order') }}">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group form-row mb-2">
                <label for="dateRangePicker">Date Range:</label>
                <input type="text" class="form-control form-control-sm" value="{{ $dates->range }}" name="date_range" id="dateRangePicker" required/>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Invoice</label>
                <select class="form-control select2" name="invoice" id="">
                  <option value="">Select Invoice No</option>
                  @for($i = 0; count($invoices) > $i; $i++)
                  <option value="{{$invoices[$i]}}">{{$invoices[$i]}}</option>
                  @endfor
                </select>
              </div>
            </div>
            <!-- <div class="col-md-3">
              <div class="form-group">
                <label for="">Customer</label>
                <select class="form-control" name="customer" id="">
                  <option value="">Select Customer</option>
                  @foreach(App\Models\User::getCustomers() as $customer)
                  <option value="{{$customer->id}}">{{$customer->name}}</option>
                  @endforeach
                </select>
              </div>
            </div> -->
            {{-- <div class="col-md-3">
              <div class="form-group">
                <label for="">Seller</label>
                <select class="form-control select2" name="seller" id="">
                <option value="">Select Employee</option>
                @foreach(App\Models\User::employeesQuery(['*'], App\Library\Utilities::getActiveStore()?->id)->get() as $employee)
                  <option value="{{$employee->id}}">{{$employee->name}}</option>
                  @endforeach
                </select>
              </div>
            </div> --}}
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Product</label>
                <select class="form-control select2" name="product" id="">
                  <option value="">Select Item</option>
                  @foreach($products as $product)
                  <option value="{{$product->product_id}}">{{$product->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="form-row">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
          </div>
        </form>
      </div>

      <table class="table" id="myTable" width="100%">
        <thead>
        <tr class="text-center">
          <th>Invoice No</th>
          <th>Datetime</th>
          <th>Item Name</th>
          <th>Price</th>
          <th>Qty.</th>
          <th>Total (Tk.)</th>
          <th style="width: 60px">Action</th>
        </tr>
        </thead>
        <tbody>
          <?php $Gtotal = $totalQty = 0; ?>
        @foreach($orders as $sale)
          <tr class="text-center">
            <td>{{ $sale->order_no }}</td>
            <td>{{ datetimeFormat($sale->created_at) }}</td>
            <td>{{ $sale->name }}</td>
            <td>{{ $sale->price }}</td>
            <td>{{ $sale->qty }}</td>
            <td>{{ $sale->price * $sale->qty }}</td>
            <td>
              <a target="_blank" href="{{ route('order.show', $sale->id) }}" class="btn btn-outline-dark btn btn-sm btn-bold">View</a>
            </td>
          </tr>
          <?php $Gtotal += $sale->price*$sale->qty; $totalQty += $sale->qty; ?>
        @endforeach
        </tbody>
        <tfoot id="expense-report-table-footer">
        <tr class="text-center">
          <th></th>
          <th></th>
          <th>Total =</th>
          <th></th>
          <th>{{$totalQty}} Pcs</th>
          <th>{{$Gtotal}} Tk.</th>
          <th></th>
        </tr>
        </tfoot>
      </table>
    </div>
  </div>

@endsection

@push('style')
  <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sl-1.3.3/datatables.min.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endpush

@push('script')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript"
          src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sl-1.3.3/datatables.min.js"></script>

  {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
  {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> --}}
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#myTable').DataTable({
        "footerCallback": function (row, data, start, end, display) {
          if (data.length > 0) {
            let api = this.api(), data;

            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
              return typeof i === 'string' ? i.replace(/[\$,'TK'' ']/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            let totalAmount = api.column(5).data().reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);
            let totalPayable = api.column(6).data().reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);
            let totalPaid = api.column(7).data().reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);
            let totalDue = api.column(8).data().reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);

            // Number formatting
            let format = function number_format(number) {
              return number.toString().replace(/./g, function (c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
              });
            }

            // Update footer
            $(api.column(5).footer()).html(format(totalAmount) + ' TK');
            $(api.column(6).footer()).html(format(totalPayable) + ' TK');
            $(api.column(7).footer()).html(format(totalPaid) + ' TK');
            $(api.column(8).footer()).html(format(totalDue) + ' TK');
          } else {
            $('#expense-report-table-footer').remove();
          }
        }
      });
    });
  </script>

  <script>
    $(function () {


      $('#dateRangePicker').daterangepicker({
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      });

    });
  </script>
@endpush
