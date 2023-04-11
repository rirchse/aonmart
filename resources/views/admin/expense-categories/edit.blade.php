<div class="kt-portlet kt-portlet--mobile">
    <form class="kt-form kt-form--label-right" enctype="multipart/form-data" method="POST" action="{{ route('expense-categories.update', $expenseCategory->id) }}">
        @csrf
        @method('PUT')
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group form-row mb-2">
                        <label for="name">Name : <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm {{ $errors->has('name') ? 'is-invalid' : null }}" value="{{ old('name', $expenseCategory->name) }}" name="name" id="name" required autofocus/>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-row mb-2">
                        <label for="status">Status : <span class="text-danger">*</span></label>
                        <select class="form-control form-control-sm {{ $errors->has('title') ? 'is-invalid' : null }}" name="status" id="status" required>
                            <option value="1" selected>Active</option>
                            <option value="0" {{ old('status', $expenseCategory->status) == 0 ? 'selected' : null }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-row">
                <button type="submit" class="btn btn-sm btn-primary">Update Category</button>
                <a href="{{ route('expense-categories.index') }}" class="ml-2 btn btn-sm btn-default">Cancel</a>
            </div>
        </div>
    </form>
</div>
