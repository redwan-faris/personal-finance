# Transaction Category Seeder

This document explains the enhanced TransactionCategorySeeder that provides comprehensive income and expense categories for the financial management application.

## Overview

The `TransactionCategorySeeder` creates a comprehensive set of transaction categories divided into two main types:

### Income Categories (8 categories)
- **Salary**: Regular employment salary
- **Freelance**: Freelance work and consulting
- **Investment Returns**: Dividends, interest, and capital gains
- **Business Income**: Business profits and sales
- **Rental Income**: Property and equipment rentals
- **Gifts & Donations**: Monetary gifts and donations received
- **Refunds**: Purchase refunds and reimbursements
- **Other Income**: Miscellaneous income sources

### Expense Categories (20 categories)
- **Food & Dining**: Restaurants, groceries, and dining out
- **Transportation**: Gas, public transport, and vehicle expenses
- **Shopping**: Clothing, electronics, and general shopping
- **Bills & Utilities**: Electricity, water, internet, and phone bills
- **Housing**: Rent, mortgage, and home maintenance
- **Healthcare**: Medical expenses, insurance, and prescriptions
- **Entertainment**: Movies, games, hobbies, and leisure activities
- **Education**: Tuition, books, courses, and training
- **Insurance**: Health, auto, home, and life insurance
- **Taxes**: Income tax, property tax, and other taxes
- **Debt Payments**: Credit card payments, loans, and debt reduction
- **Personal Care**: Haircuts, beauty products, and personal hygiene
- **Travel**: Vacations, business trips, and travel expenses
- **Gifts & Donations**: Charitable donations and gifts given
- **Business Expenses**: Work-related expenses and business costs
- **Subscriptions**: Streaming services, software, and memberships
- **Pet Expenses**: Pet food, veterinary care, and pet supplies
- **Home Improvement**: Renovations, repairs, and home upgrades
- **Legal & Professional**: Legal fees, accounting, and professional services
- **Other Expenses**: Miscellaneous and uncategorized expenses

## Features

Each category includes:
- **Name**: Clear, descriptive category name
- **Description**: Detailed explanation of what the category covers
- **Type**: Either 'income' or 'expense'
- **Color**: Hex color code for UI display (using Tailwind CSS color palette)

## Usage

### Running the Seeder

1. **Individual seeder**:
   ```bash
   php artisan db:seed --class=TransactionCategorySeeder
   ```

2. **All seeders** (if included in DatabaseSeeder):
   ```bash
   php artisan db:seed
   ```

### Database Seeder Integration

The seeder is automatically included in `DatabaseSeeder.php`:

```php
$this->call([
    UserSeeder::class,
    TransactionCategorySeeder::class, // Uncommented
    // PersonSeeder::class,
    // WalletSeeder::class,
    // TransactionSeeder::class,
]);
```

### Resetting and Re-seeding

To clear existing categories and re-seed:

```bash
php artisan migrate:fresh --seed
```

Or to just clear the categories table:

```bash
php artisan tinker
>>> App\Models\TransactionCategory::truncate();
>>> exit
php artisan db:seed --class=TransactionCategorySeeder
```

## Customization

To add or modify categories:

1. Edit the `$incomeCategories` and `$expenseCategories` arrays in `database/seeders/TransactionCategorySeeder.php`
2. Each category should have:
   ```php
   [
       'name' => 'Category Name',
       'description' => 'Category description',
       'type' => 'income|expense',
       'color' => '#HEXCODE',
   ]
   ```

## Color Palette

The seeder uses a consistent color palette based on Tailwind CSS colors:
- **Green tones** (#10B981, #059669): Income and positive categories
- **Blue tones** (#3B82F6, #2563EB): Professional and business categories
- **Red tones** (#EF4444, #DC2626): Important expenses and taxes
- **Purple tones** (#8B5CF6, #7C3AED): Premium and entertainment categories
- **Orange tones** (#F59E0B): Food and dining categories
- **Gray tones** (#6B7280, #374151): Utilities and miscellaneous categories

## Database Schema

The categories are stored in the `transaction_categories` table with the following structure:

```sql
CREATE TABLE transaction_categories (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    type ENUM('income', 'expense') DEFAULT 'expense',
    color VARCHAR(7) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Notes

- The seeder uses `TransactionCategory::create()` which will generate UUIDs automatically
- Categories are created in the order specified in the arrays
- Each category has a unique color for easy identification in the UI
- The seeder is idempotent - running it multiple times will create duplicate categories (use `truncate()` first if needed) 
