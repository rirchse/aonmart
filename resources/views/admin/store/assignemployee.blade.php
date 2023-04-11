@extends('layouts.default')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Assign Employee To Store</h3>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="card-body">
            <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{ route('store.storeemployee') }}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row  justify-content-center">
                        <label for="name" class="col-md-2 col-form-label"><b>{{ __('Store Name') }} <span class="text-danger">*</span></b></label>
                        <div class="col-md-8">
                            <select class="form-control select2" name="store_id" value="{{ old('store_id') }}" id="getemployee">
                                <option>--- Select Store ---</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row  justify-content-center">
                        <label class="col-md-2 col-form-label" for="status"><b>{{ __('Employee Name') }} <span class="text-danger">*</span></b></label>
                        <div class="col-lg-8">
                            <select class="form-control select2" name="user_id[]" multiple value="{{ old('store_id') }}">
                                <option value="">-- Select Store --</option>
                                  @foreach ($users as $employee)
                                      <option value="{{ $employee->id }}"> {{ $employee->name }} ({{ $employee->roles->first()->name }}) {{ $employee->store_id ? ' - ' . $employee->store?->name : '' }}</option>
                                  @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- <button type="reset" class="btn btn-danger">Delete</button> -->
                            </div>
                            <div class="col-lg-6 kt-align-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('unit.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Employee List</h3>
            </div>

        </div>

        <div class="card-body">
            <div id="employee"></div>
        </div>
    </div>

@endsection

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"/>
@endpush

@push('script')
    <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        $(window).on('load', function(e) {
            var storeId = $('#getemployee').children('option').eq(0).val();
            ajax(storeId);
        });

        $(document).ready(function() {
            $('#myTable').DataTable();
            $('#getemployee').on('change', function(e) {
                var id = e.target.value;
                ajax(id);
            });
        });

        function ajax(id) {
            var id = id;
            $.ajax({
                url: "{{ route('store.getemployee') }}",
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                },
                context: document.body
            }).done(function(data) {
                var table = $('#employee');
                table.html(data);
                $('#myTable').DataTable();
            })
        }
    </script>
@endpush
