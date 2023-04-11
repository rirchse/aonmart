@extends('layouts.default')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">Products Barcode</h3>
        </div>
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
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
        <form action="{{ route('product.barcode.print') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary">Submit</button>
            <table class="table" id="myTable" width="100%">
                <thead>
                <tr>
                    <th style="min-width: 130px; width: 140px"><input type="checkbox" class="select_all_products" id="select_all_products"> <label for="select_all_products">Select All</label></th>
                    <th>Name</th>
                    <th>Category</th>
                </tr>
                </thead>
                <tbody>

                @foreach($products as $product)
                    <tr>
                        <td>
                            <input type="number" name="qty[]" id="qty{{ $product->id }}" class="qty" style="width: 50px" disabled>
                            <input type="checkbox" name="products[]" value="{{ $product->id . '|' . $product->name  . '|' . $product->barcode  . '|' . ($product->sell_price ?: $product->regular_price) }}" id="product{{ $product->id }}" data-menu_id="{{ $product->id }}" class="select_product">
                            <label for="product{{ $product->id }}">Select</label>
                        </td>
                        <td>
                            {{$product->name}}
                        </td>
                        <td>
                            @foreach($product->categories as $category)
                                <span class="badge badge-secondary d-inline-block w-auto">{{ $category->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

@endsection

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"/>
@endpush

@push('script')
    <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        $(function () {
            // Check or Uncheck All checkboxes start
            $(document).on('click', '.select_all_products', function (e) {
                if ($(this).is(':checked')) {
                    $(".select_product").each(function () {
                        let menu_id = $(this).attr('data-menu_id');
                        $(this).prop("checked", true);
                        $("#qty" + menu_id).val(1).prop("disabled", false);
                    });
                    $(".select_all_products").prop("checked", true);
                } else {
                    $(".select_product").each(function () {
                        let menu_id = $(this).attr('data-menu_id');
                        $(this).prop("checked", false);
                        $("#qty" + menu_id).val('').prop("disabled", true);
                    });
                    $(".select_all_products").prop("checked", false);
                }
            });

            $(document).on('click', '.select_product', function (e) {
                const menu_id = $(this).attr('data-menu_id');
                if ($(".select_product").length === $(".select_product:checked").length) {
                    $(".select_all_products").prop("checked", true);
                    if ($(this).is(':checked')) {
                        $("#qty" + menu_id).val(1).prop("disabled", false);
                    } else {
                        $("#qty" + menu_id).val('').prop("disabled", true);
                    }
                } else {
                    $(".select_all_products").prop("checked", false);
                    if ($(this).is(':checked')) {
                        $("#qty" + menu_id).val(1).prop("disabled", false);
                    } else {
                        $("#qty" + menu_id).val('').prop("disabled", true);
                    }
                }
            });
            // Check or Uncheck All checkboxes end

            $('#myTable').DataTable({
                'autoWidth': false,
                "paging": false,
                'ordering': false,
                buttons: []
            })
        });
    </script>
@endpush
