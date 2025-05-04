<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string'
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Check if slug exists and make it unique if necessary
        $count = 1;
        $originalSlug = $validated['slug'];
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        $category = Category::create($validated);
        
        if ($request->wantsJson()) {
            return response()->json($category);
        }
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string'
        ]);

        // Update slug if name has changed
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Check if slug exists and make it unique if necessary
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Category::where('slug', $validated['slug'])->where('id', '!=', $category->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        $category->update($validated);
        
        if ($request->wantsJson()) {
            return response()->json($category);
        }
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        if ($category->campaigns()->exists()) {
            return response()->json([
                'error' => 'Cannot delete category with associated campaigns'
            ], 422);
        }
        
        $category->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Category deleted successfully']);
        }
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
} 