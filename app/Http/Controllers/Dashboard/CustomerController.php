<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserBanRequest;
use App\Library\Utilities;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class CustomerController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions([
            'customer.create', 'customer.edit', 'customer.delete', 'customer.show'
        ]);
        $store = Utilities::getActiveStore();
        $customers = User::customersQuery(['*'], $store)->get();
        return view('dashboard.user-management.customer.index', compact('customers'));
    }

    public function create(): View
    {
        Utilities::checkPermissions('customer.create');
        return view('dashboard.user-management.customer.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions('customer.create');
        $store = Utilities::getActiveStore();
        $request->merge([
            'store_id' => $store?->id
        ]);
        $customer = (new UserService())->create(
            $request->all(),
            [
                'store_id' => ['nullable', 'exists:stores,id']
            ]
        );
        $customer->assignRole('Customer');
        return back()->with('success', 'Customer successfully created.');
    }

    public function show(User $customer): View
    {
        Utilities::checkPermissions('customer.show');
        return view('dashboard.user-management.customer.show', compact('customer'));
    }

    /* search customer by mobile number*/
    public function customerShow($mobile)
    {
        $customer = User::where('mobile', $mobile)->first();
        return $this->show($customer);
    }

    public function edit(User $customer): View
    {
        Utilities::checkPermissions('customer.edit');
        return view('dashboard.user-management.customer.edit', compact('customer'));
    }

    public function update(Request $request, User $customer): RedirectResponse
    {
        Utilities::checkPermissions('customer.edit');
        (new UserService($customer))->update($request->all());
        return back()->with('success', 'Customer successfully updated.');
    }

    public function destroy(User $customer): RedirectResponse
    {
        Utilities::checkPermissions('customer.delete');
        (new UserService($customer))->delete();
        return back()->with('success', 'Customer successfully delete.');
    }

    public function customerBan(UserBanRequest $request, User $customer)
    {
        Utilities::checkPermissions('customer.ban');
        (new UserService($customer))->banUser(
            $request->input('reason')
        );
        return back()->with('success', 'User successfully banned.');
    }

    public function customerUnban(Request $request, User $customer)
    {
        Utilities::checkPermissions('customer.ban');
        (new UserService($customer))->unbanUser();
        return back()->with('success', 'User successfully unbanned.');
    }
}
