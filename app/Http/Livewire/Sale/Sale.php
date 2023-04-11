<?php

namespace App\Http\Livewire\Sale;

use Livewire\Component;

class Sale extends Component
{
    public $sale;
    public $sale_id;

    public function updatedSaleId() {
        $this->emit('sale_id_updated', $this->sale_id);
    }

    public function render()
    {
        return view('livewire.sale.sale');
    }
}
