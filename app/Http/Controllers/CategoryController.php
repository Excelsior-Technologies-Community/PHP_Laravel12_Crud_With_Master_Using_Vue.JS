<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display categories with search, pagination and product count.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        /*
        |--------------------------------------------------------------------------
        | Category Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(
                'name',
                'like',
                '%' . $search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $categories = $query
            ->orderBy('name')
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('Category/Index', [
            'categories' => $categories,

            'filters' => [
                'search' => $request->search ?? '',
            ],
        ]);
    }


    /**
     * Show create category page.
     */
    public function create()
    {
        return Inertia::render('Category/Create');
    }


    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($validated);

        return redirect()
            ->route('category.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }


    /**
     * Show edit category page.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Category/Edit', [
            'category' => $category,
        ]);
    }


    /**
     * Update category.
     */
    public function update(
        Request $request,
        Category $category
    ) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return redirect()
            ->route('category.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }


    /**
     * Delete category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }
}
