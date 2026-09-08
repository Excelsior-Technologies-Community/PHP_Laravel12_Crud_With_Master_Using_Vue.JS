<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class ProductDashboardController extends Controller
{
    /**
     * Product and inventory statistics dashboard.
     */
    public function index()
    {
        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $totalStock = Product::sum('stock_quantity');

        $outOfStock = Product::where(
            'stock_quantity',
            '<=',
            0
        )->count();

        $lowStock = Product::where('stock_quantity', '>', 0)
            ->whereColumn(
                'stock_quantity',
                '<=',
                'low_stock_threshold'
            )
            ->count();

        $inStock = Product::where('stock_quantity', '>', 0)
            ->whereColumn(
                'stock_quantity',
                '>',
                'low_stock_threshold'
            )
            ->count();

        $totalInventoryValue = Product::selectRaw(
            'SUM(price * stock_quantity) as total'
        )->value('total') ?? 0;

        $categoryStats = Category::withCount('products')
            ->orderByDesc('products_count')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                ];
            });

        $lowStockProducts = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->whereColumn(
                'stock_quantity',
                '<=',
                'low_stock_threshold'
            )
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        $outOfStockProducts = Product::with('category')
            ->where('stock_quantity', '<=', 0)
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Product/Dashboard', [
            'statistics' => [
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_stock' => $totalStock,
                'in_stock' => $inStock,
                'low_stock' => $lowStock,
                'out_of_stock' => $outOfStock,
                'total_inventory_value' => $totalInventoryValue,
            ],

            'categoryStats' => $categoryStats,

            'lowStockProducts' => $lowStockProducts,

            'outOfStockProducts' => $outOfStockProducts,
        ]);
    }
}