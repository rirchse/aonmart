<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['date'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function expenseBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expense_by');
    }
}
