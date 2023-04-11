<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TagController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    Utilities::checkPermissions(['tag.all', 'tag.add', 'tag.view', 'tag.edit', 'tag.delete']);

    $tags = Tag::all();
    return view('admin.tag.index', compact('tags'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    Utilities::checkPermissions(['tag.all', 'tag.add']);

    return view('admin.tag.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    Utilities::checkPermissions(['tag.all', 'tag.add']);

    $request->validate([
      'name'   => 'required|max:255',
      'icon'   => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'status' => 'required',
    ]);

    $fileUrl = null;

    if ($request->hasFile('icon')) {
      $filename = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $fileUrl = $request->icon->storeAs('images/tag_icon', $filename, 'public');
    }

    Tag::create([
      'name'    => $request->input('name'),
      'icon'    => $fileUrl,
      'details' => $request->input('details'),
      'status'  => $request->input('status'),
    ]);

    $message = 'Tag Created Successfully';
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
  public function edit(Tag $tag)
  {
    Utilities::checkPermissions(['tag.all', 'tag.edit']);

    return view('admin.tag.edit', compact('tag'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Tag $tag)
  {
    Utilities::checkPermissions(['tag.all', 'tag.edit']);

    $request->validate([
      'name'   => 'required|max:255',
      'icon'   => 'nullable|image|mimes:jpeg,png,jpg',
      'status' => 'required',
    ]);

    $fileUrl = $tag->icon;

    if ($request->hasFile('icon')) {
      $filePath = 'storage/' . $tag->icon;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $filename = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $fileUrl = $request->icon->storeAs('images/tag_icon', $filename, 'public');
    }

    $tag->update([
      'name'    => $request->input('name'),
      'icon'    => $fileUrl,
      'details' => $request->input('details'),
      'status'  => $request->input('status'),
    ]);

    $message = 'Tag Updated Successfully';
    return back()->with('success', $message);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Tag $tag
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Exception
   */
  public function destroy(Tag $tag)
  {
    Utilities::checkPermissions(['tag.all', 'tag.delete']);

    $filePath = 'storage/' . $tag->icon;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }
    if (Product::where('tag_id', $tag->id)->count() > 0) {
      return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
    }

    $tag->delete();
    return redirect()->back()->with('success', 'Delete Successful.!');

  }
}
