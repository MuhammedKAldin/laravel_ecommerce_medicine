<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ItemCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ItemCategory::MedicalSupplies,
                'description' => 'Essential medical supplies for healthcare professionals and patients',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => ItemCategory::WoundCareSupplies,
                'description' => 'Specialized supplies for wound treatment and care',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => ItemCategory::MedicalEquipment,
                'description' => 'Professional medical equipment for healthcare facilities',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => ItemCategory::EnteralFeedingSupplies,
                'description' => 'Supplies for enteral nutrition and feeding support',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('categories')->insert($categories);
    }
} 