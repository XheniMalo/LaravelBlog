<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-post',
            'manage-post',

            'create-likes',
            'create-comments',
            'update-comments',
            'delete-comments',
            
            'view-profile',
            'update-profile',
            'update-password',

            'view-admin-profile',
            'update-admin-profile',
            'update-admin-password',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'view-admin-profile',
                'update-admin-profile',
                'update-admin-password'
            ]);
        }

        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->givePermissionTo([
                'view-post',
                'create-likes',
                'create-comments',
                'update-comments',
                'delete-comments',
                'view-profile',
                'update-profile',
                'update-password',
                'view-likes'
            ]);
        }


        $userRole = Role::where('name', 'writer')->first();
        if ($userRole) {
            $userRole->givePermissionTo([
                'view-post',
                'create-likes',
                'create-comments',
                'update-comments',
                'delete-comments',
                'view-profile',
                'update-profile',
                'update-password',
                'view-likes',
                'manage-post'
            ]);
        }

    }
}
