<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display all products
     */
    public function index()
    {
        return Inertia::render('Product/Index', [
            // Get latest products with category
            'products' => Product::with('category')->latest()->get()
        ]);
    }

    /**
     * Show create product page
     */
    public function create()
    {
        return Inertia::render('Product/Create', [
            // Send categories for dropdown
            'categories' => Category::all()
        ]);
    }

    /**
     * Store new product in database
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name'        => 'required',
            'details'     => 'required',
            'price'       => 'required|numeric',
            'category_id' => 'required'
        ]);

        // Create product
        Product::create($request->all());

        // Redirect to product list
        return redirect()->route('product.index');
    }

    /**
     * Show edit product page
     */
    public function edit(Product $product)
    {
        return Inertia::render('Product/Edit', [
            // Send product data
            'product'    => $product,
            // Send categories for dropdown
            'categories' => Category::all()
        ]);
    }

    /**
     * Update product data
     */
    public function update(Request $request, Product $product)
    {
        // Validate request
        $request->validate([
            'name'        => 'required',
            'details'     => 'required',
            'price'       => 'required|numeric',
            'category_id' => 'required'
        ]);

        // Update product
        $product->update($request->all());

        // Redirect to product list
        return redirect()->route('product.index');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        // Delete product
        $product->delete();

        // Redirect back
        return redirect()->back();
    }
}
