<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['supplier.create', 'supplier.edit', 'supplier.delete', 'supplier.show']);
        $store = Utilities::getActiveStore();
        $suppliers = User::suppliersQuery(['*'], $store?->id)->orderBy('id', 'DESC')->get();
        return view('dashboard.user-management.supplier.index', compact('suppliers'));
    }

    public function create(): View
    {
        Utilities::checkPermissions('supplier.create');
        $companies = Company::get();
        $products = Product::active()->get();
        return view('dashboard.user-management.supplier.create', compact('companies', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions('supplier.create');
        $data = $request->all();
        if ($request->get('company_id', false)) {
            $data['company_id'] = Company::firstOrCreate([
                    'name' => $request->get('company_id')
                ])->id ?? null;
        }
        $supplier = (new UserService())->create($data, [
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'product_id' => ['nullable', 'array'],
            'product_id.*' => ['numeric', Rule::exists('products', 'id')]
        ]);
        $supplier->assignRole('Supplier');
        if (!empty($request->get('product_id', []))) {
            $supplier->products()->attach($request->get('product_id'));
        }
        return back()->with('success', 'Supplier successfully created.');
    }

    public function show(User $supplier): View
    {
        Utilities::checkPermissions('supplier.show');
        return view('dashboard.user-management.supplier.show', compact('supplier'));
    }

    public function edit(User $supplier): View
    {
        Utilities::checkPermissions('supplier.edit');
        $supplier->load(['company', 'products']);
        $companies = Company::get();
        $products = Product::active()->get();
        return view('dashboard.user-management.supplier.edit', compact('supplier', 'companies', 'products'));
    }

    public function update(Request $request, User $supplier): RedirectResponse
    {
        Utilities::checkPermissions('supplier.edit');
        $data = $request->all();
        if ($request->get('company_id', false)) {
            $data['company_id'] = Company::firstOrCreate([
                    'name' => $request->get('company_id')
                ])->id ?? null;
        }
        (new UserService($supplier))->update($data, [
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'product_id' => ['nullable', 'array'],
            'product_id.*' => ['numeric', Rule::exists('products', 'id')]
        ]);
        if (!empty($request->get('product_id', []))) {
            $supplier->products()->sync($request->get('product_id'));
        }
        return back()->with('success', 'Supplier successfully updated.');
    }

    public function destroy(User $supplier): RedirectResponse
    {
        Utilities::checkPermissions('supplier.delete');
        (new UserService($supplier))->delete();
        return back()->with('success', 'Supplier successfully delete.');
    }
}
