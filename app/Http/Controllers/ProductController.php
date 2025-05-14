<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $result = $this->productService->getProducts($request);

        $products = $result['products'];
        $activeCategory = $result['activeCategory'];
        $categories = $this->categoryService->getAllCategoriesWithCount();
        
        return view('products', compact('products', 'categories', 'activeCategory'));
    }

    public function category($id)
    {
        $result = $this->productService->getProductByCategory($id);

        $products = $result['products'];
        $activeCategory = $result['activeCategory'];
        $categories = $this->categoryService->getAllCategoriesWithCount();

        return view('products', compact('products', 'categories', 'activeCategory'));
    }
    
    public function show($id)
    {
        $product = $this->productService->getProductById($id);
        $relatedProducts = $this->productService->getRelatedProducts($product);

        return view('product-single', compact('product', 'relatedProducts'));
    }
}
