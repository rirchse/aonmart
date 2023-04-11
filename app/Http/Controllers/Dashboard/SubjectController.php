<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Product;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SubjectController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    Utilities::checkPermissions(['subject.all', 'subject.add', 'subject.view', 'subject.edit', 'subject.delete']);

    $subjects = Subject::all();
    return view('admin.subject.index', compact('subjects'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    Utilities::checkPermissions(['subject.all', 'subject.add']);

    return view('admin.subject.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    Utilities::checkPermissions(['subject.all', 'subject.add']);

    $request->validate([
      'name'    => 'required|max:255',
      'icon'    => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'details' => 'nullable',
      'status'  => 'required',
    ]);

    $fileUrl = null;

    if ($request->hasFile('icon')) {
      $filename = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $fileUrl = $request->icon->storeAs('images/subject_icon', $filename, 'public');
    }

    Subject::create([
      'name'    => $request->input('name'),
      'icon'    => $fileUrl,
      'details' => $request->input('details'),
      'status'  => $request->input('status'),
    ]);

    $message = 'Subject Created Successfully';
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
  public function edit(Subject $subject)
  {
    Utilities::checkPermissions(['subject.all', 'subject.edit']);

    return view('admin.subject.edit', compact('subject'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Subject $subject)
  {
    Utilities::checkPermissions(['subject.all', 'subject.edit']);

    $request->validate([
      'name'    => 'required|max:255',
      'icon'    => 'nullable|image|mimes:jpeg,png,jpg',
      'details' => 'nullable',
      'status'  => 'required',
    ]);

    $fileUrl = $subject->icon;

    if ($request->hasFile('icon')) {
      $filePath = 'storage/' . $subject->icon;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $filename = Rand() . '.' . $request->icon->getClientOriginalExtension();
      $fileUrl = $request->icon->storeAs('images/subject_icon', $filename, 'public');
    }

    $subject->update([
      'name'    => $request->input('name'),
      'icon'    => $fileUrl,
      'details' => $request->input('details'),
      'status'  => $request->input('status'),
    ]);

    $message = 'Subject Updated Successfully';
    return back()->with('success', $message);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Subject $subject)
  {
    Utilities::checkPermissions(['subject.all', 'subject.delete']);

    $filePath = 'storage/' . $subject->icon;
    if (File::exists($filePath)) {
      File::delete($filePath);
    }

    if (Product::where('subject_id', $subject->id)->count() > 0) {
      return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
    }

    $subject->delete();

    return redirect()->back()->with('success', 'Delete Successful.!');
  }
}
