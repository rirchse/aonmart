<div class="row" id="promotion-livewire">
  <div class="col-md-12">
    <div class="d-flex justify-content-between">
      <h4>Promotional Products</h4>
      @if($errorMessage)
        <span class="text-danger pull-right">{{ $errorMessage }}</span>
      @endif
    </div>
  </div>
  <div class="col-md-12">
    <div class="table-responsive">
      <table class="table table-bordered" id="products-table">
        <thead>
        <th style="font-weight: bold">SL</th>
        <th style="font-weight: bold">Product
          <span class="text-danger">*</span>
        </th>
        <th style="font-weight: bold">Regular Price</th>
        <th style="font-weight: bold">Promotion Price
          <span class="text-danger">*</span>
        </th>
        <th style="font-weight: bold">Action</th>
        </thead>
        <tbody>
        @foreach($items as $index => $item)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td style="width: 300px;">
              <select id="items.{{ $index }}.product_id" name="products[]" wire:model.defer="items.{{ $index }}.product_id" class="select2 form-control form-control-sm" type="promotion-item" index="{{ $index }}" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </td>
            <td>
              <input type="number" class="form-control form-control-sm" readonly disabled value="{{ $item['regular_price'] }}">
            </td>
            <td>
              <input type="text" name="prices[]" class="form-control form-control-sm" value="{{ $item['price'] }}" required>
            </td>
            <td>
              <button class="btn btn-danger btn-sm" type="button" wire:click="removeItem({{ $index }})">Remove</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      <button type="button" class="btn btn-primary btn-sm" wire:click="addNewItem()">Add More</button>
    </div>
  </div>
</div>

@push('style')
  <style>
    .select2, span.select2.select2-container.select2-container--default.select2-container--focus {
      width: 100% !important;
    }
  </style>
@endpush

@push('script')
  <script>
    $(document).ready(function () {
      $('#promotion-livewire').on('select2:select', '.select2', function (e) {
        if (e.target.getAttribute('type') === 'promotion-item') {
          Livewire.emitTo('dashboard.promotion-products', 'changedItemsProduct', e.target.getAttribute('index'), e.target.value);
        } else {
        @this.set(e.target.getAttribute('id'), e.target.value);
        }
      });

      window.addEventListener('render-select2', event => {
        $('.select2').select2({
          placeholder: "Select a option.",
          allowClear: true
        });
      });
    });
  </script>
@endpush
