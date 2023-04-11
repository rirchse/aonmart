<tr>
    <td> {{ $sale->number_sale }} </td>
    <td>
        <button type="button" wire:click="$set('sale_id', {{ $sale->id }})" class="btn btn-sm btn-primary">Return</button>
    </td>
</tr>
