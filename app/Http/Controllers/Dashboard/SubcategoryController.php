<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SubcategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      Utilities::checkPermissions(['category.all', 'category.add', 'category.view', 'category.edit', 'category.delete']);

    $subcategories = Subcategory::with('Category')->get();
    return view('admin.subcategory.index', compact('subcategories'));
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
    return view('admin.subcategory.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      Utilities::checkPermissions(['category.all', 'category.add']);

    $request->validate([
      'name'        => 'required|max:255',
      'icon'        => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'category_id' => 'required|exists:categories,id',
      'status'      => 'required',
    ]);

    $fileUrl = null;

    if ($request->hasFile('icon')) {
      $filename = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $fileUrl = $request->icon->storeAs('images/subcategory_image', $filename, 'public');
    }

    $fileUrlCoverImage = null;

    if ($request->hasFile('cover_image')) {
      $fileUrlCoverImage = Rand() . '.' . $request->cover_image->getClientOriginalExtension();
      $fileUrlCoverImage = $request->cover_image->storeAs('images/subcategory_image', $fileUrlCoverImage, 'public');
    }

    Subcategory::create([
      'name'        => $request->input('name'),
      'icon'        => $fileUrl,
      'cover_image' => $fileUrlCoverImage,
      'category_id' => $request->input('category_id'),
      'status'      => $request->input('status'),
    ]);

    $message = 'Subcategory Created Successfully';
    return back()->with('success', $message);
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
  public function edit(Subcategory $subcategory)
  {
      Utilities::checkPermissions(['category.all', 'category.edit']);

      $categories = Category::where('status', 1)->get();
    return view('admin.subcategory.edit', compact('subcategory', 'categories'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Subcategory $subcategory)
  {
      Utilities::checkPermissions(['category.all', 'category.edit']);

    $request->validate([
      'name'        => 'required|max:255',
      'icon'        => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'category_id' => 'required|exists:categories,id',
      'status'      => 'required',
    ]);

    $fileUrl = $subcategory->icon;

    if ($request->hasFile('icon')) {
      $filePath = 'storage/' . $subcategory->icon;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $filename = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $fileUrl = $request->icon->storeAs('images/subcategory_image', $filename, 'public');
    }

    $fileUrlCoverImage = $subcategory->cover_image;

    if ($request->hasFile('cover_image')) {
      $filePath = 'storage/' . $subcategory->cover_image;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $fileUrlCoverImage = Rand() . '.' . $request->cover_image->getClientOriginalExtension();
      $fileUrlCoverImage = $request->cover_image->storeAs('images/subcategory_image', $fileUrlCoverImage, 'public');
    }

    $subcategory->update([
      'name'        => $request->input('name'),
      'icon'        => $fileUrl,
      'cover_image' => $fileUrlCoverImage,
      'category_id' => $request->input('category_id'),
      'status'      => $request->input('status'),
    ]);

    $message = 'Subcategory Updated Successfully';
    return back()->with('success', $message);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Subcategory $subcategory
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Exception
   */
  public function destroy(Subcategory $subcategory)
  {
      Utilities::checkPermissions(['category.all', 'category.delete']);

    /*if (Product::where('subcategory_id', $subcategory->id)->count() > 0) {
      return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
    }*/
    $filePath = 'storage/' . $subcategory->icon;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    $filePath = 'storage/' . $subcategory->cover_image;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    $subcategory->delete();
    return redirect()->back()->with('success', 'Delete Successfully.');
  }
}
