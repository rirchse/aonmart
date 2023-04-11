@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Expense Report</h3>
      </div>
    </div>

    <div class="card-body">
      <div class="p-4">
        <form action="{{ route('report.expense') }}">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group form-row mb-2">
                <label for="dateRangePicker">Date Range:</label>
                <input type="text" class="form-control form-control-sm" value="{{ $dates->range }}" name="date_range" id="dateRangePicker" required/>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Expence By</label>
                <select class="form-control select2" name="user" id="">
                  <option value="">Select User</option>
                  @foreach(App\Models\User::employeesQuery(['*'], App\Library\Utilities::getActiveStore()?->id)->get() as $user)
                  <option value="{{$user->id}}">{{$user->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Expense Purpose</label>
                <select class="form-control select2" name="purpose" id="">
                  <option value="">Select Purpose</option>
                  @for($p = 0; count($purposes) > $p; $p++)
                  <option value="{{$purposes[$p]}}">{{$purposes[$p]}}</option>
                  @endfor
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
          <th>Store</th>
          <th>Added By</th>
          <th>Expense By</th>
          <th>Entry Date</th>
          <th>Expense Date</th>
          <th>Amount</th>
          <th>Purpose</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($expenses as $expense)
          <tr class="text-center">
            <td>{{ $expense->store->name }}</td>
            <td>{{ $expense->addedBy->name }}</td>
            <td>{{ $expense->expenseBy->name }}</td>
            <td>{{ dateFormat($expense->created_at) }}</td>
            <td>{{ dateFormat($expense->date) }}</td>
            <td class="text-right">{{ $expense->amount }}&nbsp;TK</td>
            <td data-toggle="tooltip" title="{{ $expense->purpose }}">{{ substr($expense->purpose, 0, 20) }}...</td>
          </tr>
        @endforeach
        </tbody>
        <tfoot id="expense-report-table-footer">
        <tr class="text-center">
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>Total :</th>
          <th class="text-right"></th>
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
          src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sl-1.3.3/datatables.min.js">
  </script>

  {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
  {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> --}}
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#myTable').DataTable({
        "footerCallback": function (row, data, start, end, display) {
          if (data.length > 0) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
              return typeof i === 'string' ? i.replace(/[\$,'TK''&nbsp;'' ']/g, '') * 1 : typeof i === 'number' ? i :
                0;

            };

            // Total over all page
            let totalAmount = api.column(5).data().reduce(function (a, b) {
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
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
            'month')]
        }
      });
    });
  </script>
@endpush
