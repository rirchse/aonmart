<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    const SUPER_ADMIN_ROLE = 'Super Admin';
    const ADMIN_ROLE = 'Admin';
    const MANAGER_ROLE = 'Manager';
    const SUPPLIER_ROLE = 'Supplier';
    const SALES_PERSON_ROLE = 'Sales Person';
    const CUSTOMER_ROLE = 'Customer';

    const ROLES = [
        self::SUPER_ADMIN_ROLE,
        self::ADMIN_ROLE,
        self::MANAGER_ROLE,
        self::SUPPLIER_ROLE,
        self::SALES_PERSON_ROLE,
        self::CUSTOMER_ROLE,
    ];

    const WALK_IN_CUSTOMER_PHONE = 'XXXXXXXXXXX';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image', 'cover_image', 'mobile',
        'phone', 'about', 'type_id', 'status', 'store_id', 'balance',
        'company_id', 'is_banned', 'ban_reason'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accessors
    // Supplier supply summery
    protected function purchasesSummary(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (object)[
                'total_purchases' => $this->purchases->count(),
                'total_purchases_amount' => $this->purchases->sum('grand_total'),
                'total_purchases_due_amount' => $this->purchases->sum('due_amount'),
            ],
        );
    }

    // Customer orders summery
    protected function ordersSummary(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (object)[
                'total_ordered' => $this->orders->count(),
                'total_ordered_amount' => $this->orders->sum('total'),
            ],
        );
    }

    // Customer sales summery
    protected function salesSummary(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (object)[
                'total_sales' => $this->sales->count(),
                'total_sales_amount' => $this->sales->sum('total_amount'),
                'total_sales_due_amount' => $this->sales->sum('due')
            ],
        );
    }

    protected function isWalkInCustomer(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->mobile == self::WALK_IN_CUSTOMER_PHONE),
        );
    }

    // Relations
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist');
    }

    public function addedExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'added_by');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    // Scopes
    public function scopeStoreID()
    {
        return $this->store?->id;
    }

    // Query related not-static functions
    public function increaseBalance($amount): User
    {
        $this->balance += $amount;
        $this->save();
        return $this;
    }


    // Query related static functions
    public static function getCustomers(array $columns = [], $store_id = null): Collection
    {
        return self::role(self::CUSTOMER_ROLE)
            ->when($store_id, function ($query) use ($store_id) {
                return $query->where('store_id', $store_id)
                    ->where(function ($query) use ($store_id) {
                        return $query->whereHas('orders', fn($query) => $query->where('store_id', $store_id))
                            ->orWhereHas('sales', fn($query) => $query->where('store_id', $store_id));
                    });
            })
            ->when(is_array($columns) && !empty($columns), function ($query) use ($columns) {
                return $query->select($columns);
            })
            ->latest()
            ->get();
    }

    public static function customersQuery(array $columns = [], $store = null)
    {
        return self::query()
        ->role(self::CUSTOMER_ROLE)
        ->when($store, function($query) use ($store){
            return $query->where('store_id', $store->id);
        });

    }

    public static function suppliersQuery(array $columns = ['*'], $store_id = false): Builder
    {
        return self::query()
            ->role(self::SUPPLIER_ROLE)
            ->when($store_id, function ($query) use ($store_id) {
                return $query->whereHas('purchases', fn($query) => $query->where('store_id', $store_id));
            })
            ->select($columns);
    }

    public static function employeesQuery(array $columns = ['*'], $store_id = null): Builder
    {
        return self::query()
            ->role(['Manager', 'Sales Person'])
            ->when($store_id, fn($query) => $query->where('store_id', $store_id))
            ->select($columns);
    }

    public static function adminsQuery(array $columns = ['*'], $store_id = null): Builder
    {
        return self::query()
            ->role(['Admin'])
            ->when($store_id, fn($query) => $query->where('store_id', $store_id))
            ->select($columns);
    }

    public static function findUserByEmailOrPhone(string $emailOrPhone): User|null
    {
        return self::where('email', $emailOrPhone)
            ->orWhere('mobile', $emailOrPhone)
            ->first();
    }

    public static function updatePassword(int $user_id, string $password): bool
    {
        return self::where('id', $user_id)
            ->update([
                'password' => Hash::make($password)
            ]);
    }
}
