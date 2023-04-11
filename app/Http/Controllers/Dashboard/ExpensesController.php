<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\Expense;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ExpensesController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['expense.all', 'expense.add', 'expense.view', 'expense.edit', 'expense.delete']);

        $store = Utilities::getActiveStore();
        $expenses = Expense::when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })->with(['addedBy', 'expenseBy', 'store'])->get();
        return view('admin.expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        Utilities::checkPermissions(['expense.all', 'expense.add']);
        $expenseCategories = ExpenseCategory::active()->get();
        $stores = Store::get();
        return view('admin.expenses.create', compact('expenseCategories', 'stores'));
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions(['expense.all', 'expense.add']);
        $validatedData = $request->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'expense_by' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'integer'],
            'purpose' => ['required', 'string', 'max:1000'],
            'store_id' => ['required', 'integer', Rule::exists('stores', 'id')]
        ]);
        if (auth()->user()->hasRole('Super Admin')) {
            $validatedData['approved'] = TRUE;
        }
        Auth::user()->addedExpenses()->create($validatedData);
        return back()->with('success', 'Expense Successfully Created.');
    }

    public function edit(Expense $expense): View
    {
        Utilities::checkPermissions(['expense.all', 'expense.view', 'expense.edit']);
        $expenseCategories = ExpenseCategory::active()->get();
        $stores = Store::get();
        return view('admin.expenses.edit', compact('expense', 'stores', 'expenseCategories'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        Utilities::checkPermissions(['expense.all', 'expense.edit']);
        $validatedData = $request->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'expense_by' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'integer'],
            'purpose' => ['required', 'string', 'max:1000'],
            'store_id' => ['required', 'integer', Rule::exists('stores', 'id')]
        ]);
        $expense->update($validatedData);
        return back()->with('success', 'Expense Successfully Updated.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        Utilities::checkPermissions(['expense.all', 'expense.delete']);
        $expense->delete();
        return back()->with('success', 'Expense Successfully Deleted.');
    }

    public function approveExpense(Expense $expense): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);
        $expense->approved = TRUE;
        $expense->save();
        return back()->with('success', 'Expense successfully approved.');
    }
}
