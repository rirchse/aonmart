<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class PromotionProducts extends Component
{
    protected $listeners = ['changedItemsProduct'];
    public $errorMessage;
    public $products;
    public $items;
    public $promotion;

    public function render()
    {
        $this->dispatchBrowserEvent('render-select2');
        return view('livewire.dashboard.promotion-products');
    }

    public function mount()
    {
        if (is_array(old('products')) && is_array(old('prices'))) {
            $items = [];
            foreach (old('products') as $index => $product_id) {
                $items[$index] = [
                    'product_id' => $product_id,
                    'regular_price' => $this->products->where('id', $product_id)->first()->regular_price,
                    'price' => old('prices')[$index]
                ];
            }
        } else if ($this->promotion) {
            $products = $this->promotion->details()->get();
            $items = [];
            foreach ($products as $index => $product) {
                $items[$index] = [
                    'product_id' => $product->id,
                    'regular_price' => $product->regular_price,
                    'price' => $product->pivot->price
                ];
            }
        } else {
            $items = [
                [
                    'product_id' => '',
                    'regular_price' => 0,
                    'price' => 0
                ]
            ];
        }
        $this->items = collect($items);
    }

    public function addNewItem()
    {
        if ($this->items->count() == $this->products->count()) {
            $this->errorMessage = 'Maximus item added.';
            return;
        }
        $this->errorMessage = null;
        $this->items->push([
            'product_id' => '',
            'regular_price' => 0,
            'price' => 0
        ]);
    }

    public function removeItem($index)
    {
        if ($this->items->count() == 1) {
            $this->errorMessage = 'Minimum one products is required.';
            return;
        }
        $this->errorMessage = null;
        $this->items->forget($index);
    }

    public function changedItemsProduct($index, $product_id)
    {
        $product = $this->products->where('id', $product_id)->first();
        if ($this->items->where('product_id', $product_id)->count() > 1) {
            $product = null;
            $this->errorMessage = 'Duplicate product selection not allowed.';
        } else {
            $this->errorMessage = null;
        }
        if ($product) {
            $item = [
                $index => [
                    'product_id' => $product->id,
                    'regular_price' => $product->regular_price,
                    'price' => $product->regular_price
                ]
            ];
        } else {
            $item = [
                $index => [
                    'product_id' => '',
                    'regular_price' => 0,
                    'price' => 0
                ]
            ];
        }
        $this->items = $this->items->replace($item);
    }
}
