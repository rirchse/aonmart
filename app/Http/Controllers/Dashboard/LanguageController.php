<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Language;
use App\Models\Product;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    Utilities::checkPermissions(['language.all', 'language.add', 'language.view', 'language.edit', 'language.delete']);

    $languages = Language::all();
    return view('admin.language.index', compact('languages'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    Utilities::checkPermissions(['language.all', 'language.add']);

    return view('admin.language.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    Utilities::checkPermissions(['language.all', 'language.add']);

    $request->validate([
      'name'   => 'required|max:255',
      'status' => 'required',
    ]);

    Language::create([
      'name'   => $request->input('name'),
      'status' => $request->input('status'),
    ]);

    $message = 'Language Created Successfully';
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
  public function edit(Language $language)
  {
    Utilities::checkPermissions(['language.all', 'language.edit']);

    return view('admin.language.edit', compact('language'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Language $language)
  {
    Utilities::checkPermissions(['language.all', 'language.edit']);

    $request->validate([
      'name'   => 'required|max:255',
      'status' => 'required',
    ]);

    $language->update([
      'name'   => $request->input('name'),
      'status' => $request->input('status'),
    ]);

    $message = 'Language Updated Successfully';
    return back()->with('success', $message);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Language $language
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Exception
   */
  public function destroy(Language $language)
  {
    Utilities::checkPermissions(['language.all', 'language.delete']);

    if (Product::where('language_id', $language->id)->count() > 0) {
      return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
    }

    $language->delete();
    return redirect()->back()->with('success', 'Delete Successful.!');
  }
}
