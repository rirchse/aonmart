<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Product;
use App\Models\Purchase as PurchaseModel;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Purchase extends Component
{

    // Overrides var
    public $suppliers;
    public $products;
    public $purchase;

    // Data vars
    public $units;
    public $store;
    public $invoiceNo;
    public $purchaseDate;
    public $supplierId;

    // bind vars
    public $note;
    public $items;
    public $paid;
    protected $listeners = ['changedItemsProduct'];
    protected $rules = [
        'invoiceNo' => ['required'],
        'purchaseDate' => ['required', 'date'],
        'supplierId' => ['required'],
        'note' => ['nullable', 'string'],
        'paid' => ['required', 'numeric'],
        'items.*.product_id' => ['required', 'distinct'],
        'items.*.quantity' => ['required', 'numeric', 'min:1'],
        'items.*.purchase_price' => ['required', 'numeric', 'min:0'],
        'items.*.discount' => ['nullable', 'numeric', 'min:0']
    ];
    protected $messages = [
        'required' => "Required.",
        'distinct' => "Duplicate Entry.",
        'numeric' => "Numeric Only",
        'min' => "Min: 0"
    ];

    public function render()
    {
        $this->dispatchBrowserEvent('render-select2');
        return view('livewire.dashboard.purchase');
    }

    public function mount()
    {
        $this->units = Unit::where('status', TRUE)->get();
        $this->suppliers = User::with(['products', 'products.Unit', 'company'])->role('Supplier')->orderBy('name')->get();
        if ($this->purchase) {
            $this->invoiceNo = $this->purchase->invoice_no;
            $this->purchaseDate = $this->purchase->purchase_date->format('Y-m-d');
            $this->supplierId = $this->purchase->supplier->id;
            $this->note = $this->purchase->purchase_note;
            $this->updatedSupplierId($this->supplierId, FALSE);
            $this->paid = $this->purchase->grand_total - $this->purchase->due_amount;
            foreach ($this->purchase->products as $product) {
                $this->items[] = [
                    'product_id' => $product->id,
                    'quantity' => $product->pivot->qty,
                    'unit' => $this->units->where('id', $product->pivot->unit)->first()->name,
                    'unit_id' => $product->pivot->unit,
                    'sale_price' => $product->regular_price,
                    'purchase_price' => $product->pivot->product_price,
                    'discount' => $product->pivot->discount
                ];
            }
            return;
        }
        $this->products = Product::with(['suppliers', 'unit'])->orderBy('name')->get();
        $this->invoiceNo = PurchaseModel::generateNewPurchaseNo();
        $this->purchaseDate = date('Y-m-d');
        $this->resetItems();
    }

    // variable events
    public function updatedSupplierId($value, $resetItems = true)
    {
        $this->products = $this->suppliers
            ->where('id', $value)
            ->first()
            ->products()
            ->with('unit')
            ->get();
        if ($resetItems !== false) $this->resetItems();
    }

    public function resetItems()
    {
        $this->items = [];
        $this->addNewItem();
    }

    public function fullPaid($totalAmount)
    {
        $this->paid = $totalAmount;
    }

    public function addNewItem()
    {
        $this->items[] = [
            'product_id' => '',
            'quantity' => '',
            'unit' => '',
            'unit_id' => '',
            'sale_price' => '',
            'purchase_price' => '',
            'discount' => ''
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) <= 1) return;
        unset($this->items[$index]);
    }

    public function changedItemsProduct(int $index, $value = FALSE)
    {
        if ($value) {
            $this->items[$index]['product_id'] = $value;
        }
        $product = $this->products->where('id', $this->items[$index]['product_id'])->first();
        $this->items[$index]['unit'] = $product->unit->name;
        $this->items[$index]['unit_id'] = $product->unit->id;
        $this->items[$index]['sale_price'] = $product->regular_price;

        // On Product change update suppliers
        if ($this->supplierId) return;
        $product = $this->products->where('id', $this->items[$index]['product_id'])->first();
        if (!$product) return;
        $supplier = $product->suppliers->first();
        if (!$supplier) return;
        $this->supplierId = $supplier->id;
        $this->updatedSupplierId($this->supplierId, false);
    }

    public function savePurchase()
    {
        $this->validate();
        $totalAmount = 0;
        $totalQuantity = 0;
        $attachableData = [];
        foreach ($this->items as $item) {
            $discount = (is_numeric($item['discount']) ? $item['discount'] : 0);
            $itemTotal = ($item['quantity'] * $item['purchase_price']) - $discount;
            $attachableData[$item['product_id']] = [
                'qty' => $item['quantity'],
                'discount' => $discount,
                'product_price' => $item['purchase_price'],
                'total_price' => $itemTotal,
                'unit' => $item['unit_id']
            ];
            $totalAmount += $itemTotal;
            $totalQuantity += $item['quantity'];
        }
        $dueAmount = number_format($totalAmount - $this->paid, 2);
        if ($this->purchase) {
            $this->updatePurchase($totalAmount, $totalQuantity, $dueAmount, $attachableData);
        } else {
            $this->storePurchase($totalAmount, $totalQuantity, $dueAmount, $attachableData);
        }
    }

    public function updatePurchase($totalAmount, $totalQuantity, $dueAmount, $attachableData)
    {
        try {
            $this->purchase->update([
                'purchase_date' => $this->purchaseDate,
                'supplier_id' => $this->supplierId,
                'purchase_note' => $this->note,
                'grand_total' => $totalAmount,
                'total_qty' => $totalQuantity,
                'due_amount' => $dueAmount
            ]);
            $this->purchase->products()->sync($attachableData);
            session()->flash('success', "Purchase successfully updated.");
        } catch (\Throwable $th) {
            session()->flash('error', "Something Wrong! Try again.");
        }
        return redirect()->route('purchase.index');
    }

    public function storePurchase($totalAmount, $totalQuantity, $dueAmount, $attachableData)
    {
        DB::beginTransaction();
        try {
            $newPurchase = PurchaseModel::create([
                'invoice_no' => $this->invoiceNo,
                'purchase_date' => $this->purchaseDate,
                'supplier_id' => $this->supplierId,
                'purchase_note' => $this->note,
                'grand_total' => $totalAmount,
                'total_qty' => $totalQuantity,
                'due_amount' => $dueAmount,
                'status' => TRUE,
                'store_id' => $this->store->id ?? null
            ])->products()
                ->attach($attachableData);

            session()->flash('success', "Purchase successfully saved.");
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', "Failed to create the purchase. Please try again.");
        }
        return redirect()->route('purchase.create');
    }

    public function updateProductListForSelectedSupplier()
    {
        $this->updatedSupplierId($this->supplierId, false);
    }
}
