<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        $permissions = Permission::all();
        $roles = Role::with('permissions')->get();
        return view('admin.role.createRole', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        Role::create([
            'name' => $request->input('name')
        ])->givePermissionTo($request->input('permissions'));

        return back()->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        if ($role->id == 1) return back()->with('error', 'Can\'t edit this role.');

        $restricted = false;
        $restrictedRoles = collect(['Admin', 'Customer', 'Manager', 'Sales Person', 'Super Admin', 'Supplier']);
        if ($restrictedRoles->contains($role->name)) {
            $restricted = true;
        }
        $permissions = Permission::all();
        $roles = Role::with('permissions')->get();

        return view('admin.role.createRole', compact('role', 'restricted', 'roles', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        if ($request->input('restricted') == 'false') {
            $role->update([
                'name' => $request->input('name')
            ]);
        }
        $role->syncPermissions($request->input('permissions'));

        return back()->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        $restrictedRoles = collect(['Admin', 'Customer', 'Manager', 'Sales Person', 'Super Admin', 'Supplier']);
        if ($restrictedRoles->contains($role->name)) {
            return back()->with('error', 'Cannot delete this role');
        }
        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }

    public function roleAssign()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        $roles = Role::whereKeyNot(1)->get();
        $users = User::with('roles')->whereKeyNot([1, 2])->get();

        return view('admin.role.roleassign', compact('users', 'roles'));
    }

    public function storeAssign(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        User::findOrFail($request->input('user'))
            ->syncRoles($request->input('roles'));

        return redirect()->route('role.assign')->with('success', 'Role assigned to user successfully.');
    }
}
