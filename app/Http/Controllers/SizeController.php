<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SizeController extends Controller
{
    /**
     * Display sizes with search and pagination.
     */
    public function index(Request $request)
    {
        $query = Size::withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(
                'name',
                'like',
                '%' . $search . '%'
            );
        }

        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortBy, ['id', 'name', 'products_count', 'created_at'])) {
            $sortBy = 'id';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $sizes = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Size/Index', [
            'sizes' => $sizes,
            'filters' => [
                'search' => $request->search ?? '',
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'totalSizes' => Size::count(),
            'totalLinkedProducts' => \App\Models\Product::whereNotNull('size_id')->count(),
        ]);
    }

    /**
     * Show create size page.
     */
    public function create()
    {
        return Inertia::render('Size/Create');
    }

    /**
     * Store new size.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
        ]);

        Size::create($validated);

        return redirect()
            ->route('size.index')
            ->with(
                'success',
                'Size created successfully.'
            );
    }

    /**
     * Show edit size page.
     */
    public function edit(Size $size)
    {
        return Inertia::render('Size/Edit', [
            'size' => $size,
        ]);
    }

    /**
     * Update size.
     */
    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
        ]);

        $size->update($validated);

        return redirect()
            ->route('size.index')
            ->with(
                'success',
                'Size updated successfully.'
            );
    }

    /**
     * Delete size.
     */
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Size deleted successfully.'
            );
    }
}
