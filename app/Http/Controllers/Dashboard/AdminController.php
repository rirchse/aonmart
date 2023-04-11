<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    public function index(): View
    {
        $user = Auth()->user();

        Utilities::checkPermissions(['admin.create', 'admin.edit', 'admin.delete', 'admin.show']);
        $store = Utilities::getActiveStore();
        $admins = User::adminsQuery(['*'], $store?->id)->orderBy('id', 'DESC')->get();
        
        return view('dashboard.user-management.admin.index', compact('admins'));
    }

    public function create(): View
    {
        Utilities::checkPermissions('admin.create');
        return view('dashboard.user-management.admin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions('admin.create');
        $admin = (new UserService())->create($request->all());
        $admin->assignRole('Admin');
        return back()->with('success', 'Admin successfully created.');
    }

    public function show(User $admin): View
    {
        Utilities::checkPermissions('admin.show');
        return view('dashboard.user-management.admin.show', compact('admin'));
    }

    public function edit(User $admin): View
    {
        Utilities::checkPermissions('admin.edit');
        return view('dashboard.user-management.admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        Utilities::checkPermissions('admin.edit');
        (new UserService($admin))->update($request->all());
        return back()->with('success', 'Admin successfully updated.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        Utilities::checkPermissions('admin.delete');
        (new UserService($admin))->delete();
        return back()->with('success', 'Admin successfully delete.');
    }
}
