<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Library\Utilities;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['store.create', 'store.edit', 'store.delete', 'store.show']);
        $store = Utilities::getActiveStore();
        $stores = Store::when($store, fn($query) => $query->where('id', $store->id))
            ->get();
        return view('dashboard.store.index', compact('stores'));
    }

    public function create(): View
    {
        Utilities::checkPermissions('store.create');
        $categories = Category::active()
            ->get([
                'id', 'name'
            ]);
        return view('dashboard.store.create', compact('categories'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Utilities::checkPermissions('store.create');

        if ($request->hasFile('image')) {
            $imagePath = Str::uuid() . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs('images/store_images', $imagePath, 'public');
        }
        $store = Store::create($request->validated() + [
                'image' => $imagePath ?? ''
            ]);
        $store->categories()
            ->attach($request->get('categories'));

        // Banner::insertDefaultBannersForStore($store);

        return back()->with('success', 'store created Successful.');
    }

    public function edit(Store $store): View
    {
        Utilities::checkPermissions('store.edit');
        $store->load('categories');
        $categories = Category::active()->get(['id', 'name']);
        return view('dashboard.store.edit', compact('store', 'categories'));
    }

    public function update(StoreRequest $request, Store $store): RedirectResponse
    {
        Utilities::checkPermissions('store.edit');
        
        $imagePath = $store->image;
        if ($request->hasFile('image')) {
            if (File::exists('storage/' . $imagePath)) File::delete('storage/' . $imagePath);
            $imagePath = Str::uuid() . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs('images/store_images', $imagePath, 'public');
        }
        $store->update($request->validated() + [
                'image' => $imagePath
            ]);
        $store->categories()
            ->sync($request->get('categories'));
        return back()->with('success', 'Update Successful.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        Utilities::checkPermissions('store.delete');
        $store->delete();
        return back()->with('success', 'Delete Successful.');
    }


    // TODO:: Review and replace

    public function assignEmployee()
    {
        $stores = Store::active()->get();
        $users = User::role(['Admin', 'Manager', 'Sales Person'])->get();
        return view('admin.store.assignemployee', compact('stores', 'users'));
    }

    public function storeEmployee(Request $request)
    {
        Utilities::checkPermissions(['store.all']);
        $request->validate([
            'user_id' => 'required',
            'store_id' => 'required',
        ]);
        foreach ($request->user_id as $user) {
            $user = User::findOrFail($user);
            $user->store_id = $request->store_id;
            $user->save();
        }
        return back();
    }

    public function getStoreEmployeeList(Request $request)
    {
        Utilities::checkPermissions(['store.all']);
        $data['employees'] = Store::findOrFail($request->id)->employees()->with('roles')->get(['id', 'name']);
        return view('admin.store.storeemployeelist', $data);
    }

    public function removeStoreEmployeeList($id): RedirectResponse
    {
        Utilities::checkPermissions(['store.all']);
        $employee = User::findOrFail($id);
        $employee->store_id = null;
        $employee->save();
        return back()->with('success', 'Employee removed Successful.');
    }

    public function getEmployees(Store $store): JsonResponse
    {
        $employees = $store->employees()->get(['id', 'name'])->toArray();
        return response()->json($employees);
    }

    public function changeStore(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->can('access.all.store'), 403);
        session()->put('store_id', $request->get('store'));
        return back();
    }
}