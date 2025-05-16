<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ProductLogsService;
use Illuminate\Http\Request;

class ProductLogsController extends Controller
{
    protected $productLogsService;

    public function __construct(ProductLogsService $productLogsService)
    {
        $this->productLogsService = $productLogsService;
    }

    /**
     * Display a listing of the product logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logs = $this->productLogsService->getLogs($request);
        $products = $this->productLogsService->getAllProducts();

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
        $log = $this->productLogsService->getLogDetails($id);
        return view('admin.products.logs.show', compact('log'));
    }
} 