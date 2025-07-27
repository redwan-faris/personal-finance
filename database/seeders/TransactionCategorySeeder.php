<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Income categories
        TransactionCategory::create([
            'name' => 'Salary',
            'description' => 'Monthly salary income',
            'type' => 'income',
            'color' => '#10B981',
        ]);

        TransactionCategory::create([
            'name' => 'Freelance',
            'description' => 'Freelance work income',
            'type' => 'income',
            'color' => '#3B82F6',
        ]);

        TransactionCategory::create([
            'name' => 'Investment',
            'description' => 'Investment returns',
            'type' => 'income',
            'color' => '#8B5CF6',
        ]);

        // Expense categories
        TransactionCategory::create([
            'name' => 'Food & Dining',
            'description' => 'Restaurants, groceries, etc.',
            'type' => 'expense',
            'color' => '#F59E0B',
        ]);

        TransactionCategory::create([
            'name' => 'Transportation',
            'description' => 'Gas, public transport, etc.',
            'type' => 'expense',
            'color' => '#EF4444',
        ]);

        TransactionCategory::create([
            'name' => 'Shopping',
            'description' => 'Clothing, electronics, etc.',
            'type' => 'expense',
            'color' => '#EC4899',
        ]);

        TransactionCategory::create([
            'name' => 'Bills & Utilities',
            'description' => 'Electricity, water, internet, etc.',
            'type' => 'expense',
            'color' => '#6B7280',
        ]);

        TransactionCategory::create([
            'name' => 'Entertainment',
            'description' => 'Movies, games, hobbies, etc.',
            'type' => 'expense',
            'color' => '#8B5CF6',
        ]);
    }
}
