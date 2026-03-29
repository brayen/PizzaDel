<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage staff',
            'manage menu',
            'manage orders',
            'view reports',
            'manage settings',
            'manage clients',
            'manage promotions',
            'view analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'staff']);
        }

        // Create roles and assign permissions
        $roles = [
            'owner' => Permission::all(),
            'admin' => ['manage staff', 'manage menu', 'manage orders', 'view reports', 'manage settings', 'manage clients', 'manage promotions', 'view analytics'],
            'manager' => ['manage orders', 'view reports', 'manage clients'],
            'cook' => ['manage menu'],
            'delivery' => ['manage orders'],
            'cashier' => ['manage orders'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'staff']);
            $role->syncPermissions($rolePermissions);
        }

        // Create default staff users
        $staffUsers = [
            [
                'name' => 'Pizza Owner',
                'email' => 'owner@pizzadel.com',
                'password' => 'password',
                'phone' => '+390123456789',
                'position' => 'admin',
                'role' => 'owner',
                'hire_date' => now()->subYears(2),
                'salary' => 800000,
            ],
            [
                'name'      => 'Pizza Admin',
                'email'     => 'admin@pizzadel.com',
                'password'  => 'password',
                'phone'     => '+390987654321',
                'position'  => 'admin',
                'role'      => 'admin',
                'hire_date' => now()->subYear(),
                'salary'    => 500000,
            ],
        ];

        foreach ($staffUsers as $staffData) {
            $staff = Staff::firstOrCreate(
                ['email' => $staffData['email']],
                [
                    'name'      => $staffData['name'],
                    'password'  => Hash::make($staffData['password']),
                    'phone'     => $staffData['phone'],
                    'position'  => $staffData['position'],
                    'hire_date' => $staffData['hire_date'],
                    'salary'    => $staffData['salary'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $staff->assignRole($staffData['role']);
        }

        $this->command->info('✅ Roles, permissions and default staff users created successfully!');
        $this->command->info('✅ Login credentials:');
        $this->command->info('  Owner: owner@pizzadel.com / password');
        $this->command->info('  Admin: admin@pizzadel.com / password');
    }
}
