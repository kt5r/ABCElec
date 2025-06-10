<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    public function searchProducts(array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['category', 'orderItems'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0);

        // Apply filters
        $query = $this->applyFilters($query, $filters);

        // Apply sorting
        $query = $this->applySorting($query, $filters['sort'] ?? 'popular');

        return $query->paginate($filters['per_page'] ?? 12);
    }

    public function searchByKeyword(string $keyword, array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['category', 'orderItems'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('name_si', 'LIKE', "%{$keyword}%")
                  ->orWhere('description_si', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('category', function (Builder $categoryQuery) use ($keyword) {
                      $categoryQuery->where('name', 'LIKE', "%{$keyword}%")
                                  ->orWhere('name_si', 'LIKE', "%{$keyword}%");
                  });
            });

        // Apply additional filters
        $query = $this->applyFilters($query, $filters);

        // Apply sorting
        $query = $this->applySorting($query, $filters['sort'] ?? 'relevance');

        return $query->paginate($filters['per_page'] ?? 12);
    }

    public function getProductsByCategory(int $categoryId, array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['category', 'orderItems'])
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0);

        // Apply filters
        $query = $this->applyFilters($query, $filters);

        // Apply sorting
        $query = $this->applySorting($query, $filters['sort'] ?? 'popular');

        return $query->paginate($filters['per_page'] ?? 12);
    }

    public function getFeaturedProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with(['category', 'orderItems'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPopularProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with(['category', 'orderItems'])
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRelatedProducts(Product $product, int $limit = 4): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with(['category', 'orderItems'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        // Price range filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Category filter
        if (!empty($filters['category'])) {
            if (is_array($filters['category'])) {
                $query->whereIn('category_id', $filters['category']);
            } else {
                $query->where('category_id', $filters['category']);
            }
        }

        // Stock filter
        if (!empty($filters['in_stock'])) {
            $query->where('stock_quantity', '>', 0);
        }

        // Featured filter
        if (!empty($filters['featured'])) {
            $query->where('is_featured', true);
        }

        return $query;
    }

    private function applySorting(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            'popular' => $query->withCount('orderItems')->orderBy('order_items_count', 'desc'),
            'rating' => $query->orderBy('rating', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    public function getSearchSuggestions(string $keyword, int $limit = 5): array
    {
        $products = Product::where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('name_si', 'LIKE', "%{$keyword}%");
            })
            ->select('id', 'name', 'name_si', 'price', 'image')
            ->limit($limit)
            ->get();

        $categories = Category::where('status', 'active')
            ->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('name_si', 'LIKE', "%{$keyword}%");
            })
            ->select('id', 'name', 'name_si')
            ->limit($limit)
            ->get();

        return [
            'products' => $products,
            'categories' => $categories,
        ];
    }
}