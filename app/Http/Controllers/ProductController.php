<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display products with search and filters.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by product name or details
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {

            if ($request->stock_status === 'in_stock') {
                $query->where('stock_quantity', '>', 0)
                    ->whereColumn(
                        'stock_quantity',
                        '>',
                        'low_stock_threshold'
                    );
            }

            if ($request->stock_status === 'low_stock') {
                $query->where('stock_quantity', '>', 0)
                    ->whereColumn(
                        'stock_quantity',
                        '<=',
                        'low_stock_threshold'
                    );
            }

            if ($request->stock_status === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        // Minimum price
        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        // Maximum price
        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        $products = $query
            ->latest()
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'details' => $product->details,
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                    'category' => $product->category,
                    'stock_quantity' => $product->stock_quantity,
                    'low_stock_threshold' => $product->low_stock_threshold,
                    'stock_status' => $product->stock_status,
                ];
            });

        return Inertia::render('Product/Index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),

            'filters' => [
                'search' => $request->search ?? '',
                'category_id' => $request->category_id ?? '',
                'stock_status' => $request->stock_status ?? '',
                'min_price' => $request->min_price ?? '',
                'max_price' => $request->max_price ?? '',
            ],
        ]);
    }

    /**
     * Show create product page.
     */
    public function create()
    {
        return Inertia::render('Product/Create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',

            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()
            ->route('product.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product page.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Product/Edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',

            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()
            ->route('product.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->back()
            ->with('success', 'Product deleted successfully.');
    }
}