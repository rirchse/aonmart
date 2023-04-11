<div class="card-body" id="purchase-livewire">
  <div class="row">
    <div class="form-group col-md-3">
      <label for="invoiceNo">Invoice No <span class="text-danger">*</span></label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="invoiceNo-addon">#</span>
        </div>
        <input name="invoiceNo" id="invoiceNo" class="form-control" type="text" wire:model.lazy="invoiceNo" disabled />
      </div>
      @error('invoiceNo')
      <span class="text-sm text-danger"><i class="far fa-times-circle"></i> {{ $message }}</span>
      @enderror
    </div>
    <div class="form-group col-md-3">
      <label for="purchaseDate">Date <span class="text-danger">*</span></label>
      <input type="date" name="purchaseDate" wire:model.lazy="purchaseDate" class="form-control @error('purchaseDate') is-invalid @enderror" id="purchaseDate" placeholder="Enter Date" required>
      @error('purchaseDate')
      <span class="text-sm text-danger" for="purchaseDate"><i class="far fa-times-circle"></i> {{ $message }}</span>
      @enderror
    </div>
    <div class="form-group col-md-3" wire:ignore>
      <label for="supplierId">Suppliers <span class="text-danger">*</span></label>
      <select name="supplierId" id="supplierId" wire:model.lazy="supplierId" class="select2 form-control @error('supplierId') is-invalid @enderror">
        <option></option>
        @foreach ($suppliers as $supplier)
          <option value="{{ $supplier->id }}">{{ $supplier->name }}{!! ($supplier->company ? '&nbsp;|&nbsp' . $supplier->company->name . '' : '') . '&nbsp;|&nbsp;' . $supplier->mobile !!}</option>
        @endforeach
      </select>
      @error('supplierId')
      <span class="text-sm text-danger" for="supplierId"><i class="far fa-times-circle"></i> {{ $message }}</span>
      @enderror
    </div>
    <div class="form-group col-md-3">
      <label for="note">Note</label>
      <input type="text" name="note" wire:model.lazy="note" class="form-control @error('note') is-invalid @enderror" id="note" placeholder="Write Note Here">
      @error('note')
      <span class="text-sm text-danger" for="note"><i class="far fa-times-circle"></i> {{ $message }}</span>
      @enderror
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 table-responsive">
      <table class="table table-bordered table-sm text-nowrap">
        <thead>
        <th style="width: 32%">Product @if($supplierId) &nbsp;<button class="btn btn-icon-muted btn-sm btn-hover-icon-primary" wire:click="updateProductListForSelectedSupplier()" title="Update Supplier Product."><i class="fas fa-arrow-down"></i></button> @endif</th>
        <th style="width: 100px">Quantity</th>
        <th style="width: 120px">Unit</th>
        <th style="width: 100px">Sell Price</th>
        <th style="width: 100px">Purchase Price</th>
        <th style="width: 100px">Discount</th>
        <th style="width: 120px">Price</th>
        <th style="width: 20px"></th>
        </thead>
        <tbody>
        @php($totalPrice = $totalDiscount = 0)
        @foreach ($items as $key => $item)
          <tr>
            <td>
              <select id="{{ $key }}" type="purchase-item" wire:model.lazy="items.{{ $key }}.product_id" class="select2 form-control form-control-sm @error('items.' . $key . '.product_id') is-invalid @enderror" data-live-search="true" required>
                <option value="">Select Product</option>
                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
              @error('items.' . $key . '.product_id')
              <br>
              <span class="text-sm text-danger" for="note"><i class="far fa-times-circle"></i> {{ $message }}</span>
              @enderror
            </td>
            <td>
              <input wire:model.debounce.500ms="items.{{ $key }}.quantity" type="number" name="quantities[]" class="text-center form-control form-control-sm @error('items.' . $key . '.quantity') is-invalid @enderror" placeholder="Quantity">
              @error('items.' . $key . '.quantity')
              <span class="text-sm text-danger" for="note"><i class="far fa-times-circle"></i> {{ $message }}</span>
              @enderror
            </td>
            <td>
              <input value="{{ $item['unit'] }}" type="text" class="form-control form-control-sm" disabled>
            </td>
            <td>
              <input value="{{ $item['sale_price'] }}" type="text" class="text-center form-control form-control-sm" disabled >
            </td>
            <td>
              <input wire:model.debounce.500ms="items.{{ $key }}.purchase_price" type="number" class="text-center form-control form-control-sm @error('items.' . $key . '.purchase_price') is-invalid @enderror" aria-label="Per Unit Price" name="unit_prices[]">
              @error('items.' . $key . '.purchase_price')
              <span class="text-sm text-danger" for="note"><i class="far fa-times-circle"></i> {{ $message }}</span>
              @enderror
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input wire:model.debounce.500ms="items.{{ $key }}.discount" type="number" class="text-center form-control @error('items.' . $key . '.discount') is-invalid @enderror" aria-label="Discount" name="discount[]" min="0">
                <div class="input-group-append">
                  <span class="input-group-text px-1">TK</span>
                </div>
              </div>
              @error('items.' . $key . '.discount')
              <span class="text-sm text-danger" for="note"><i class="far fa-times-circle"></i> {{ $message }}</span>
              @enderror
            </td>
            @php($price = ((is_numeric($item['quantity']) ? $item['quantity'] : 0) * (is_numeric($item['purchase_price']) ? $item['purchase_price'] : 0)) - (is_numeric($item['discount']) ? $item['discount'] : 0))
            @php($totalPrice += $price)
            @php($totalDiscount += (is_numeric($item['discount']) ? $item['discount'] : 0))
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="text-right form-control form-control-sm" value="{{ $price }}" disabled>
                <div class="input-group-append">
                  <span class="input-group-text px-1">TK</span>
                </div>
              </div>
            </td>
            <td class="text-center">
              <button wire:click="removeItem('{{ $key }}')" type="button" class="btn btn-sm btn-icon btn-light btn-hover-danger" title="Delete"><i class="fa fa-trash-alt"></i></button>
            </td>
          </tr>
        @endforeach
        <tr>
          <td colspan="6">
            <button type="button" wire:click="addNewItem()" class="btn btn-primary btn-sm float-left py-1 px-3"><i class="la la-plus"></i>Add More</button>
            <span class="float-right">Total</span>
          </td>
          <td>
            <div class="input-group input-group-sm">
              <input type="text" class="text-right form-control form-control-sm" name="cost_amount" placeholder="Total" value="{{ $totalPrice }}" disabled>
              <div class="input-group-append">
                <span class="input-group-text px-1">TK</span>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td colspan="6" class="text-right"> Due</td>
          <td>
            <div class="input-group input-group-sm">
              <input type="text" class="text-right form-control form-control-sm" placeholder="Due" value="{{ number_format($totalPrice - (is_numeric($paid) ? $paid : 0), 2) }}" disabled>
              <div class="input-group-append">
                <span class="input-group-text px-1">TK</span>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td colspan="6" class="text-right"> Discount</td>
          <td>
            <div class="input-group input-group-sm">
              <input type="text" name="discount" value="{{ number_format($totalDiscount, 2) }}" class="text-right form-control form-control-sm" id="discount" disabled>
              <div class="input-group-append">
                <span class="input-group-text px-1">TK</span>
              </div>
            </div>
            @error('discount')
            <span class="text-xs text-danger"><i class="far fa-times-circle"></i> {{ $message }}</span>
            @enderror
          </td>
        </tr>

        <tr>
          <td colspan="6" class="text-right"> Paid</td>
          <td>
            <div class="input-group input-group-sm">
              <input type="text" wire:model.debounce.500ms="paid" name="paid" class="text-right form-control form-control-sm" id="paid" required min="0" max="" autocomplete="off">
              <div class="input-group-append">
                <span class="input-group-text px-1">TK</span>
              </div>
            </div>
            @error('paid')
            <span class="text-xs text-danger"><i class="far fa-times-circle"></i> {{ $message }}</span>
            @enderror
          </td>
          <td class="text-center">
            <button wire:click="fullPaid('{{ $totalPrice }}')" type="button" class="btn btn-sm btn-icon btn-light btn-hover-primary" title="Full Paid"><i class="fa fa-arrow-left"></i></button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 text-right">
      <a href="{{ route('purchase.index') }}" class="btn btn-default">Cancel</a>
      <button type="button" class="btn btn-primary" wire:click="savePurchase">{{ $purchase ? 'Update' : 'Save' }}</button>
    </div>
  </div>
</div>

@push('script')
  <script>
    $(document).ready(function() {
      $('#purchase-livewire').on('select2:select', '.select2', function (e) {
        if (e.target.getAttribute('type') == 'purchase-item') {
          Livewire.emitTo('dashboard.purchase', 'changedItemsProduct', e.target.getAttribute('id'), e.target.value);
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
      $('.select2').select2({
        placeholder: "Select a option.",
        allowClear: true
      });
    });
  </script>
@endpush
