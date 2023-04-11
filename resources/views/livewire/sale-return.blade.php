@push('style')
<style>
    #message {
        font-size: 15px;
        background-color: rgb(38, 161, 65);
        padding: 8px 5px 5px 5px;
        color: rgb(255, 255, 255);
        border-radius: 10px;
        z-index: 1000;
        position: absolute;
        left: 40%;
        bottom: 15%;
    }
</style>
@endpush

<div>
    <button type="button" class="btn btn-primary btn-sm mr-2" data-action="{{ route('sale.payment.due') }}" data-toggle="modal" onclick="returnModal()">Return</button>
    <div wire:ignore.self class="modal fade bd-example-return-modal-lg-client" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle1">Sale Return</h5>
                    <h5 id='validate' class="modal-title text-danger mx-auto"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span style="display: block" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <p id='validate'></p>
                <div class="modal-body">
                    @if($backToInvoiceList)
                        <div class="before_inv_select">
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label for="searchInvoice">Search</label>
                                        <input wire:model.debounce.500ms="search" type="search" name="searchInvoice" id="searchInvoice" placeholder="Search Invoice" class="form-control" autofocus autocomplete="off">
                                        <div wire:loading.delay wire:target="search">
                                            Searching Invoice...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th style="width: 200px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody wire:loading.class.delay="opacity-60" wire:target="search">
                                @forelse($sales as $sale)
                                    @livewire('sale.sale', ['sale' => $sale], key($sale->id))
                                @empty
                                    <tr>
                                        <td colspan="2">
                                            <h4 class="text-black-50 text-center p-4">No Data Found!</h4>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $sales->links() }}
                            </div>
                        </div>
                    @else
                        <div class="after_inv_select">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 500px;">name</th>
                                    <th style="width: 500px;">Quantity</th>
                                    <th style="width: 500px;">Unit Price</th>
                                    <th style="width: 500px;">Soled Price</th>
                                    <th style="width: 500px;">Return Quantity</th>
                                    <th style="width: 500px;">Note</th>
                                    <th style="width: 500px;">Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($products as $key => $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-control" wire:model.lazy="return.{{ $product->id }}.product">
                                        </td>
                                        <td> {{ $product->name }}</td>
                                        <td> {{ $product->pivot->quantity }} {{ $product->unit->name }}</td>
                                        <td> {{ $product->pivot->product_price }}</td>
                                        <td> {{ $product->pivot->total_price }}</td>
                                        <td>
                                            <input
                                                type="number"
                                                class="form-control qty-{{ $key }}"
                                                wire:model.lazy="return.{{ $product->id }}.qty"
                                                wire:change='setMaximumQty({{ $product->pivot->quantity }},{{ $product->id }})'
                                            >
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                class="form-control note-{{ $key }}"
                                                wire:model.lazy="return.{{ $product->id  }}.note"
                                            >
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control type-{{ $key }}" wire:model.lazy='return.{{ $product->id  }}.type'>
                                                    <option selected>Select</option>
                                                    <option value="damage">Damage Return</option>
                                                    <option value="normal">Normal Return</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <p>Not Product Found!</p>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button wire:click="$set('backToInvoiceList', true)" class="btn btn-dark mt-2">Back</button>
                            <a href="#" class="btn btn-outline-dark mt-2" wire:click="returning()">Return</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <p id="message" class="d-none"></p>
</div>

@push('script')
<script>
    function returnModal() {
        $('.bd-example-return-modal-lg-client').modal('show');
    }

    window.addEventListener('name-updated', event => {
        if (event.detail.modalclose == true) {
            $('.bd-example-return-modal-lg-client').modal('hide');
            $('#message').html(event.detail.message).addClass('d-block').removeClass('d-none');

            setTimeout(function () {
                $('#message').removeClass('d-block').addClass('d-none');
            }, 3000);
        } else {
            $('#validate').html(event.detail.message).addClass('d-block').removeClass('d-none');

            setTimeout(function () {
                $('#validate').removeClass('d-block').addClass('d-none');
            }, 3000);
        }
    })
</script>
@endpush
