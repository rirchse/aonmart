@extends('layouts.default')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">Add New Expense</h3>
        </div>
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{session('error')}}
        </div>
        @endif

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
        <form class="form" enctype="multipart/form-data" method="POST" action="{{ route('expenses.store') }}">
            @csrf
            <div class="kt-portlet__body">

                @if(!auth()->user()->store)
                    <div class="form-group row">
                        <label for="store_id" class="col-md-2 col-form-label"><b>{{ __('Select Store') }}
                                <span class="text-danger">*</span></b></label>
                        <div class="col-lg-8">
                            <select name="store_id" id="store_id" class="custom-select  @error('store_id') is-invalid @enderror">
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id', session('store_id')) == $store->id ? 'selected' : null }}>{{ $store->name }}</option>
                                @endforeach
                            </select>
                            @error('store_id')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <span class="form-text text-muted">Please select store.</span>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="store_id" id="store_id" value="{{ auth()->user()->store_id }}">
                @endif

                <div class="form-group row">
                    <label for="expense_by" class="col-md-2 col-form-label"><b>{{ __('Expense By') }}
                            <span class="text-danger">*</span></b></label>
                    <div class="col-lg-8">
                        <select name="expense_by" id="expense_by" class="custom-select  @error('expense_by') is-invalid @enderror"></select>
                        @error('expense_by')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <span class="form-text text-muted">Please select employee who made the expense.</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="expense_category_id" class="col-md-2 col-form-label"><b>{{ __('Expense Category') }}
                            <span class="text-danger">*</span></b></label>
                    <div class="col-lg-8">
                        <select name="expense_category_id" id="expense_category_id" class="custom-select  @error('expense_category_id') is-invalid @enderror">
                            <option value="">Select Expense Category</option>
                            @foreach($expenseCategories as $expenseCategory)
                                <option value="{{ $expenseCategory->id }}" {{ old('expense_category_id') == $expenseCategory->id ? 'selected' : null }}>{{ $expenseCategory->name }}</option>
                            @endforeach
                        </select>
                        @error('expense_category_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <span class="form-text text-muted">Please select expense category.</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="date"><b>{{ __('Expense Date') }}
                            <span class="text-danger">*</span>
                        </b></label>
                    <div class="col-lg-8">
                        <input name="date" id="date" value="{{ old('date') }}" placeholder="Ex: " type="date" class="form-control  @error('date') is-invalid @enderror">
                        @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <span class="form-text text-muted">Please select expense date.</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="amount"><b>{{ __('Expense Amount') }}
                            <span class="text-danger">*</span>
                        </b></label>
                    <div class="col-lg-8">
                        <input name="amount" id="amount" value="{{ old('amount') }}" placeholder="Ex: " type="text" class="form-control  @error('amount') is-invalid @enderror">
                        @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <span class="form-text text-muted">Please enter expense amount.</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="purpose"><b>{{ __('Expense Purpose') }}
                            <span class="text-danger">*</span>
                        </b></label>
                    <div class="col-lg-8">
                        <textarea class="form-control @error('purpose') is-invalid @enderror" name="purpose" placeholder="Enter a expense purpose..." rows="4">{{ old('purpose') }}</textarea>
                        @error('purpose')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <span class="form-text text-muted">Please enter expense purpose within text length range 100 and 1000.</span>
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
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')
    <script>
        $(document).ready(function () {
            const store_id_field = $('#store_id');
            const expense_by_field = $('#expense_by');
            const current_employee = '{{ old('expense_by') }}';
            store_id_field.on('change', updateEmployeesList);

            function updateEmployeesList() {
                expense_by_field.empty();
                let url = '{{ url('dashboard/getStoreEmployees') }}';
                let store_id = store_id_field.val();
                $.get(url + '/' + store_id, function (response) {
                    $.each(response, function (key, value) {
                        expense_by_field.append('<option value="' + value.id + '" ' + (current_employee == value.id ? "selected" : "") + '>' + value.name + '</ooption>');
                    });
                });
            }

            if (store_id_field.val() != null) {
                updateEmployeesList();
            }
        });
    </script>
@endpush
