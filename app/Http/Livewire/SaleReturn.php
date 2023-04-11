<?php

namespace App\Http\Livewire;

use App\Models\ReturnProduct;
use App\Models\Sale;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class SaleReturn extends Component
{
    use WithPagination;

    public $store;
    public $backToInvoiceList = true;
    public $search = "";
    public $sale_id;
    public $return = [];
    public $note;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'sale_id_updated' => 'setSaleId',
        'sale_created' => 'render'
    ];

    public function updatedSearch()
    {
        $this->render();
        $this->resetPage();
    }

    public function render()
    {
        $sales = Sale::when($this->store, function ($query) {
            return $query->where('store_id', $this->store->id);
        })->search($this->search)->latest()->paginate(10);

        $products = [];
        if ($this->sale_id) {
            $products = Sale::find($this->sale_id)->products()->with('unit')->get();
        }

        return view('livewire.sale-return', [
            'sales' => $sales,
            'products' => $products
        ]);
    }

    public function setSaleId($sale_id)
    {
        $this->sale_id = $sale_id;
        $this->backToInvoiceList = false;
    }

    public function setMaximumQty($value, $key)
    {
        $this->return[$key]['maxqty'] = $value;
    }

    public function returning()
    {
        $sell = Sale::find($this->sale_id);
        $totalQty = 0;
        $totalAmount = 0;
        $attach = [];
        $message = '';
        if ($this->return != null) {
            foreach ($this->return as $key => $return) {
                if (isset($return['product']) && $return['product'] == true) {
                    if (!isset($return['qty']) || empty($return['qty'])) {
                        $message = 'Return Quantity has no value';
                        $this->dispatchBrowserEvent('name-updated', ['message' => $message, 'modalclose' => false]);
                        return true;
                    }
                    if ($this->validation($return['maxqty'], $return['qty']) == false) {
                        return true;
                    }
                    $product = $sell->products()->find($key);
                    if ($return['type'] == 'normal') {
                        $store = Store::find(session('store_id'));
                        $updateStockProduct = $store->products()->find($key);
                        $updateStockProduct->pivot->stock_out -= $return['qty'];
                        $updateStockProduct->pivot->save();
                    }
                    $product->pivot->quantity -= $return['qty'];
                    $product->pivot->save();
                    $totalQty += $return['qty'];
                    $totalAmount += $return['qty'] * $product->pivot->product_price;
                    $attach[$key] = [
                        'note' => $return['note'] ?? "no note given",
                        'status' => $return['type'],
                        'qty' => $return['qty'],
                        'total' => $return['qty'] * $product->pivot->product_price,
                    ];
                }
            }
        }
        $modalclose = true;
        if (!empty($attach)) {
            $message = 'Return saved successfully';
            $return = ReturnProduct::create([
                'total_qty' => $totalQty,
                'return_amount' => $totalAmount,
                'sale_id' => $this->sale_id,
            ]);
            $return->products()->attach($attach);
        } else {
            $modalclose = false;
            $message = 'Return faild';
        }

        $this->dispatchBrowserEvent('name-updated', ['message' => $message, 'modalclose' => $modalclose]);
        $this->reset(['return', 'backToInvoiceList']);
        return true;
    }

    public function validation($maxqty, $qty)
    {
        if ($qty > $maxqty) {
            $message = 'Return Quantity value is larger then quantity';
            $this->dispatchBrowserEvent('name-updated', ['message' => $message, 'modalclose' => false]);
            return false;
        }
        return true;
    }
}
