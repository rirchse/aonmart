<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\CompanySetting;
use App\Models\District;
use App\Models\Division;
use App\Models\EcommerceSetting;
use App\Models\ExpenseCategory;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class CoreDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->saveGeoData();
        $this->createSettings();
        $this->createDefaultRoles();
        $this->createDefaultPermissions();
        $this->createSuperAdmin();
        $this->createDefaultCustomer();
        $this->createDefaultExpenseCategories();
    }

    private function createSettings()
    {
        CompanySetting::create([
            'name' => config('app.name')
        ]);
        EcommerceSetting::create([
            'currency' => 'BDT'
        ]);
    }

    private function createSuperAdmin()
    {
        User::create([
            'name' => 'Super Admin',
            'mobile' => '12312312312',
            'password' => Hash::make('password'),
        ])->assignRole(User::SUPER_ADMIN_ROLE);
    }

    private function createDefaultPermissions()
    {
        Permission::insert([
            ['name' => 'dashboard'],

            ['name' => 'customer.create'],
            ['name' => 'customer.edit'],
            ['name' => 'customer.delete'],
            ['name' => 'customer.show'],

            ['name' => 'wallet.add-money'],

            ['name' => 'supplier.create'],
            ['name' => 'supplier.edit'],
            ['name' => 'supplier.delete'],
            ['name' => 'supplier.show'],

            ['name' => 'admin.create'],
            ['name' => 'admin.edit'],
            ['name' => 'admin.delete'],
            ['name' => 'admin.show'],

            ['name' => 'employee.create'],
            ['name' => 'employee.edit'],
            ['name' => 'employee.delete'],
            ['name' => 'employee.show'],

            ['name' => 'order.show'],
            ['name' => 'order.change-status'],

            ['name' => 'store.create'],
            ['name' => 'store.edit'],
            ['name' => 'store.delete'],
            ['name' => 'store.show'],
            ['name' => 'store.assign-employee'],

            ['name' => 'purchase.return'],

            ['name' => 'settings.slider.create'],
            ['name' => 'settings.slider.edit'],
            ['name' => 'settings.slider.delete'],

            ['name' => 'settings.video.create'],
            ['name' => 'settings.video.edit'],
            ['name' => 'settings.video.delete'],

            ['name' => 'settings.banner.show'],
            ['name' => 'settings.banner.edit'],

            ['name' => 'import.purchases'],

            ['name' => 'access.all.store'],

            ['name' => 'report.all'],
            ['name' => 'report.supplier'],
            ['name' => 'report.customer'],
            ['name' => 'report.sale'],
            ['name' => 'report.expense'],
            ['name' => 'report.purchase'],
            ['name' => 'report.stock'],

            // done
            ['name' => 'user.all'],
            ['name' => 'user.add'],
            ['name' => 'user.edit'],
            ['name' => 'user.delete'],
            ['name' => 'user.view'],

            ['name' => 'product.all'],
            ['name' => 'product.add'],
            ['name' => 'product.edit'],
            ['name' => 'product.delete'],
            ['name' => 'product.view'],

            ['name' => 'coupon.all'],
            ['name' => 'coupon.add'],
            ['name' => 'coupon.edit'],
            ['name' => 'coupon.delete'],
            ['name' => 'coupon.view'],

            ['name' => 'stock.all'],
            ['name' => 'stock.add'],
            ['name' => 'stock.edit'],
            ['name' => 'stock.delete'],
            ['name' => 'stock.view'],

            ['name' => 'category.all'],
            ['name' => 'category.add'],
            ['name' => 'category.edit'],
            ['name' => 'category.delete'],
            ['name' => 'category.view'],

            ['name' => 'feature.all'],
            ['name' => 'feature.add'],
            ['name' => 'feature.edit'],
            ['name' => 'feature.delete'],
            ['name' => 'feature.view'],

            ['name' => 'tag.all'],
            ['name' => 'tag.add'],
            ['name' => 'tag.edit'],
            ['name' => 'tag.delete'],
            ['name' => 'tag.view'],

            ['name' => 'order.all'],
            ['name' => 'order.add'],
            ['name' => 'order.edit'],
            ['name' => 'order.delete'],
            ['name' => 'order.view'],

            ['name' => 'warehouse.all'],
            ['name' => 'warehouse.add'],
            ['name' => 'warehouse.edit'],
            ['name' => 'warehouse.delete'],
            ['name' => 'warehouse.view'],

            ['name' => 'purchase.all'],
            ['name' => 'purchase.add'],
            ['name' => 'purchase.edit'],
            ['name' => 'purchase.delete'],
            ['name' => 'purchase.view'],

            ['name' => 'sale.all'],
            ['name' => 'sale.add'],
            ['name' => 'sale.edit'],
            ['name' => 'sale.delete'],
            ['name' => 'sale.view'],

            ['name' => 'customer_support.all'],
            ['name' => 'settings.all'],

            ['name' => 'expense.all'],
            ['name' => 'expense.add'],
            ['name' => 'expense.edit'],
            ['name' => 'expense.delete'],
            ['name' => 'expense.view'],

            ['name' => 'expense.category.all'],
            ['name' => 'expense.category.add'],
            ['name' => 'expense.category.edit'],
            ['name' => 'expense.category.delete'],

            ['name' => 'mobile_app.all'],
            ['name' => 'mobile_app.product'],
            ['name' => 'mobile_app.user'],
            ['name' => 'mobile_app.video'],

            ['name' => 'promotion.all'],
            ['name' => 'promotion.add'],
            ['name' => 'promotion.edit'],
            ['name' => 'promotion.delete'],
            ['name' => 'promotion.view'],
        ]);
    }

    private function createDefaultRoles()
    {
        $roles = [];

        foreach (User::ROLES as $role) {
            $roles[] = [
                'name' => $role
            ];
        }

        Role::insert($roles);
    }

    private function saveGeoData()
    {
        $jsonString = file_get_contents(asset('geoinfo/bd-divisions.json'));
        $data = json_decode($jsonString, true);
        Division::insert($data['divisions']);

        $jsonString = file_get_contents(asset('geoinfo/bd-districts.json'));
        $data = json_decode($jsonString, true);
        District::insert($data['districts']);

        $jsonString = file_get_contents(asset('geoinfo/bd-upazilas.json'));
        $data = json_decode($jsonString, true);
        Upazila::insert($data['upazilas']);
    }

    private function createDefaultCustomer()
    {
        User::create([
            'name' => 'Walk-In Customer',
            'mobile' => User::WALK_IN_CUSTOMER_PHONE,
            'password' => Hash::make('password'),
        ])->assignRole(User::CUSTOMER_ROLE);
    }

    private function createDefaultExpenseCategories()
    {
        ExpenseCategory::create([
            'name' => 'Electricity Bill',
            'status' => true,
            'approved' => true
        ]);
        ExpenseCategory::create([
            'name' => 'Travel Cost',
            'status' => true,
            'approved' => true
        ]);
        ExpenseCategory::create([
            'name' => 'Others',
            'status' => true,
            'approved' => true
        ]);

    }
}
