<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            "address" => 'array'
        ];
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOperationManager()
    {
        return $this->role === 'operation_manager';
    }

    public function isSalesManager()
    {
        return $this->role === 'sales_manager';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function hasAdminAccess()
    {
        return in_array($this->role, ['admin', 'operation_manager']);
    }

    public function canManageProducts()
    {
        return in_array($this->role, ['admin', 'operation_manager']);
    }

    public function canViewReports()
    {
        return in_array($this->role, ['admin', 'operation_manager', 'sales_manager']);
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles) || 
                   ($this->role_id && in_array($this->role->name, $roles));
        }

        return $this->role === $roles || 
               ($this->role_id && $this->role->name === $roles);
    }

    /**
     * Get the roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function getCartCount()
    {
        return $this->cartItems()->sum('quantity');
    }
    public function getCartTotal()
    {
        return $this->cartItems()->with('product')->get()->sum(function ($item) {
            return $item->quantity * ($item->product->sale_price ?? $item->product->price);
        });
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRoleAttribute()
    {
        return $this->role()->first()?->name ?? null;
    }
}
