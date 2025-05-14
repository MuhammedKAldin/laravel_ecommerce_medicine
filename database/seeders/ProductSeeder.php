<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $categories = DB::table('categories')->get();
        
        foreach ($categories as $category) {
            $products = $this->getProductsForCategory($category->id, $category->name);
            DB::table('products')->insert($products);
        }
    }

    private function getProductsForCategory($categoryId, $categoryName): array
    {
        $products = [];
        $now = now();

        switch ($categoryName) {
            case 'Medical Supplies':
                $products = [
                    [
                        'name' => 'Disposable Face Masks',
                        'description' => 'High-quality 3-ply disposable face masks',
                        'price' => 12.99,
                        'stock' => 1000,
                        'image' => 'products/disposable-face-masks.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Nitrile Examination Gloves',
                        'description' => 'Powder-free nitrile examination gloves',
                        'price' => 15.99,
                        'stock' => 500,
                        'image' => 'products/nitrile-examination-gloves.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Digital Thermometer',
                        'description' => 'Quick-read digital thermometer',
                        'price' => 24.99,
                        'stock' => 200,
                        'image' => 'products/digital-thermometer.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Alcohol Swabs',
                        'description' => 'Sterile alcohol prep pads',
                        'price' => 8.99,
                        'stock' => 1000,
                        'image' => 'products/alcohol-swabs.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Stethoscope',
                        'description' => 'Professional dual-head stethoscope',
                        'price' => 49.99,
                        'stock' => 100,
                        'image' => 'products/stethoscope.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]
                ];
                break;

            case 'Wound Care Supplies':
                $products = [
                    [
                        'name' => 'Adhesive Bandages',
                        'description' => 'Various sizes of adhesive bandages',
                        'price' => 6.99,
                        'stock' => 1000,
                        'image' => 'products/adhesive-bandages.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Gauze Pads',
                        'description' => 'Sterile gauze pads 4"x4"',
                        'price' => 9.99,
                        'stock' => 500,
                        'image' => 'products/gauze-pads.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Medical Tape',
                        'description' => 'Hypoallergenic medical tape',
                        'price' => 4.99,
                        'stock' => 300,
                        'image' => 'products/medical-tape.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Wound Cleanser',
                        'description' => 'Antiseptic wound cleaning solution',
                        'price' => 12.99,
                        'stock' => 200,
                        'image' => 'products/wound-cleanser.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Compression Bandages',
                        'description' => 'Elastic compression bandages',
                        'price' => 14.99,
                        'stock' => 150,
                        'image' => 'products/compression-bandages.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]
                ];
                break;

            case 'Medical Equipment':
                $products = [
                    [
                        'name' => 'Blood Pressure Monitor',
                        'description' => 'Digital blood pressure monitor',
                        'price' => 59.99,
                        'stock' => 100,
                        'image' => 'products/blood-pressure-monitor.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Pulse Oximeter',
                        'description' => 'Fingertip pulse oximeter',
                        'price' => 34.99,
                        'stock' => 150,
                        'image' => 'products/pulse-oximeter.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Wheelchair',
                        'description' => 'Folding wheelchair with footrests',
                        'price' => 299.99,
                        'stock' => 50,
                        'image' => 'products/wheelchair.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Walking Cane',
                        'description' => 'Adjustable aluminum walking cane',
                        'price' => 24.99,
                        'stock' => 200,
                        'image' => 'products/walking-cane.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Nebulizer',
                        'description' => 'Portable compressor nebulizer system',
                        'price' => 49.99,
                        'stock' => 75,
                        'image' => 'products/nebulizer.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]
                ];
                break;

            case 'Enteral Feeding Supplies':
                $products = [
                    [
                        'name' => 'Feeding Tubes',
                        'description' => 'Enteral feeding tubes',
                        'price' => 19.99,
                        'stock' => 200,
                        'image' => 'products/feeding-tubes.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Feeding Bags',
                        'description' => 'Enteral feeding bags 1000ml',
                        'price' => 14.99,
                        'stock' => 300,
                        'image' => 'products/feeding-bags.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Extension Sets',
                        'description' => 'Feeding tube extension sets',
                        'price' => 9.99,
                        'stock' => 250,
                        'image' => 'products/extension-sets.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Feeding Syringes',
                        'description' => '60ml enteral feeding syringes',
                        'price' => 7.99,
                        'stock' => 500,
                        'image' => 'products/feeding-syringes.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ],
                    [
                        'name' => 'Tube Cleaning Brushes',
                        'description' => 'Feeding tube cleaning brushes',
                        'price' => 6.99,
                        'stock' => 150,
                        'image' => 'products/tube-cleaning-brushes.jpg',
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]
                ];
                break;
        }

        return $products;
    }
} 