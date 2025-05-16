<?php

namespace App\Services\Admin;

use App\Models\ProductLog;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductLogsService
{
    public function getLogs(Request $request)
    {
        $query = ProductLog::with(['product' => function($query) {
            $query->withTrashed();
        }, 'admin']);

        if ($request->filled('search_product')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->withTrashed()->where('name', 'like', '%' . $request->search_product . '%');
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        return $query->latest()->paginate(15)->withQueryString();
    }

    public function getLogDetails($id)
    {
        return ProductLog::with(['product' => function($query) {
            $query->withTrashed();
        }, 'admin'])->findOrFail($id);
    }

    public function getAllProducts()
    {
        return Product::withTrashed()->get();
    }
} 