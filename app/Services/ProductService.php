<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ProductService
{
    /**
     * Get products
     * @param Request $request
     * @return Paginator
     */
    public function getProducts(Request $request) : Paginator
    {
        $query = Product::with('category');
        $activeCategory = null;

        if ($request->has('category')) {
            $categoryId = $request->query('category');
            $activeCategory = Category::findOrFail($categoryId);
            $query->where('category_id', $categoryId);
        }

        return $query->paginate(12)->withQueryString();
    }

    /**
     * Get product by id
     * @param int $id
     * @return Product
     */
    public function getProductById($id) : Product
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Get products by category
     * @param int $categoryId
     * @return Collection
     */
    public function getProductByCategory($categoryId) : Collection
    {
        return Product::with('category')->where('category_id', $categoryId)->get();
    }
}
