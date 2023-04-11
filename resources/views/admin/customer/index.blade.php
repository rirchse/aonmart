@extends('layouts.default')

@section('content')
<div class="card card-custom">
    <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Customers</h3>
            </div>

        </div>

        <div class="card-body">
            <table class="table" id="myTable" width="100%">
                <thead>
                <tr>
                    <th>Signup Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Image</th>
                    <th style="width: 60px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $user)
                    <tr>
                        <td>
                            {{ $user->created_at->format('F d, Y h:i:s A') }}
                        </td>
                        <td>
                            {{ $user->name }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->mobile ?? $user->phone }}
                        </td>
                        <td>
                            {{ $user->address }}
                        </td>
                        <td>
                            <img src="{{ getImageUrl($user->image) }}" alt="avatar" height="50px"/>
                        </td>
                        <td>
                            @canany(['user.all','user.edit'])
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm  btn-clean btn-icon"><i class="la la-edit icon-lg"></i></a>

                            @endcanany
                            @canany(['user.all', 'user.delete'])
                                @if($user->id != 1)
                                    <form method="post" action="{{ route('user.destroy', $user->id) }}" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class=" btn btn-sm text-hover-danger btn-clean btn-icon"><i class="la la-trash icon-lg"></i></button>
                                    </form>
                                @endif
                            @endcanany
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
