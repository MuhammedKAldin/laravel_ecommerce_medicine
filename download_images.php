<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Function to download image from URL and save it to storage
function downloadImage($url, $filename) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($contents && $httpCode === 200) {
        Storage::disk('public')->put('products/' . $filename, $contents);
        return true;
    }
    return false;
}

// Product images to download with their Unsplash URLs
$products = [
    // Medical Supplies
    [
        'name' => 'disposable-face-masks',
        'url' => 'https://images.unsplash.com/photo-1584634731339-252c581abfc5?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'nitrile-examination-gloves',
        'url' => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'digital-thermometer',
        'url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'alcohol-swabs',
        'url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'stethoscope',
        'url' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=800&auto=format&fit=crop'
    ],

    // Wound Care Supplies
    [
        'name' => 'adhesive-bandages',
        'url' => 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'gauze-pads',
        'url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'medical-tape',
        'url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'wound-cleanser',
        'url' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'compression-bandages',
        'url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=800&auto=format&fit=crop'
    ],

    // Medical Equipment
    [
        'name' => 'blood-pressure-monitor',
        'url' => 'https://images.unsplash.com/photo-1581595220892-b0739db3ba8c?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'pulse-oximeter',
        'url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'wheelchair',
        'url' => 'https://images.unsplash.com/photo-1611242320536-f12d3541249b?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'walking-cane',
        'url' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'nebulizer',
        'url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&auto=format&fit=crop'
    ],

    // Enteral Feeding Supplies
    [
        'name' => 'feeding-tubes',
        'url' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'feeding-bags',
        'url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'extension-sets',
        'url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'feeding-syringes',
        'url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&auto=format&fit=crop'
    ],
    [
        'name' => 'tube-cleaning-brushes',
        'url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=800&auto=format&fit=crop'
    ]
];

// Create products directory if it doesn't exist
if (!Storage::disk('public')->exists('products')) {
    Storage::disk('public')->makeDirectory('products');
}

// Download each image
foreach ($products as $product) {
    $extension = 'jpg';
    $filename = $product['name'] . '.' . $extension;
    
    if (downloadImage($product['url'], $filename)) {
        echo "Downloaded: " . $filename . "\n";
    } else {
        echo "Failed to download: " . $filename . "\n";
    }
}

echo "Image download completed!\n"; 