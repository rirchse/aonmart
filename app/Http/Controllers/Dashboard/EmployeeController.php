<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['employee.create', 'employee.edit', 'employee.delete', 'employee.show']);
        $store = Utilities::getActiveStore();
        $employees = User::employeesQuery(['*'], $store?->id)->orderBy('id', 'DESC')->get();
        return view('dashboard.user-management.employee.index', compact('employees'));
    }

    public function create(): View
    {
        Utilities::checkPermissions('employee.create');
        $roles = Role::whereIn('name', ['Manager', 'Sales Person'])->get();
        return view('dashboard.user-management.employee.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions('employee.create');
        $employee = (new UserService())->create($request->all(), [
            'role' => ['required', 'string', Rule::exists('roles', 'name')]
        ]);
        $employee->assignRole($request->get('role'));
        return back()->with('success', 'Employee successfully created.');
    }

    public function show(User $employee): View
    {
        Utilities::checkPermissions('employee.show');
        return view('dashboard.user-management.employee.show', compact('employee'));
    }

    public function edit(User $employee): View
    {
        Utilities::checkPermissions('employee.edit');
        $employee->load('roles');
        $roles = Role::whereIn('name', ['Manager', 'Sales Person'])->get();
        return view('dashboard.user-management.employee.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        Utilities::checkPermissions('employee.edit');
        (new UserService($employee))->update($request->all(), [
            'role' => ['required', 'string', Rule::exists('roles', 'name')]
        ]);
        $employee->syncRoles($request->get('role'));
        return back()->with('success', 'Employee successfully updated.');
    }

    public function destroy(User $employee): RedirectResponse
    {
        Utilities::checkPermissions('employee.delete');
        (new UserService($employee))->delete();
        return back()->with('success', 'Employee successfully delete.');
    }
}
