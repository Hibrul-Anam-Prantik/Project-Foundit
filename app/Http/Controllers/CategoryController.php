<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Show every category as a table (the "read" part of CRUD).
     */
    public function index()
    {
        // latest() orders by created_at descending, so the category the admin
        // just added appears at the top instead of buried at the bottom.
        $categories = Category::latest()->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the empty form for creating a category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Validate the submitted form and save a new category.
     */
    public function store(Request $request)
    {
        // validate() stops here and redirects back with error messages if any
        // rule fails, so the code below only ever sees clean input.
        $validated = $request->validate([
            // unique:categories,name mirrors the database index, but gives the
            // admin a readable message instead of a MySQL crash on duplicates.
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        // Only these validated keys are passed, which matches $fillable on the
        // model and blocks any extra field a user might try to post.
        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form pre-filled with an existing category's values.
     * Laravel injects $category automatically by matching the {category}
     * route parameter to the row's id (route model binding).
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Validate the form and save changes to an existing category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // ignore() exempts this row's own name from the unique check;
                // without it, saving without renaming would flag itself as a duplicate.
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete one category.
     * Note: no item table exists yet, so nothing can reference a category and
     * no extra cleanup is needed. Revisit this once Items are added.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
