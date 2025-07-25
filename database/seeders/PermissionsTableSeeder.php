<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $guard_name = 'sanctum';
        $permissions = [

        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                [
                    'uuid' => (string) Str::uuid(),
                    'guard_name' => $guard_name,
                ]
            );
        }
    }
}
