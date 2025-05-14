<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * Get all categories with product count
     * @return Collection
     */
    public function getAllCategoriesWithCount(): Collection
    {
        return Category::withCount('products')->get();
    }

    /**
     * Find category by ID
     * @param int $id
     * @return Category
     */
    public function findCategory($id): Category
    {
        return Category::findOrFail($id);
    }
} 