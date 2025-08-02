<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Person;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;

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

            // Get wallet balances
            $wallets = Wallet::all();

            // Calculate overall financial statistics
            $financial_stats = $this->calculateFinancialStats();

            // Get transaction statistics for graphs
            $transaction_stats = $this->getTransactionStats();

            // Get category summary
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
            $financial_stats = [
                'total_wallet_balance' => 0,
                'total_owed_to_you' => 0,
                'total_you_owe' => 0,
                'net_worth' => 0,
            ];
            $transaction_stats = [
                'monthly_data' => [],
                'category_data' => [],
                'type_data' => [],
            ];
        }

        return view('dashboard.index', compact(
            'stats', 
            'recent_transactions', 
            'wallets', 
            'category_summary',
            'financial_stats',
            'transaction_stats'
        ));
    }

    private function calculateFinancialStats()
    {
        // Calculate total wallet balance
        $total_wallet_balance = Wallet::sum('balance');

        // Calculate total money owed to you (positive balances)
        $total_owed_to_you = Person::where('balance', '>', 0)->sum('balance');

        // Calculate total money you owe (negative balances)
        $total_you_owe = abs(Person::where('balance', '<', 0)->sum('balance'));

        // Calculate net worth (wallet balance + money owed to you - money you owe)
        $net_worth = $total_wallet_balance + $total_owed_to_you - $total_you_owe;

        return [
            'total_wallet_balance' => $total_wallet_balance,
            'total_owed_to_you' => $total_owed_to_you,
            'total_you_owe' => $total_you_owe,
            'net_worth' => $net_worth,
        ];
    }

    private function getTransactionStats()
    {
        // Get monthly transaction data for the last 12 months
        $monthly_data = Transaction::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                SUM(CASE WHEN direction = "in" THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN direction = "out" THEN amount ELSE 0 END) as expense,
                COUNT(*) as total_transactions
            ')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)),
                    'income' => $item->income,
                    'expense' => $item->expense,
                    'total_transactions' => $item->total_transactions,
                ];
            });

        // Get transaction data by category
        $category_data = Transaction::with('category')
            ->selectRaw('transaction_category_id, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('transaction_category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Unknown',
                    'amount' => $item->total_amount,
                    'count' => $item->count,
                ];
            });

        // Get transaction data by type
        $type_data = Transaction::selectRaw('type, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => ucfirst($item->type->value),
                    'amount' => $item->total_amount,
                    'count' => $item->count,
                ];
            });

        return [
            'monthly_data' => $monthly_data,
            'category_data' => $category_data,
            'type_data' => $type_data,
        ];
    }
}
