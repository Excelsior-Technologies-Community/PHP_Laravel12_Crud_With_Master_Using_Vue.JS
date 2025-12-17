<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index()
    {
        return Inertia::render('Category/Index', [
            'categories' => Category::latest()->get()
        ]);
    }

    /**
     * Show create category page
     */
    public function create()
    {
        return Inertia::render('Category/Create');
    }

    /**
     * Store new category in database
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required'
        ]);

        // Create category
        Category::create($request->all());

        // Redirect to category list
        return redirect()->route('category.index');
    }

    /**
     * Show edit category page
     */
    public function edit(Category $category)
    {
        return Inertia::render('Category/Edit', [
            'category' => $category
        ]);
    }

    /**
     * Update category data
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return redirect()->route('category.index');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back();
    }
}
