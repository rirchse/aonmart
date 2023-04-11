@extends('layouts.default')

@section('content')
  <div class="row">
    <div class="col-md-5">
      <div class="card card-custom">
        <div class="card-header">
          <div class="card-title">
            <h3 class="card-label">Assign Role To User</h3>
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
          <form action="{{ route('store.assign') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="user">Select User</label>
              <select class="form-control select2" id="user" name="user" style="width: 100%">
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="role">Select Role</label>
              <select class="form-control select2" multiple id="role" name="roles[]" style="width: 100%" multiple>
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-primary">save</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card card-custom">
        <div class="card-body">
          <table class="table " id="myTable" width="100%">
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                @if (count($user->roles) > 0)
                  <tr class="border">
                    <td>{{ $user->name }}</td>
                    <td>
                      @foreach ($user->roles as $item)
                        <span class="badge badge-secondary">{{ $item->name }}</span>
                      @endforeach
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
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
      $('#myTable').DataTable();
    })
  </script>
@endpush
