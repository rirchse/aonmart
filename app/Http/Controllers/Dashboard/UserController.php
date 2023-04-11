<?php

namespace App\Http\Controllers\Dashboard;

use App\Library\Utilities;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): Response
    {
        Utilities::checkPermissions(['user.all', 'user.view', 'user.edit', 'user.delete', 'supplier.all', 'supplier.edit', 'supplier.view', 'supplier.delete']);
        $store = Utilities::getActiveStore();

        if (request()->is('dashboard/customer*')) {
            $customers = User::role('Customer')->when($store, function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            })->get();
            return response()->view('admin.customer.index', compact('customers'));
        }

        if (request()->is('dashboard/supplier*')) {
            $suppliers = User::role('Supplier')->get();
            return response()->view('admin.supplier.index', compact('suppliers'));
        }

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Supplier')->where('name', '!=', 'Customer');
        })->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        })->get();

        return response()->view('admin.userManagement.index', compact('users'));
    }

    public function create(): Response
    {
        Utilities::checkPermissions(['user.all', 'user.add', 'supplier.all', 'supplier.add']);

        if (request()->is('dashboard/supplier*')) {
            $companies = Company::whereStatus(True)->get();
            $products = Product::where('status', 1)->get();
            return response()->view('admin.supplier.create', compact('products', 'companies'));
        }
        $stores = Store::get();
        $roles = Role::get()->except([1, 4]);
        return response()->view('admin.userManagement.create', compact('roles', 'stores'));
    }

    public function store(Request $request): RedirectResponse
    {
        Utilities::checkPermissions(['user.all', 'user.add', 'supplier.all', 'supplier.add']);
        if ($request->input('role_id') == 1) {
            return back()->with('error', 'Unaccepted Role Given.');
        }
        $request->validate([
            'name'         => 'required|max:255',
            'role_id'      => 'nullable|exists:roles,id',
            'password'     => 'required|min:8|confirmed',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            'email'        => 'nullable|email',
            'mobile'       => 'required|unique:users,mobile|numeric|digits_between:11,13',
            'product_id'   => 'nullable|array',
            'product_id.*' => 'nullable|string|exists:products,id',
            'about'        => 'nullable|string',
            'status'       => 'required|string',
            'store_id'     => 'nullable|exists:stores,id',
            'company'      => 'nullable|string'
        ]);

        $fileUrl = null;
        $fileUrlCoverImage = null;

        if ($request->hasFile('image')) {
            $filename = Rand() . '.' . $request->image->getClientOriginalExtension();
            $fileUrl = $request->image->storeAs('images/users', $filename, 'public');
        }

        if ($request->hasFile('cover_image')) {
            $fileUrlCoverImage = Rand() . '.' . $request->cover_image->getClientOriginalExtension();
            $fileUrlCoverImage = $request->cover_image->storeAs('images/users', $fileUrlCoverImage, 'public');
        }

        $company_id = '';
        if ($request->has('company')) {
            $company_id = Company::firstOrCreate([
                'name' => $request->get('company')
            ])->id;
        }

        $user = User::create([
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'password'    => Hash::make($request->input('password')),
            'image'       => $fileUrl,
            'cover_image' => $fileUrlCoverImage,
            'mobile'      => $request->input('mobile'),
            'about'       => $request->input('about'),
            'status'      => $request->input('status'),
            'store_id'    => $request->input('store_id') ?? null,
            'company_id'  => $company_id
        ]);

        if (request()->is('dashboard/supplier*')) {
            $user->syncRoles(4); // role id 4 is a supplier
        } else {
            $user->syncRoles($request->input('role_id'));
        }

        if (request()->is('dashboard/supplier*') && $request->input('product_id')) {
            foreach ($request->input('product_id') as $product) {
                $user->products()->attach($product);
            }
        }

        $message = 'User Created Successfully';
        return back()->with('success', $message);
    }

    public function show($id)
    {
        //
    }

    public function edit(User $user): Response
    {
        Utilities::checkPermissions(['user.all', 'user.edit', 'supplier.all', 'supplier.edit']);

        if (request()->is('dashboard/supplier*')) {
            $products = Product::where('status', 1)->get();
            $oldProducts = $user->Products->pluck('id');
            return response()->view('admin.supplier.edit', compact('products', 'user', 'oldProducts'));
        }

        $stores = Store::all();
        $roles = Role::get()->except([1, 4]); // 1 is
        return response()->view('admin.userManagement.edit', compact('user', 'roles', 'stores'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        Utilities::checkPermissions(['user.all', 'user.edit', 'supplier.all', 'supplier.edit']);

        if ($request->input('role_id') == 1) {
            return back()->with('error', 'Unaccepted Role Given.');
        }

        $request->validate([
            'name'         => 'required|max:255',
            'role_id'      => 'nullable|exists:roles,id',
            'password'     => 'nullable|min:8|confirmed',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            'email'        => 'nullable|email',
            'mobile'       => 'required|numeric|unique:users,mobile,' . $user->id,
            'product_id'   => 'nullable|array',
            'product_id.*' => 'nullable|string|exists:products,id',
            'about'        => 'nullable',
            'status'       => 'required',
            'store_id'     => 'nullable|exists:stores,id',
        ]);

        $fileUrl = $user->image;
        $fileUrlCoverImage = $user->cover_image;

        if ($request->hasFile('image')) {
            if (File::exists('storage/' . $user->image)) {
                File::delete('storage/' . $user->image);
            }
            $fileUrl = Rand() . '.' . $request->image->getClientOriginalExtension();
            $fileUrl = $request->image->storeAs('images/users', $fileUrl, 'public');
        }

        if ($request->hasFile('cover_image')) {
            if (File::exists('storage/' . $user->cover_image)) {
                File::delete('storage/' . $user->cover_image);
            }
            $fileUrlCoverImage = Rand() . '.' . $request->cover_image->getClientOriginalExtension();
            $fileUrlCoverImage = $request->cover_image->storeAs('images/users', $fileUrlCoverImage, 'public');
        }

        $user->update([
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'password'    => $request->input('password') ? Hash::make($request->input('password')) : $user->password,
            'image'       => $fileUrl,
            'cover_image' => $fileUrlCoverImage,
            'mobile'      => $request->input('mobile'),
            'about'       => $request->input('about'),
            'status'      => $request->input('status'),
            'store_id'    => $request->input('store_id') ?? $user->store_id,
        ]);

        if ($user->id != 1) {
            if (!request()->is('dashboard/supplier*')) {
                $user->syncRoles($request->input('role_id'));
            }
            if (request()->is('dashboard/supplier*')) {
                $user->products()->sync($request->input('product_id'));
            }
        }

        $message = 'User Updated Successfully';
        return back()->with('success', $message);
    }

    public function destroy(User $user): RedirectResponse
    {
        Utilities::checkPermissions(['user.all', 'user.delete', 'supplier.all', 'supplier.delete']);

        if ($user->id == 1) {
            return back()->with('error', 'Can\t delete this user');
        }

        if (File::exists('storage/' . $user->image)) {
            File::delete('storage/' . $user->image);
        }
        if (File::exists('storage/' . $user->cover_image)) {
            File::delete('storage/' . $user->cover_image);
        }

        if (Order::where('user_id', $user->id)->count()) {
            return back()->with('error', 'Can\'t delete User, Have user related data');
        }

        $user->delete();
        return back()->with('success', 'Deleted Successfully.');
    }

    public function addUserAjax(Request $request): JsonResponse
    {
        $request->validate([
            'name'   => 'required|max:255',
            'email'  => 'nullable|email',
            'mobile' => 'required|numeric|digits_between:11,13|unique:users,mobile',
            'about'  => 'nullable|string',
        ]);

        $customer = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'mobile'   => $request->input('mobile'),
            'password' => Hash::make('password'),
            'about'    => $request->input('about'),
            'store_id' => session('store_id'),
        ]);
        $role_id = Role::where('name', 'Customer')->first()->id;
        $customer->syncRoles($role_id);
        return response()->json(['user' => $customer, 'message' => 'User Create Successfully.']);
    }
}
