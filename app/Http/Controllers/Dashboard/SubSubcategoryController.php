<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class SubSubcategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      Utilities::checkPermissions(['category.all', 'category.add', 'category.view', 'category.edit', 'category.delete']);

    $subSubcategories = SubSubcategory::with('Category', 'Subcategory')->get();
    return response()->view('admin.subSubcategory.index', compact('subSubcategories'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      Utilities::checkPermissions(['category.all', 'category.add']);

    $categories = Category::where('status', 1)->get();
    return response()->view('admin.subSubcategory.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
      Utilities::checkPermissions(['category.all', 'category.add']);

    $request->validate([
      'name'           => 'required|max:255',
      'icon'           => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'subcategory_id' => 'required|exists:subcategories,id',
      'category_id'    => 'required|exists:categories,id',
      'status'         => 'required',
    ]);

    $icon = null;
    if ($request->hasFile('icon')) {
      $icon = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $icon = $request->icon->storeAs('images/subSubcategory_image', $icon, 'public');
    }

    $coverImage = null;
    if ($request->hasFile('cover_image')) {
      $coverImage = Rand() . '.' . $request->cover_image->getClientOriginalExtension();
      $coverImage = $request->cover_image->storeAs('images/subSubcategory_image', $coverImage, 'public');
    }

    SubSubcategory::create([
      'name'           => $request->input('name'),
      'icon'           => $icon,
      'cover_image'    => $coverImage,
      'category_id'    => $request->input('category_id'),
      'subcategory_id' => $request->input('subcategory_id'),
      'status'         => $request->input('status'),
    ]);

    $message = 'SubSubcategory Created Successfully';
    return back()->with('success', $message);
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Models\SubSubcategory $subSubcategory
   * @return \Illuminate\Http\Response
   */
  public function show(SubSubcategory $subSubcategory)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\SubSubcategory $subSubcategory
   * @return \Illuminate\Http\Response
   */
  public function edit(SubSubcategory $subSubcategory)
  {
      Utilities::checkPermissions(['category.all', 'category.edit']);

    $categories = Category::where('status', 1)->get();
    $subcategories = Category::findOrFail($subSubcategory->category_id)->Subcategories;
    return response()->view('admin.subSubcategory.edit', compact('subSubcategory', 'categories', 'subcategories'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\SubSubcategory $subSubcategory
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, SubSubcategory $subSubcategory)
  {
      Utilities::checkPermissions(['category.all', 'category.edit']);

    $request->validate([
      'name'           => 'required|max:255',
      'icon'           => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'category_id'    => 'required|exists:categories,id',
      'subcategory_id' => 'required|exists:subcategories,id',
      'status'         => 'required',
    ]);

    $icon = $subSubcategory->icon;
    if ($request->hasFile('icon')) {
      $filePath = 'storage/' . $subSubcategory->icon;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $icon = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $icon = $request->icon->storeAs('images/subSubcategory_image', $icon, 'public');
    }

    $coverImage = $subSubcategory->cover_image;
    if ($request->hasFile('cover_image')) {
      $filePath = 'storage/' . $subSubcategory->cover_image;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $coverImage = Rand() . '.' . $request->cover_image->getClientOriginalExtension();
      $coverImage = $request->cover_image->storeAs('images/subSubcategory_image', $coverImage, 'public');
    }

    $subSubcategory->update([
      'name'           => $request->input('name'),
      'icon'           => $icon,
      'cover_image'    => $coverImage,
      'category_id'    => $request->input('category_id'),
      'subcategory_id' => $request->input('subcategory_id'),
      'status'         => $request->input('status'),
    ]);

    $message = 'SubSubcategory Updated Successfully';
    return back()->with('success', $message);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\SubSubcategory $subSubcategory
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(SubSubcategory $subSubcategory)
  {
      Utilities::checkPermissions(['category.all', 'category.delete']);
    /*if (Product::where('subcategory_id', $subSubcategory->id)->count() > 0) {
      return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
    }*/
    $filePath = 'storage/' . $subSubcategory->icon;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    $filePath = 'storage/' . $subSubcategory->cover_image;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    $subSubcategory->delete();
    return back()->with('success', 'SubSubcategory Deleted Successfully.');
  }
}
