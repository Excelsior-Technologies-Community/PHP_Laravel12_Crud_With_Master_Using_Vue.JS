<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Size;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed 10 Sizes
        $sizes = [
            'XS - Extra Small',
            'S - Small',
            'M - Medium',
            'L - Large',
            'XL - Extra Large',
            'XXL - Double Extra Large',
            'Size 28 (Waist)',
            'Size 32 (Waist)',
            'Size 36 (Waist)',
            'Free Size (Universal)',
        ];

        $sizeMap = [];
        foreach ($sizes as $sizeName) {
            $s = Size::firstOrCreate(['name' => $sizeName]);
            $sizeMap[$sizeName] = $s->id;
        }

        // 2. Seed 10 Categories
        $categories = [
            "Men's Fashion",
            "Women's Fashion",
            "Kids Wear & Toys",
            "Electronics & Gadgets",
            "Footwear & Shoes",
            "Sports & Fitness",
            "Home & Kitchen",
            "Beauty & Personal Care",
            "Watches & Jewelry",
            "Bags & Luggage",
        ];

        $categoryMap = [];
        foreach ($categories as $catName) {
            $cat = Category::firstOrCreate(['name' => $catName]);
            $categoryMap[$catName] = $cat->id;
        }

        // 3. Seed 10 Products
        $products = [
            [
                'name' => 'Slim Fit Cotton Casual Shirt',
                'details' => 'Premium 100% breathable cotton slim fit shirt with button-down collar and stylish modern finish.',
                'price' => 1499.00,
                'category_id' => $categoryMap["Men's Fashion"],
                'size_id' => $sizeMap['L - Large'],
                'stock_quantity' => 45,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Designer Floral Print Anarkali Kurti',
                'details' => 'Elegant traditional printed kurti with fine embroidery, 3/4 sleeves, and premium georgette fabric.',
                'price' => 2299.00,
                'category_id' => $categoryMap["Women's Fashion"],
                'size_id' => $sizeMap['M - Medium'],
                'stock_quantity' => 30,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'Kids Organic Cotton Cartoon T-Shirt',
                'details' => 'Ultra-soft, skin-friendly daily wear graphic tee with vibrant colors for toddlers and young kids.',
                'price' => 599.00,
                'category_id' => $categoryMap["Kids Wear & Toys"],
                'size_id' => $sizeMap['S - Small'],
                'stock_quantity' => 60,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'Noise-Cancelling Wireless Bluetooth Earbuds',
                'details' => 'High-bass 35dB active noise cancellation with 40-hour battery life, IPX5 water resistance, and Type-C fast charging.',
                'price' => 2999.00,
                'category_id' => $categoryMap["Electronics & Gadgets"],
                'size_id' => $sizeMap['Free Size (Universal)'],
                'stock_quantity' => 25,
                'low_stock_threshold' => 8,
            ],
            [
                'name' => 'Lightweight Breathable Running Shoes',
                'details' => 'Ergonomic athletic running sneakers with shock-absorbing memory foam insole and slip-resistant grip.',
                'price' => 3499.00,
                'category_id' => $categoryMap["Footwear & Shoes"],
                'size_id' => $sizeMap['Size 32 (Waist)'],
                'stock_quantity' => 18,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'Stainless Steel Insulated Sports Bottle (1L)',
                'details' => 'Double-walled vacuum insulated flask keeping beverages cold for 24h & hot for 12h. BPA-free and leakproof.',
                'price' => 899.00,
                'category_id' => $categoryMap["Sports & Fitness"],
                'size_id' => $sizeMap['Free Size (Universal)'],
                'stock_quantity' => 50,
                'low_stock_threshold' => 12,
            ],
            [
                'name' => 'Cast Iron Non-Stick Cookware Frying Pan',
                'details' => 'Pre-seasoned heavy-duty cast iron skillet suitable for gas, electric, and induction cooktops.',
                'price' => 1850.00,
                'category_id' => $categoryMap["Home & Kitchen"],
                'size_id' => $sizeMap['Free Size (Universal)'],
                'stock_quantity' => 12,
                'low_stock_threshold' => 4,
            ],
            [
                'name' => 'Vitamin C Glow Brightening Face Serum (30ml)',
                'details' => 'Dermatologist tested formula enriched with 15% pure Vitamin C and Hyaluronic Acid for glowing, youthful skin.',
                'price' => 749.00,
                'category_id' => $categoryMap["Beauty & Personal Care"],
                'size_id' => $sizeMap['Free Size (Universal)'],
                'stock_quantity' => 80,
                'low_stock_threshold' => 20,
            ],
            [
                'name' => 'Classic Stainless Steel Chronograph Quartz Watch',
                'details' => 'Water-resistant luxury analog wrist watch with tachymeter bezel, calendar date display, and scratch-resistant sapphire glass.',
                'price' => 4599.00,
                'category_id' => $categoryMap["Watches & Jewelry"],
                'size_id' => $sizeMap['Free Size (Universal)'],
                'stock_quantity' => 8,
                'low_stock_threshold' => 3,
            ],
            [
                'name' => 'Waterproof Laptop Backpack with USB Charging Port',
                'details' => 'Spacious multi-compartment travel backpack fits up to 15.6 inch laptops with anti-theft back pocket.',
                'price' => 1799.00,
                'category_id' => $categoryMap["Bags & Luggage"],
                'size_id' => $sizeMap['Free Size (Universal)'],
                'stock_quantity' => 35,
                'low_stock_threshold' => 10,
            ],
        ];

        foreach ($products as $prodData) {
            Product::updateOrCreate(
                ['name' => $prodData['name']],
                $prodData
            );
        }
    }
}
