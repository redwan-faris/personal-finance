<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Person;
use App\Models\TransactionCategory;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get dashboard statistics
            $stats = [
                'total_users' => User::count(),
                'total_wallets' => Wallet::count(),
                'total_transactions' => Transaction::count(),
                'total_people' => Person::count(),
                'total_categories' => TransactionCategory::count(),
            ];

            // Get recent transactions
            $recent_transactions = Transaction::with(['wallet', 'category', 'person'])
                ->latest()
                ->take(5)
                ->get();

            // Get wallet balances - handle case where user relationship might not exist
            $wallets = Wallet::all();

            // Get transaction summary by category
            $category_summary = Transaction::with('category')
                ->selectRaw('transaction_category_id, SUM(amount) as total_amount, COUNT(*) as transaction_count')
                ->groupBy('transaction_category_id')
                ->get();

        } catch (\Exception $e) {
            // If there are database issues, provide default values
            $stats = [
                'total_users' => 0,
                'total_wallets' => 0,
                'total_transactions' => 0,
                'total_people' => 0,
                'total_categories' => 0,
            ];
            $recent_transactions = collect();
            $wallets = collect();
            $category_summary = collect();
        }

        return view('dashboard.index', compact('stats', 'recent_transactions', 'wallets', 'category_summary'));
    }
}
