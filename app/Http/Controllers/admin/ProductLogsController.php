<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductLog;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductLogsController extends Controller
{
    /**
     * Display a listing of the product logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ProductLog::with(['product', 'admin']);

        // Search by product name
        if ($request->filled('search_product')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_product . '%');
            });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(15)->withQueryString();
        $products = Product::all();

        return view('admin.products.logs.index', compact('logs', 'products'));
    }

    /**
     * Display the specified product log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = ProductLog::with(['product', 'admin'])->findOrFail($id);
        return view('admin.products.logs.show', compact('log'));
    }
} 