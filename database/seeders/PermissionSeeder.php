<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        app()['cache']->forget('spatie.permission.cache');

        $entities = [
            'dashboard', 'employees', 'targets', 'categories', 'products', 'sales', 'purchases', 'reports', 'users', 'roles', 'permissions'
        ];

        $actions = ['menu', 'create', 'read', 'update', 'delete'];

        $permissions = [];
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permissions["{$entity}: {$action}"] = Permission::firstOrCreate(['name' => "{$entity}: {$action}"]);
            }
        }

        $rolesPermissions = [
            'admin' => Permission::whereNotIn('name', [
                'employees: menu', 'employees: create', 'employees: read', 'employees: update', 'employees: delete',
                'users: menu', 'users: create', 'users: read', 'users: update', 'users: delete',
                'roles: menu', 'roles: create', 'roles: read', 'roles: update', 'roles: delete',
                'permissions: menu', 'permissions: create', 'permissions: read', 'permissions: update', 'permissions: delete',
                'reports: menu', 'reports: create', 'reports: read', 'reports: update', 'reports: delete',
            ])->get(),
            'kasir' => Permission::whereNotIn('name', [
                'employees: menu', 'employees: create', 'employees: read', 'employees: update', 'employees: delete',
                'targets: menu', 'targets: create', 'targets: read', 'targets: update', 'targets: delete',
                'categories: menu', 'categories: create', 'categories: read', 'categories: update', 'categories: delete',
                'products: menu', 'products: create', 'products: read', 'products: update', 'products: delete',
                'users: menu', 'users: create', 'users: read', 'users: update', 'users: delete',
                'roles: menu', 'roles: create', 'roles: read', 'roles: update', 'roles: delete',
                'permissions: menu', 'permissions: create', 'permissions: read', 'permissions: update', 'permissions: delete',
                'reports: menu', 'reports: create', 'reports: read', 'reports: update', 'reports: delete',
            ])->get(),
            'owner' => Permission::all()
        ];

        foreach ($rolesPermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }


        $user1 = User::where('email', 'kasir@system.com')->first();
        $user1->assignRole('kasir');
        $user2 = User::where('email', 'owner@system.com')->first();
        $user2->assignRole('owner');
        $user3 = User::where('email', 'admin@system.com')->first();
        $user3->assignRole('admin');
    }
}
