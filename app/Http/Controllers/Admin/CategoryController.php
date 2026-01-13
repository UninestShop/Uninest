<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return view('admin.categories.index', [
                'title' => 'Categories',
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error fetching categories: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('admin.categories.create', [
                'title' => 'Create Category'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading create form: ' . $e->getMessage());
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $validated = $request->validated();
            Category::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);
            return redirect()->route('admin.categories.index')
                            ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function edit(Category $category)
    {
        try {
            return view('admin.categories.edit', [
                'title' => 'Edit Category',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $validated = $request->validated();
            $category->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);
            return redirect()->route('admin.categories.index')
                            ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')
                            ->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                            ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
