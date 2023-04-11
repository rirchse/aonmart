<?php

namespace App\Models;

use App\Models\Product;

class Cart
{

    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        } else {
            $this->items = null;
        }
    }

    public function add($item, $id)
    {
        $product_id = $item['product_id'];
        $qty = $item['qty'];
        $product = Product::query()->findOrFail($id);

        $item['name'] = $product->name;

        $item['img'] = $product->image;
        if ($product->sale_price != null) {
            $item['price'] = $product->sale_price;
        } else {
            $item['price'] = $product->regular_price;
        }

        $total = $item['price'] * 1;
        /* Discount Calculation start */

        $item['totalprice'] = $total;

        $storedItem = ['qty' => 0, 'price' => $total, 'item' => $item];

        $be = $storedItem;
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $this->items[$id] = $be;
                $storedItem = $this->items[$id];
            }
        }
        $storedItem['qty']++;
        $storedItem['price'] = $item['totalprice'] * $storedItem['qty'];
        $storedItem['price'] = $item['totalprice'] * 1;
        $this->totalQty++;
        $this->totalPrice += $item['totalprice'];
        $this->items[$id] = $storedItem;
    }


    public function updateCart($id)
    {
        $prevItems = $this->items;
        $prevTotalPrice = $this->totalPrice;

        $this->items[$id]['item']['qty'] = $this->items[$id]['item']['qty'] - 1; //0

        $this->totalPrice -= ($this->items[$id]['item']['price'] * 1);
        if ($this->items[$id]['item']['qty'] == 0) {
            $this->singleProductRemove($id);
        }
    }

    public function singleProductRemove($id)
    {
        $this->totalQty--;
        if ($this->items[$id]['item']['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }

    public function remove($id)
    {
        $this->items[$id]['qty']--;
        $this->items[$id]['price'] -= $this->items[$id]['item']['totalprice'];

        $this->totalQty--;
        $this->totalPrice -=  ($this->items[$id]['item']['totalprice'] * $this->items[$id]['item']['qty']);

        if ($this->items[$id]['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }
}
