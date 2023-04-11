@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Suppliers Report</h3>
      </div>
    </div>

    <div class="card-body">
    <form action="{{route('report.supplier')}}">
      <div class="row">
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
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Due Amount</label>
          <select class="form-control select2" name="due" id="">
            <option value="">Select Amount</option>
            <option value="500">0-500Tk</option>
            <option value="1000">500-1,000Tk</option>
            <option value="5000">1,000-5,000Tk</option>
            <option value="9999">5,000-10,000Tk</option>
            <option value="10000">Max 10,000Tk</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        {{-- {{dd( $suppliers_names )}} --}}
        <div class="form-group">
          <label for="">Supplier Name</label>
          <select class="form-control select2" name="supplier" id="">
            <option value="">Select Supplier Name</option>
            @foreach($suppliers_names as $supplier)
            <option value="{{$supplier['id']}}">{{$supplier['name']}}</option>
            @endforeach
          </select>
        </div>
      </div>
      </div>
      <div class="form-row">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
      </div>
    </form>
    <br>
      <div class="table-responsive">
        <table class="table" id="data-table">
          <thead>
          <tr>
            <th style="width: 10px">SL</th>
            <th>Name</th>
            <th>Email</th>
            <th class="text-right">Purchase Amount</th>
            <th class="text-right">Due Amount</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach($suppliers as $supplier)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $supplier->name }}</td>
              <td>{{ $supplier->email }}</td>
              <td class="text-right">{{ $supplier->purchases_summary->total_purchases_amount }} TK</td>
              <td class="text-right">{{ $supplier->purchases_summary->total_purchases_due_amount }} TK</td>
              <td><a target="_blank" title="Purchase History" href="{{route('report.purchaseHistory', $supplier->id)}}"><i class="fa fa-eye"></i></a></td>
            </tr>
          @endforeach
          </tbody>
          <tfoot id="supplier-report-table-footer">
          <tr class="text-center">
            <th></th>
            <th></th>
            <th>Total :</th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th></th>
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
            $(api.column(3).footer()).html(format(totalAmount.toFixed(2)) + ' TK');
            $(api.column(4).footer()).html(format(totalDue.toFixed(2)) + ' TK');
          } else {
            $('#supplier-report-table-footer').remove();
          }
        }
      });
    })
  </script>
@endpush
