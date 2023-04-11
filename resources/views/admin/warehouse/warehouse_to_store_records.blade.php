@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Werehouse to store records</h3>
            </div>
        </div>

        <div class="card-body">
            <form class="form" enctype="multipart/form-data" method="post" action="{{ route('warehouse.records') }}">
                @csrf
                <div class="form-group row">
                    <label for="start_date" class="col-md-2 col-form-label"><b>{{ __('Start Date') }} <span class="text-danger">*</span></b></label>
                    <div class="col-md-3">
                        <input name="start_date" id="start_date" value="{{ old('start_date', $start_date) }}" placeholder="Ex: Smith Jones" type="date"
                            class="form-control  @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <label class="col-md-2 col-form-label" for="end_date"><b>{{ __('End Date') }} <span class="text-danger">*</span></b></label>
                    <div class="col-lg-3">
                        <input name="end_date" id="end_date" value="{{ old('end_date', $end_date) }}" placeholder="Ex: Smith Jones" type="date" class="form-control  @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <table id="myTable" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th width="10px">SL</th>
                        <th>Store Name</th>
                        <th>Transaction By</th>
                        <th>Date</th>
                        <th width="40px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($warehouseToStoreRecords as $warehouseToStoreRecord)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $warehouseToStoreRecord->store->name }}</td>
                            <td>{{ $warehouseToStoreRecord->user->name }}</td>
                            <td>{{ $warehouseToStoreRecord->date }}</td>
                            <td>
                                <a href="{{ route('warehouse.records.view', $warehouseToStoreRecord->id) }}" class="btn btn-sm btn-info">Details</a>
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
