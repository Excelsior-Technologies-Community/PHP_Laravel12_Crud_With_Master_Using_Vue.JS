<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display products with search, filters, sorting and pagination.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stock_status')) {

            if ($request->stock_status === 'in_stock') {
                $query->where(
                    'stock_quantity',
                    '>',
                    0
                )->whereColumn(
                    'stock_quantity',
                    '>',
                    'low_stock_threshold'
                );
            }

            if ($request->stock_status === 'low_stock') {
                $query->where(
                    'stock_quantity',
                    '>',
                    0
                )->whereColumn(
                    'stock_quantity',
                    '<=',
                    'low_stock_threshold'
                );
            }

            if ($request->stock_status === 'out_of_stock') {
                $query->where(
                    'stock_quantity',
                    '<=',
                    0
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'name',
            'price',
            'stock_quantity',
            'created_at',
        ];

        /*
        | Default sorting:
        | ID Ascending
        |
        | 1, 2, 3, 4, 5...
        */

        $sortBy = $request->input(
            'sort_by',
            'id'
        );

        $sortOrder = $request->input(
            'sort_order',
            'asc'
        );

        /*
        | Prevent invalid sorting values
        */

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->orderBy(
                $sortBy,
                $sortOrder
            )
            ->paginate(5)
            ->withQueryString()
            ->through(function ($product) {

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

        /*
        |--------------------------------------------------------------------------
        | Return Inertia Page
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Product/Index',
            [
                'products' => $products,

                'categories' => Category::orderBy('name')->get(),

                'filters' => [
                    'search' => $request->search ?? '',
                    'category_id' => $request->category_id ?? '',
                    'stock_status' => $request->stock_status ?? '',
                    'min_price' => $request->min_price ?? '',
                    'max_price' => $request->max_price ?? '',

                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ],
            ]
        );
    }


    /**
     * Show create product page.
     */
    public function create()
    {
        return Inertia::render(
            'Product/Create',
            [
                'categories' => Category::orderBy('name')->get(),
            ]
        );
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
            ->with(
                'success',
                'Product created successfully.'
            );
    }


    /**
     * Show edit product page.
     */
    public function edit(Product $product)
    {
        return Inertia::render(
            'Product/Edit',
            [
                'product' => $product,

                'categories' => Category::orderBy('name')->get(),
            ]
        );
    }


    /**
     * Update product.
     */
    public function update(
        Request $request,
        Product $product
    ) {
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
            ->with(
                'success',
                'Product updated successfully.'
            );
    }


    /**
     * Delete single product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('product.index')
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }


    /**
     * Bulk delete products.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:products,id',
        ]);

        Product::whereIn(
            'id',
            $validated['ids']
        )->delete();

        return redirect()
            ->route('product.index')
            ->with(
                'success',
                count($validated['ids']) .
                    ' product(s) deleted successfully.'
            );
    }


    /**
     * Export products to CSV.
     */
    public function export(Request $request)
    {
        $query = Product::with('category');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'details',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stock_status')) {

            if ($request->stock_status === 'in_stock') {

                $query->where(
                    'stock_quantity',
                    '>',
                    0
                )->whereColumn(
                    'stock_quantity',
                    '>',
                    'low_stock_threshold'
                );
            }

            if ($request->stock_status === 'low_stock') {

                $query->where(
                    'stock_quantity',
                    '>',
                    0
                )->whereColumn(
                    'stock_quantity',
                    '<=',
                    'low_stock_threshold'
                );
            }

            if ($request->stock_status === 'out_of_stock') {

                $query->where(
                    'stock_quantity',
                    '<=',
                    0
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Price Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CSV Sorting
        |--------------------------------------------------------------------------
        |
        | Export also uses ID ascending.
        |
        */

        $products = $query
            ->orderBy('id', 'asc')
            ->get();

        $filename =
            'products-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($products) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                | CSV Header
                */

                fputcsv(
                    $handle,
                    [
                        'ID',
                        'Name',
                        'Details',
                        'Price',
                        'Category',
                        'Stock Quantity',
                        'Low Stock Threshold',
                        'Stock Status',
                    ]
                );

                /*
                | CSV Data
                */

                foreach ($products as $product) {

                    fputcsv(
                        $handle,
                        [
                            $product->id,
                            $product->name,
                            $product->details,
                            $product->price,
                            $product->category?->name ?? 'N/A',
                            $product->stock_quantity,
                            $product->low_stock_threshold,
                            $product->stock_status,
                        ]
                    );
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }


    /**
     * Duplicate an existing product.
     */
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();

        $newProduct->name =
            $product->name . ' (Copy)';

        $newProduct->save();

        return redirect()
            ->route('product.index')
            ->with(
                'success',
                'Product duplicated successfully.'
            );
    }
}
