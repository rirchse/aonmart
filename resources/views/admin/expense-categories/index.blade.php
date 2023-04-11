@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h1 class="card-label">Expense Categories </h1><br>
      </div>
      <div class="card-toolbar">
      </div>
    </div>
    <div class="card-body">
      <div class="mb-7">
        @isset($expenseCategory)
          @include('admin.expense-categories.edit')
        @else
          @include('admin.expense-categories.create')
        @endisset
      </div>
      <table class="table" id="myTable" width="100%">
        <thead>
          <tr>
            <th style="width: 10px">SL</th>
            <th>Name</th>
            <th>Status</th>
            <th>Approval</th>
            <th style="width: 60px">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($expenseCategories as $expenseCategory)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $expenseCategory->name }}</td>
              <td>
                @if ($expenseCategory->status)
                  <span class="label font-weight-bold label-lg label-light-success label-inline">Active</span>
                @else
                  <span class="label font-weight-bold label-lg label-light-danger label-inline">Inactive</span>
                @endif
              </td>
              <td>
                @if ($expenseCategory->approved)
                  <span class="label font-weight-bold label-lg label-light-success label-inline">Approved</span>
                @else
                  <span class="label font-weight-bold label-lg label-light-warning label-inline">Pending</span>
                @endif
              </td>
              <td>
                @if (!$expenseCategory->approved &&
      auth()->user()->hasRole('Super Admin'))
                  <a href="{{ route('expense-categories.approve', $expenseCategory->id) }}" onclick="return confirm('Are you sure to approve this category?');" class="btn btn-sm text-hover-success btn-clean btn-icon text-dark" title="Approve"><i class="fas fa-check"></i></a>
                @endif
                <a href="{{ route('expense-categories.edit', $expenseCategory->id) }}" class="btn btn-sm  btn-clean btn-icon"><i class="la la-edit icon-lg"></i></a>
                <form method="post" action="{{ route('expense-categories.destroy', $expenseCategory->id) }}" class="d-inline-block">

                  @csrf
                  @method('DELETE')
                  <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm text-hover-danger btn-clean btn-icon"><i class="la la-trash icon-lg"></i></button>
                </form>
              </td>
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
