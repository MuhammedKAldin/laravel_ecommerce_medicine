<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        ProductLog::create([
            'product_id' => $product->id,
            'action' => 'created',
            'changed_by' => Auth::id(),
            'changes' => [
                'old' => null,
                'new' => $product->toArray()
            ]
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $changes = [
            'old' => array_intersect_key($product->getOriginal(), $product->getDirty()),
            'new' => $product->getDirty()
        ];

        if (!empty($changes['new'])) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'updated',
                'changed_by' => Auth::id(),
                'changes' => $changes
            ]);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        ProductLog::create([
            'product_id' => $product->id,
            'action' => 'deleted',
            'changed_by' => Auth::id(),
            'changes' => [
                'old' => $product->toArray(),
                'new' => null
            ]
        ]);
    }
} 