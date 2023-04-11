<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    Utilities::checkPermissions(['category.all', 'category.add', 'category.view', 'category.edit', 'category.delete']);

    $cats = Category::orderBy('name')->get();
    return response()->view('admin.category.index', compact('cats'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    Utilities::checkPermissions(['category.all', 'category.add']);
    return response()->view('admin.category.create');
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
      'name'        => 'required|string|max:255|unique:categories',
      'icon'        => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'cover_img'   => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'description' => 'nullable|string|max:1000',
    ]);

    $icon_path = null;
    if ($request->hasFile('icon')) {
      $icon_path = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $icon_path = $request->icon->storeAs('images/category_img', $icon_path, 'public');
    }

    $cover_path = null;
    if ($request->hasFile('cover_img')) {
      $cover_path = Rand() . '.' . $request->cover_img->getClientOriginalExtension();
      $cover_path = $request->cover_img->storeAs('images/category_img', $cover_path, 'public');
    }

    Category::create([
      'name'        => $request->input('name'),
      'description' => $request->input('description'),
      'icon'        => $icon_path,
      'cover_img'   => $cover_path,
    ]);
    return redirect()->back()->with('success', 'Save Successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    Utilities::checkPermissions(['category.all', 'category.edit']);
    $cat = Category::find($id);
    return response()->view('admin.category.edit', compact('cat'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, $id)
  {
    Utilities::checkPermissions(['category.all', 'category.edit']);
    $request->validate([
      'name'        => 'required|string|max:255|unique:categories,id,' . $id,
      'icon'        => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'cover_img'   => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'description' => 'nullable|string|max:1000',
      'status'      => 'required',
    ]);
    $category = Category::findOrFail($id);

    $oldIcon = $category->icon;
    if ($request->hasFile('icon')) {
      if (File::exists('storage/' . $oldIcon)) {
        File::delete('storage/' . $oldIcon);
      }

      $oldIcon = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $oldIcon = $request->icon->storeAs('images/category_img', $oldIcon, 'public');
    }

    $oldCoverImage = $category->cover_img;
    if ($request->hasFile('cover_img')) {
      if (File::exists('storage/' . $oldCoverImage)) {
        File::delete('storage/' . $oldCoverImage);
      }

      $oldCoverImage = Rand() . '.' . $request->cover_img->getClientOriginalExtension();
      $oldCoverImage = $request->cover_img->storeAs('images/category_img', $oldCoverImage, 'public');
    }

    $category->update([
      'name'        => $request->input('name'),
      'description' => $request->input('description'),
      'icon'        => $oldIcon,
      'cover_img'   => $oldCoverImage,
      'status'      => $request->input('status'),
    ]);
    return back()->with('success', 'Update Successful.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Exception
   */
  public function destroy($id)
  {
    Utilities::checkPermissions(['category.all', 'category.delete']);

    /*if (Product::where('category_id', $id)->count() > 0) {
      return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
    }*/
    $category = Category::findOrFail($id);
    $filePath = 'storage/' . $category->icon;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    $filePath = 'storage/' . $category->cover_img;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    $category->delete();
    return redirect()->back()->with('success', 'Delete Successful.!');
  }
}
