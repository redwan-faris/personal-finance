<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $guard_name = 'sanctum';
        $rolesPermissions = [

        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'uuid' => (string) Str::uuid(),
                    'guard_name' => $guard_name,
                ]
            );

            $permissionsIds = Permission::whereIn('name', $permissions)->pluck('uuid')->toArray();

            $role->syncPermissions($permissionsIds);
        }
    }
}
