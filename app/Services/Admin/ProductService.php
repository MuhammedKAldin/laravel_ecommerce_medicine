<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getProducts(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        if ($request->filled('search_category')) {
            $query->where('category_id', $request->search_category);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function createProduct(array $data, $image)
    {
        $productData = $data;

        if ($image) {
            $imagePath = $image->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        return Product::create($productData);
    }

    public function updateProduct(Product $product, array $data, $image = null)
    {
        $productData = $data;

        if ($image) {
            // Delete old image if it exists
            if ($product->getRawOriginal('image')) {
                Storage::disk('public')->delete($product->getRawOriginal('image'));
            }

            // Store the new image
            $imagePath = $image->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        return $product->update($productData);
    }

    public function deleteProduct(Product $product)
    {
        // Delete the product image if it exists
        if ($product->getRawOriginal('image')) {
            Storage::disk('public')->delete($product->getRawOriginal('image'));
        }

        return $product->delete();
    }
} 