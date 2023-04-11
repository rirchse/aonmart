@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Mobile App Products</h3>
            </div>
        </div>

        <div class="card-body">
            <table class="table" id="myTable" width="100%">
                <thead>
                    <tr>
                        <th style="width: 10px">SL</th>
                        <th width="50">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th style="width: 60px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <img src="{{ $product->image_url }}" alt="avatar" height="50px" width="50px" />
                            </td>
                            <td>
                                {{ $product->name }}
                            </td>
                            <td>
                                @foreach ($product->Categories as $category)
                                    <span class="label font-weight-bold label-lg label-light-info label-inline">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="label font-weight-bold label-lg label-light-primary label-inline">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td nowrap="nowrap">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-clean btn-icon text-dark"><i class="la la-edit icon-lg"></i></a>
                                <form method="post" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm text-hover-danger btn-clean btn-icon"><i
                                            class="la la-trash icon-lg"></i></button>
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
