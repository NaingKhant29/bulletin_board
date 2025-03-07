<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Store a newly created category in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        Category::create([
            'name' => $request->name,
        ]);
        return redirect()->route('posts.index')->with('success', 'Category created successfully!');
    }

    /**
     * Delete a category from the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $category = Category::find($request->category_id);

        if ($category) {
            $category->delete();
            return redirect()->route('posts.index')->with('success', 'Category deleted successfully!');
        }

        return redirect()->route('posts.index')->with('error', 'Category not found!');
    }
}
