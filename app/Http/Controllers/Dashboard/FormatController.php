<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Format;
use App\Models\Product;
use Illuminate\Http\Request;

class FormatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      Utilities::checkPermissions(['format.all','format.add','format.view','format.edit','format.delete']);

        $formats = Format::all();
        return view('admin.format.index', compact('formats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      Utilities::checkPermissions(['format.all','format.add']);

        return view('admin.format.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      Utilities::checkPermissions(['format.all','format.add']);

        $request->validate([
            'name'     => 'required|max:255',
            'status'   => 'required',
        ]);

        Format::create([
            'name'       => $request->input('name'),
            'status'     => $request->input('status'),
        ]);

        $message = 'Format Created Successfully';
        return back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Format $format)
    {
      Utilities::checkPermissions(['format.all','format.edit']);

        return view('admin.format.edit', compact('format'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Format $format)
    {
      Utilities::checkPermissions(['format.all','format.edit']);

        $request->validate([
            'name'     => 'required|max:255',
            'status'   => 'required',
        ]);

        $format->update([
            'name'       => $request->input('name'),
            'status'     => $request->input('status'),
        ]);

        $message = 'Format Updated Successfully';
        return back()->with('success', $message);
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param Format $format
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Exception
   */
    public function destroy(Format $format)
    {
      Utilities::checkPermissions(['format.all','format.delete']);

      if(Product::where('format_id', $format->id)->count() > 0){
        return redirect()->back()->with('error', 'Can\'t Delete, Item Exist In Product.');
      }

      $format->delete();
      return redirect()->back()->with('success', 'Delete Successful.!');
    }
}
