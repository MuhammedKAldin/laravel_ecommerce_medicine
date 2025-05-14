<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');
            $activeCategory = null;

            if ($request->has('category')) {
                $categoryId = $request->query('category');
                $activeCategory = Category::findOrFail($categoryId);
                $query->where('category_id', $categoryId);
            }

            $products = $query->paginate(12)->withQueryString();
            $categories = Category::withCount('products')->get();
            
            if ($products->isEmpty() && $request->has('category')) {
                return redirect()->route('products')
                    ->with('info', 'No products found in this category.');
            }

            return view('products', compact('products', 'categories', 'activeCategory'));
        } catch (\Exception $e) {
            return redirect()->route('products')
                ->with('error', 'Unable to fetch products. Please try again.');
        }
    }

    public function category($id)
    {
        try {
            $activeCategory = Category::findOrFail($id);
            $products = Product::with('category')
                ->where('category_id', $id)
                ->paginate(12);
            $categories = Category::withCount('products')->get();

            if ($products->isEmpty()) {
                return redirect()->route('products')
                    ->with('info', 'No products found in this category.');
            }

            return view('products', compact('products', 'categories', 'activeCategory'));
        } catch (\Exception $e) {
            return redirect()->route('products')
                ->with('error', 'Category not found or invalid.');
        }
    }
    
    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);
            $categories = Category::withCount('products')->get();
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $id)
                ->limit(4)
                ->get();

            return view('product-single', compact('product', 'categories', 'relatedProducts'));
        } catch (\Exception $e) {
            return redirect()->route('products')
                ->with('error', 'Product not found.');
        }
    }
}
