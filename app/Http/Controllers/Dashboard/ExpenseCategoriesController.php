<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\Category;
use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoriesController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['expense.category.all', 'expense.category.add', 'expense.view', 'expense.category.edit', 'expense.category.delete']);
        $expenseCategories = ExpenseCategory::all();
        return view('admin.expense-categories.index', compact('expenseCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions(['expense.category.all', 'expense.category.add']);
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'status' => ['required', 'boolean']
        ]);
        if (auth()->user()->hasRole('Super Admin')) {
            $validatedData['approved'] = true;
        }
        ExpenseCategory::create($validatedData);
        return back()->with('success', 'Expense Category successfully created.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        Utilities::checkPermissions(['expense.category.all', 'expense.category.edit']);
        $expenseCategories = ExpenseCategory::all();
        return view('admin.expense-categories.index', compact('expenseCategories', 'expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        Utilities::checkPermissions(['expense.category.all', 'expense.category.edit']);
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'status' => ['required', 'boolean']
        ]);
        $expenseCategory->update($validatedData);
        return redirect()->route('expense-categories.index')->with('success', 'Expense Category successfully updated.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        Utilities::checkPermissions(['expense.category.all', 'expense.category.delete']);
        $expenseCategory->delete();
        return back()->with('success', 'Expense Category successfully deleted.');
    }

    public function approveCategory(ExpenseCategory $expenseCategory): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);
        $expenseCategory->approved = TRUE;
        $expenseCategory->save();
        return back()->with('success', 'Category successfully approved.');
    }
}
