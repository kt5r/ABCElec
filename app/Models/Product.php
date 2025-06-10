<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'images',
        'featured_image',
        'category_id',
        'is_featured',
        'is_active',
        'weight',
        'dimensions',
        'attributes',
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Automatically generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Accessors
    public function getCurrentPrice()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentage()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function isOnSale()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getFeaturedImageUrl()
    {
        if ($this->featured_image) {
            return asset('storage/products/' . $this->featured_image);
        }
        
        if ($this->images && count($this->images) > 0) {
            return asset('storage/products/' . $this->images[0]);
        }
        
        return asset('images/no-image.png');
    }

    public function getImageUrls()
    {
        if ($this->images) {
            return array_map(function ($image) {
                return asset('storage/products/' . $image);
            }, $this->images);
        }
        
        return [asset('images/no-image.png')];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}