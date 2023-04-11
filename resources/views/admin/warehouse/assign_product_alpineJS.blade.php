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

                <div id="product-list">
                    <div class="row">
                        <div class="col-md-8">
                            <p><b>Product</b></p>
                        </div>
                        <div class="col-md-4">
                            <p><b>Quantity</b></p>
                        </div>
                    </div>
                    <div x-data="ItemsData" @changed-select2.window="setSelectValue($event.detail)">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-light p-3 mb-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select required name="product_id[]" class="form-control select2" style="width: 100%"
                                                :index="index" :id="`product_${index}`"
                                                x-model="item.product"
                                        >
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} - ({{ ($product->stock - $product->stock_out) . " " . $product->unit->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex">
                                            <input name="quantity[]" :id="`quantity_${index}`" x-model="item.quantity" class="form-control" type="number" min="1" :max="item.max" required>
                                            <button type="button" class="ml-4 btn btn-icon btn-clean text-hover-danger" @click="removeItem(index); $nextTick(renderSelect)">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button class="btn btn-primary px-2 pb-1 pt-1" @click="addItem(); $nextTick(renderSelect)" type="button">
                            <i class="fas fa-plus"></i>Add New Product
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer py-5">
            <button class="btn btn-primary float-right" type="submit" form="warehouse_to_store_form">Submit</button>
        </div>
    </div>
@endsection

@push('script')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        $("#product-list").on("select2:select", '.select2', function (e) {
            window.dispatchEvent(new CustomEvent("changed-select2", {
                detail: {
                    value: e.target.value,
                    index: e.target.getAttribute('index')
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('ItemsData', () => ({
                items: [{product: '', quantity: '', max: ''}],

                addItem() {
                    this.items.push({product: '', quantity: '', max: ''});
                },

                removeItem(index) {
                    if (this.items.length <= 1) return;
                    this.items.splice(index, 1);
                },

                setSelectValue(detail) {
                    this.items[detail.index].product = detail.value;
                },

                renderSelect() {
                    $('#product-list .select2').select2({placeholder: 'Select option'});
                }
            }));
        });
    </script>
@endpush
