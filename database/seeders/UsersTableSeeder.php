<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        //TODO SOLVE assigning permissions to users
        $usersData = [
            [
                'full_name' => 'Super Admin',
                'email' => 'super_admin@aswar.com',
            'password' => Hash::make('#n31Riv39bt&'),
                'roles' => ['super_admin'],
            ],

        ];

        foreach ($usersData as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'id' => (string) Str::uuid(),
                    'full_name' => $userData['full_name'],
                    'password' => $userData['password'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            foreach ($userData['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    $user->assignRole($role);
                    $user->givePermissionTo($role->permissions);
                }
            }
        }
    }
}
