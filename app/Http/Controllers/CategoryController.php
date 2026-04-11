<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
{
    $request->validate([
        'name' => 'required|unique:categories,name',
        'slogan' => 'required|string',
        'subcategories.*' => 'nullable|string|max:255'
    ]);

    $category = Category::create([
        'name' => $request->name,
        'slogan' => $request->slogan,
    ]);

    if ($request->has('subcategories')) {
        foreach ($request->subcategories as $subName) {
            if ($subName) {
                $category->subCategories()->create(['name' => $subName]);
            }
        }
    }

    return redirect('/admin/categories')->with('message', 'Category added successfully.');
}
public function deleteCategory(Category $category)
    {
        $category->delete();
        return response()->json(['status'=>"removed"]);
    }
 public function editCategory(Request $request, Category $category)
{

    $request->validate([
        'name' => 'required|unique:categories,name,' . $category->id,
        'slogan' => 'nullable|string',
        'existing_subcategories.*' => 'nullable|string|max:255',
        'new_subcategories.*' => 'nullable|string|max:255',
    ]);

    $category->update([
        'name' => $request->name,
        'slogan' => $request->slogan,
    ]);

    // Update existing subcategories
    if ($request->has('existing_subcategories')) {
        foreach ($request->existing_subcategories as $subId => $subName) {
            if ($subName) {
                SubCategory::where('id', $subId)->update(['name' => $subName]);
            }
        }
    }

    // Add new subcategories
    if ($request->has('new_subcategories')) {
        foreach ($request->new_subcategories as $newSub) {
            if ($newSub) {
                $category->subCategories()->create(['name' => $newSub]);
            }
        }
    }

    return redirect('/admin/categories')->with('message', 'Category updated successfully.');
}


public function deleteSub(SubCategory $subCategory){
    $subCategory->delete();
    return response()->json(['status'=>"removed"]);
}
}