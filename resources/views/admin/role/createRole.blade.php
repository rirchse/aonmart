@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        @if(request()->routeIs('role.edit'))
          <h3 class="card-label">Edit Role</h3>
        @else
          <h3 class="card-label">Add New Role</h3>
        @endif
      </div>
    </div>
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if(request()->routeIs('role.edit'))
        <form action="{{ route('role.update', $role->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="role">Role Name</label>
                <input type="text" class="form-control" id="role" name="name" value="{{ $role->name }}" {{$restricted == true ? "disabled" : ""}}>
              </div>
            </div>
            @if($restricted == false)
              <input type="hidden" name="restricted" value="false">
            @else
              <input type="hidden" name="restricted" value="true">
            @endif
            <div class="col-md-8">
              <div class="form-group">
                <label for="permission">Select Permission</label>
                <select class="form-control select2_multiple" multiple id="permission" name="permissions[]" style="width: 100%">
                  @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}" {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                      {{ $permission->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
      @else
        <form action="{{ route('role.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="role">Role Name</label>
                <input type="text" class="form-control" id="role" name="name">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="permission">Select Permission</label>
                <select class="form-control select2_multiple" multiple id="permission" name="permissions[]" style="width: 100%">
                  @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">save</button>
        </form>
      @endif
    </div>
  </div>

  <div class="card card-custom mt-6">
    <div class="card-body">
      <table class="table" id="myTable" width="100%">
        <thead>
          <tr>
            <th>Role</th>
            <th>Permissions</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($roles as $role)
            <tr>
              <td>{{ $role->name }}</td>
              <td>
                <div>
                  @foreach ($role->getPermissionNames() as $item)
                    <span class="label font-weight-bold label-lg label-secondary label-inline mb-1">{{ $item }}</span>
                  @endforeach
                </div>
              </td>
              <td nowrap="nowrap">
                <a href="{{ route('role.edit', $role->id) }}" class="btn btn-sm btn-clean btn-icon text-dark"><i class="la la-edit icon-lg"></i></a>
                <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="d-inline-block">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm text-hover-danger btn-clean btn-icon"><i class="la la-trash-alt"></i></button>
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
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"/>
@endpush

@push('script')
  <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    })
  </script>
@endpush
