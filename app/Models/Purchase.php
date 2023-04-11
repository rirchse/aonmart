<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['purchase_date'];

    // Relation functions Start
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('qty', 'discount', 'product_price', 'total_price', 'unit');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productReturn(): HasMany
    {
        return $this->hasMany(ReturnProduct::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    // Relation functions End

    // Attribute functions start
    public function formattedDate(): Attribute
    {
        return Attribute::get(fn() => dateFormat($this->purchase_date));
    }

    public function paidAmount(): Attribute
    {
        return Attribute::get(fn() => $this->grand_total - $this->due_amount);
    }
    // Attribute functions End

    // Model Helper Functions Start
    public static function generateNewPurchaseNo(): string
    {
        // return time() . Str::random(4); // Random string

        $lastInvoiceNo = self::latest('created_at')
                ->first()
                ->invoice_no ?? 0;
        return (int)$lastInvoiceNo + 1; // Serial number of order

//        Serial number with 0000
//        return str_pad(
//            (int) $lastInvoiceNo + 1,
//            8,
//            '0',
//            STR_PAD_LEFT
//        );
    }

    public function newPurchase()
    {
        $this->purchase_no = self::generateNewPurchaseNo();
        $this->purchase_date = now();
        $this->save();
    }
    // Model Helper Functions End
}
