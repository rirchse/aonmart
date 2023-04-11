<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Utilities::checkPermissions(['unit.all', 'unit.add', 'unit.view', 'unit.edit', 'unit.delete']);
        $units = Unit::all();
        return response()->view('admin.unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      Utilities::checkPermissions(['unit.all', 'unit.add']);

      return response()->view('admin.unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
      Utilities::checkPermissions(['unit.all', 'unit.add']);

      $request->validate([
        'name'   => 'required|max:255',
        'status' => 'required',
      ]);

      Unit::create([
        'name'   => $request->input('name'),
        'status' => $request->input('status'),
      ]);

      $message = 'Unit Created Successfully';
      return back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
      Utilities::checkPermissions(['unit.all', 'unit.edit']);
      return response()->view('admin.unit.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Unit $unit)
    {
      Utilities::checkPermissions(['unit.all', 'unit.edit']);

      $request->validate([
        'name'   => 'required|max:255',
        'status' => 'required',
      ]);

      $unit->update([
        'name'   => $request->input('name'),
        'status' => $request->input('status'),
      ]);

      $message = 'Unit Updated Successfully';
      return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Unit $unit)
    {
      Utilities::checkPermissions(['unit.all', 'unit.delete']);

//      if (Product::where('unit_id', $unit->id)->count() > 0) {
//        return back()->with('error', 'Can\'t Delete, Item Exist In Product.');
//      }

      $unit->delete();
      return back()->with('success', 'Delete Successfully.');
    }
}
