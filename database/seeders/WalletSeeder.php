<?php

namespace Database\Seeders;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() > 0) {
            // Create wallets for the first user
            Wallet::create([
                'name' => 'Main Bank Account',
                'user_id' => $users->first()->id,
                'balance' => 500000, // $5000.00 in cents
                'description' => 'Primary checking account',
            ]);

            Wallet::create([
                'name' => 'Savings Account',
                'user_id' => $users->first()->id,
                'balance' => 1500000, // $15000.00 in cents
                'description' => 'High-yield savings account',
            ]);

            // Create wallets for other users if they exist
            if ($users->count() > 1) {
                Wallet::create([
                    'name' => 'Business Account',
                    'user_id' => $users[1]->id,
                    'balance' => 250000, // $2500.00 in cents
                    'description' => 'Business checking account',
                ]);
            }

            if ($users->count() > 2) {
                Wallet::create([
                    'name' => 'Investment Account',
                    'user_id' => $users[2]->id,
                    'balance' => 3000000, // $30000.00 in cents
                    'description' => 'Investment portfolio account',
                ]);
            }
        }
    }
}
