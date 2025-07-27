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
        $incomeCategories = [
            [
                'name' => 'Salary',
                'description' => 'Regular employment salary',
                'type' => 'income',
                'color' => '#10B981',
            ],
            [
                'name' => 'Freelance',
                'description' => 'Freelance work and consulting',
                'type' => 'income',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Investment Returns',
                'description' => 'Dividends, interest, and capital gains',
                'type' => 'income',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Business Income',
                'description' => 'Business profits and sales',
                'type' => 'income',
                'color' => '#059669',
            ],
            [
                'name' => 'Rental Income',
                'description' => 'Property and equipment rentals',
                'type' => 'income',
                'color' => '#0EA5E9',
            ],
            [
                'name' => 'Gifts & Donations',
                'description' => 'Monetary gifts and donations received',
                'type' => 'income',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Refunds',
                'description' => 'Purchase refunds and reimbursements',
                'type' => 'income',
                'color' => '#84CC16',
            ],
            [
                'name' => 'Other Income',
                'description' => 'Miscellaneous income sources',
                'type' => 'income',
                'color' => '#6B7280',
            ],
        ];

        // Expense categories
        $expenseCategories = [
            [
                'name' => 'Food & Dining',
                'description' => 'Restaurants, groceries, and dining out',
                'type' => 'expense',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Transportation',
                'description' => 'Gas, public transport, and vehicle expenses',
                'type' => 'expense',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Shopping',
                'description' => 'Clothing, electronics, and general shopping',
                'type' => 'expense',
                'color' => '#EC4899',
            ],
            [
                'name' => 'Bills & Utilities',
                'description' => 'Electricity, water, internet, and phone bills',
                'type' => 'expense',
                'color' => '#6B7280',
            ],
            [
                'name' => 'Housing',
                'description' => 'Rent, mortgage, and home maintenance',
                'type' => 'expense',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical expenses, insurance, and prescriptions',
                'type' => 'expense',
                'color' => '#DC2626',
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Movies, games, hobbies, and leisure activities',
                'type' => 'expense',
                'color' => '#7C3AED',
            ],
            [
                'name' => 'Education',
                'description' => 'Tuition, books, courses, and training',
                'type' => 'expense',
                'color' => '#2563EB',
            ],
            [
                'name' => 'Insurance',
                'description' => 'Health, auto, home, and life insurance',
                'type' => 'expense',
                'color' => '#059669',
            ],
            [
                'name' => 'Taxes',
                'description' => 'Income tax, property tax, and other taxes',
                'type' => 'expense',
                'color' => '#DC2626',
            ],
            [
                'name' => 'Debt Payments',
                'description' => 'Credit card payments, loans, and debt reduction',
                'type' => 'expense',
                'color' => '#991B1B',
            ],
            [
                'name' => 'Personal Care',
                'description' => 'Haircuts, beauty products, and personal hygiene',
                'type' => 'expense',
                'color' => '#F472B6',
            ],
            [
                'name' => 'Travel',
                'description' => 'Vacations, business trips, and travel expenses',
                'type' => 'expense',
                'color' => '#0891B2',
            ],
            [
                'name' => 'Gifts & Donations',
                'description' => 'Charitable donations and gifts given',
                'type' => 'expense',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Business Expenses',
                'description' => 'Work-related expenses and business costs',
                'type' => 'expense',
                'color' => '#7C2D12',
            ],
            [
                'name' => 'Subscriptions',
                'description' => 'Streaming services, software, and memberships',
                'type' => 'expense',
                'color' => '#1E40AF',
            ],
            [
                'name' => 'Pet Expenses',
                'description' => 'Pet food, veterinary care, and pet supplies',
                'type' => 'expense',
                'color' => '#92400E',
            ],
            [
                'name' => 'Home Improvement',
                'description' => 'Renovations, repairs, and home upgrades',
                'type' => 'expense',
                'color' => '#78350F',
            ],
            [
                'name' => 'Legal & Professional',
                'description' => 'Legal fees, accounting, and professional services',
                'type' => 'expense',
                'color' => '#374151',
            ],
            [
                'name' => 'Other Expenses',
                'description' => 'Miscellaneous and uncategorized expenses',
                'type' => 'expense',
                'color' => '#6B7280',
            ],
        ];

        // Create income categories
        foreach ($incomeCategories as $category) {
            TransactionCategory::create($category);
        }

        // Create expense categories
        foreach ($expenseCategories as $category) {
            TransactionCategory::create($category);
        }
    }
}
