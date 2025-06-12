<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'status',
        'role_id',
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

    // RBAC Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // RBAC Methods
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->role && $this->role->name === $roles;
        }

        if (is_array($roles)) {
            return $this->role && in_array($this->role->name, $roles);
        }

        return false;
    }

    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->where('name', $permission)->exists();
    }

    public function hasAnyPermission($permissions)
    {
        if (!$this->role) {
            return false;
        }

        if (is_string($permissions)) {
            return $this->hasPermission($permissions);
        }

        if (is_array($permissions)) {
            return $this->role->permissions()->whereIn('name', $permissions)->exists();
        }

        return false;
    }

    public function getRoleName()
    {
        return $this->role ? $this->role->name : 'customer';
    }

    public function getRoleDisplayName()
    {
        return $this->role ? $this->role->display_name : 'Customer';
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    // Check if user is operation manager
    public function isOperationManager()
    {
        return $this->hasRole('operation_manager');
    }

    // Check if user is sales manager
    public function isSalesManager()
    {
        return $this->hasRole('sales_manager');
    }

    // Check if user is customer
    public function isCustomer()
    {
        return $this->hasRole('customer');
    }

    // Check if user has staff role (admin, operation_manager, sales_manager)
    public function isStaff()
    {
        return $this->hasRole(['admin', 'operation_manager', 'sales_manager']);
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

     // Helper methods
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->postal_code,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    public function updateLoginInfo()
    {
        $this->increment('login_count');
        $this->update(['last_login_at' => now()]);
    }
    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for staff users
    public function scopeStaff($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'operation_manager', 'sales_manager']);
        });
    }

    // Scope for customers
    public function scopeCustomers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('name', 'customer');
        });
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
}
