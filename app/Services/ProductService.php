<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Get products
     * @param Request $request
     * @return array
     */
    public function getProducts(Request $request)
    {
        $query = Product::with('category');
        $activeCategory = null;

        if ($request->has('category')) {
            $categoryId = $request->query('category');
            $activeCategory = Category::findOrFail($categoryId);
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(12)->withQueryString();

        return [
            'products' => $products,
            'activeCategory' => $activeCategory
        ];
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

    /**
     * Get related products
     * @param Product $product
     * @param int $limit
     * @return Collection
     */
    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit($limit)
            ->get();
    }
}
