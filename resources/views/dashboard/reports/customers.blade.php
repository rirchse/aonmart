@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Customers Report</h3>
      </div>
    </div>

    <div class="card-body">
    <div class="p-4">
        <form action="{{ route('report.customer') }}">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group form-row mb-2">
                <label for="dateRangePicker">Shopping Date:</label>
                <input type="text" class="form-control form-control-sm" value="{{ $dates->range }}" name="date_range" id="dateRangePicker" required/>
              </div>
            </div>
            <!-- <div class="col-md-3">
              <div class="form-group">
                <label for="">Shopping Amount</label>
                <select class="form-control" name="shopping_amount" id="">
                  <option value="">Select Amount</option>
                  <option value="0-500">0-500Tk</option>
                  <option value="500-1000">500-1,000Tk</option>
                  <option value="1000-5000">1,000-5,000Tk</option>
                  <option value="5000-10000">5,000-10,000Tk</option>
                  <option value="10000<">Max 10,000Tk</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Due Amount</label>
                <select class="form-control" name="due_amount" id="">
                  <option value="">Select Amount</option>
                  <option value="0-500">0-500Tk</option>
                  <option value="500-1000">500-1,000Tk</option>
                  <option value="1000-5000">1,000-5,000Tk</option>
                  <option value="5000-10000">5,000-10,000Tk</option>
                  <option value="10000<">Max 10,000Tk</option>
                </select>
              </div>
            </div> -->
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Most Selling Product</label>
                <select class="form-control select2" name="most_selling" id="">
                  <option value="">Select Item</option>
                  @foreach($products as $product)
                  <option value="{{$product->id}}">{{$product->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <!-- <div class="col-md-3">
              <div class="form-group">
                <label for="">Order From</label>
                <select class="form-control" name="order_from" id="">
                  <option value="">Select One</option>
                  <option value="1">Order From Web</option>
                  <option value="2">Order From App</option>
                </select>
              </div>
            </div> -->
          </div>
          <div class="form-row">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
          </div>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table" id="data-table">
          <thead>
          <tr>
            <th style="width: 10px">SL</th>
            <th>Name</th>
            <th>Email</th>
            <th class="text-right">Shopping Amount</th>
            <th class="text-right">Due Amount</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach($customers as $customer)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $customer->name }}</td>
              <td>{{ $customer->email }}</td>
              <td
                class="text-right">{{ $customer->orders_summary->total_ordered_amount + $customer->sales_summary->total_sales_amount }}
                TK
              </td>
              <td class="text-right">{{ $customer->sales_summary->total_sales_due_amount }} TK</td>
              <td><a target="_blank" href="{{route('report.buyingHistory', $customer->id)}}"><i class="fa fa-eye"></i></a></td>
            </tr>
          @endforeach
          </tbody>
          <tfoot id="customer-report-table-footer">
          <tr class="text-center">
            <th></th>
            <th></th>
            <th>Total :</th>
            <th class="text-right"></th>
            <th class="text-right"></th>
          </tr>
          </tfoot>
        </table>
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
      $('#data-table').DataTable({
        "footerCallback": function (row, data, start, end, display) {
          if (data.length > 0) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
              return typeof i === 'string' ? i.replace(/[\$,'TK''&nbsp;'' ']/g, '') * 1 : typeof i === 'number' ? i :
                0;

            };

            // Total over all page
            let totalAmount = api.column(3).data().reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);

            // Total over all page
            let totalDue = api.column(4).data().reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);

            // Number formatting
            let format = function number_format(number) {
              return number.toString().replace(/./g, function (c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
              });
            }

            // Update footer
            $(api.column(3).footer()).html(format(totalAmount) + ' TK');
            $(api.column(4).footer()).html(format(totalDue) + ' TK');
          } else {
            $('#customer-report-table-footer').remove();
          }
        }
      });
    })
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
