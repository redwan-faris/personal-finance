<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\TransactionCategory;
use App\Models\Person;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = Wallet::all();
        $categories = TransactionCategory::all();
        $people = Person::all();

        if ($wallets->count() > 0 && $categories->count() > 0) {
            // Get some specific categories
            $salaryCategory = $categories->where('name', 'Salary')->first();
            $foodCategory = $categories->where('name', 'Food & Dining')->first();
            $transportCategory = $categories->where('name', 'Transportation')->first();
            $shoppingCategory = $categories->where('name', 'Shopping')->first();

            // Create income transactions
            if ($salaryCategory) {
                Transaction::create([
                    'wallet_id' => $wallets->first()->id,
                    'transaction_category_id' => $salaryCategory->id,
                    'person_id' => $people->count() > 0 ? $people->first()->id : null,
                    'amount' => 750000, // $7500.00 in cents
                    'status' => 'completed',
                    'type' => 'credit',
                    'description' => 'Monthly salary payment',
                    'direction' => 'in',
                    'transaction_date' => now()->subDays(5),
                    'notes' => 'Regular monthly salary',
                ]);
            }

            // Create expense transactions
            if ($foodCategory) {
                Transaction::create([
                    'wallet_id' => $wallets->first()->id,
                    'transaction_category_id' => $foodCategory->id,
                    'person_id' => $people->count() > 1 ? $people[1]->id : null,
                    'amount' => 8500, // $85.00 in cents
                    'status' => 'completed',
                    'type' => 'debit',
                    'description' => 'Grocery shopping',
                    'direction' => 'out',
                    'transaction_date' => now()->subDays(2),
                    'notes' => 'Weekly groceries',
                ]);
            }

            if ($transportCategory) {
                Transaction::create([
                    'wallet_id' => $wallets->first()->id,
                    'transaction_category_id' => $transportCategory->id,
                    'person_id' => null,
                    'amount' => 4500, // $45.00 in cents
                    'status' => 'completed',
                    'type' => 'debit',
                    'description' => 'Gas station',
                    'direction' => 'out',
                    'transaction_date' => now()->subDays(1),
                    'notes' => 'Fuel for car',
                ]);
            }

            if ($shoppingCategory) {
                Transaction::create([
                    'wallet_id' => $wallets->first()->id,
                    'transaction_category_id' => $shoppingCategory->id,
                    'person_id' => $people->count() > 2 ? $people[2]->id : null,
                    'amount' => 12500, // $125.00 in cents
                    'status' => 'completed',
                    'type' => 'debit',
                    'description' => 'Online shopping',
                    'direction' => 'out',
                    'transaction_date' => now(),
                    'notes' => 'New clothes',
                ]);
            }

            // Create more transactions for other wallets
            if ($wallets->count() > 1) {
                $freelanceCategory = $categories->where('name', 'Freelance')->first();
                if ($freelanceCategory) {
                    Transaction::create([
                        'wallet_id' => $wallets[1]->id,
                        'transaction_category_id' => $freelanceCategory->id,
                        'person_id' => $people->count() > 3 ? $people[3]->id : null,
                        'amount' => 250000, // $2500.00 in cents
                        'status' => 'completed',
                        'type' => 'credit',
                        'description' => 'Freelance project payment',
                        'direction' => 'in',
                        'transaction_date' => now()->subDays(3),
                        'notes' => 'Web development project',
                    ]);
                }
            }
        }
    }
}
