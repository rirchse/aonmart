<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function configure(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $userRole = $user->roles()->first();
            if (empty($userRole)) {
                $userRole = Role::where('name', '!=', User::SUPER_ADMIN_ROLE)
                    ->inRandomOrder()
                    ->first(['name']);
                $user->assignRole($userRole->name);
            }
            if ($userRole->name === User::SUPPLIER_ROLE) {
                Product::factory(5)
                    ->hasAttached($user)
                    ->create();
            }
        });
    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => Hash::make('123123123'),
            'mobile' => $this->faker->e164PhoneNumber()
        ];
    }
}
