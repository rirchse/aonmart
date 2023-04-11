@extends('layouts.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Products Warehouse to Store</h3>
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
            <form action="{{ route('assign.store') }}" method="POST" id="warehouse_to_store_form">
                @csrf
                <div class="d-flex flex-wrap mb-4">
                    <div class="form-group mb-4 d-flex align-items-center mr-5">
                        <label for="store_id" class="mr-2">Store</label>
                        <select class="form-control" name="store_id" id="store_id" required>
                            <option value="">Select Store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : null }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4 d-flex align-items-center">
                        <label for="date" class="mr-2">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                        <tr>
                            <th style='width: 60%'>Product</th>
                            <th style='width: 18%'>Warehouse Stock</th>
                            <th style='width: 22%'>Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-primary btn-sm" id="addButton"><i class="la la-plus"></i> Add New Product</button>
            </form>
        </div>

        <div class="card-footer py-5">
            <button class="btn btn-primary float-right" type="submit" form="warehouse_to_store_form">Submit</button>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let btnAdd = document.querySelector('#addButton');
        let table = document.querySelector('#table-body');

        let rows = [{
            Supply_id: '',
            Supply_name: '',
            qty: '',
            transfer_quantity: '',
            unit: '',
            amount: '',
        }];
        renderTable();

        $('#addButton').on('click', function () {
            rows.push({
                Supply_id: '',
                Supply_name: '',
                qty: '',
                transfer_quantity: '',
                unit: '',
                amount: '',
            });
            renderTable();
        });

        $(document).on('click', '.delete-button', function () {
            if (rows.length === 1) return;
            rows.splice($(this).data('id'), 1);
            renderTable();
        });

        function renderTable() {
            let html = ""
            for (let i = 0; i < rows.length; i++) {
                html += "<tr>"
                html += "<td> <select class='form-control form-control-sm select2 product" + i + "' name='product_id[]' index='" + i + "' required>"
                html += "<option value='" + rows[i].Supply_id + "'>" + rows[i].Supply_name + "</option>"
                html += "@foreach ($products as $product)"
                html += "<option value='{{ $product->id }}'{{ $product->id == "+rows[i].Supply_id+"?'selected':'' }}>{{ $product->name }} - ({{ $product->stock - $product->stock_out . " " . $product->unit->name }})</option>"
                html += "@endforeach"
                html += "</select></td>"

                html += "<td><input type='text' class='form-control qty-" + i + "' value='" + rows[i].qty + " " + rows[i].unit + "' placeholder='Stock' disabled></td>"
                html += "<td class='d-flex'><input type='number' min='1' max='" + rows[i].qty + "' class='form-control transfer_quantity-" + i + "' value='" + rows[i].transfer_quantity + "' placeholder='Quantity' name='quantity[]' onchange='pushStock(" + i + ")' required>"
                html += "<button type='button' class='ml-5 delete-button btn btn-icon text-hover-danger btn-clear' data-id='" + i + "'><i class='fa fa-trash-alt'></i></button></td>"
                html += "</tr>"
            }
            table.innerHTML = html

            $('#table-body .select2').select2({
                placeholder: "Select Product",
                width: '100%'
            })
        }

        function pushStock(i) {
            rows[i].transfer_quantity = event.target.value;
        }

        $('#table-body').on('select2:select', '.select2', function (e) {
            let product_id = e.target.value;
            let i = e.target.getAttribute('index');
            $.ajax({
                url: "{{ route('warehouse.getByAjax') }}",
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": product_id,
                },
                context: document.body
            }).done(function (data) {
                rows[i].qty = data[0].stock - data[0].stock_out;
                rows[i].Supply_id = data[0].id;
                rows[i].Supply_name = data[0].name;
                rows[i].unit = data[0].unit.name;
                renderTable();
            })
        });

    </script>
@endpush
