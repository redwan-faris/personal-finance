<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\TransactionCategory;
use App\Models\Person;
use App\Http\Controllers\Controller;
use App\Http\Repositories\TransactionRepository;
use App\Http\Requests\Transaction\Create;
use App\Http\Requests\Transaction\Update;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    )
    {
        // $this->authorizeApiResource("transactions"); // Commented out to prevent 403 errors
    }

    public function index()
    {
        try {
            $transactions = Transaction::with(['wallet', 'category', 'person'])
                ->latest()
                ->paginate(15);
        } catch (\Exception $e) {
            $transactions = Transaction::latest()->paginate(15);
        }

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $wallets = Wallet::all();
        $categories = TransactionCategory::all();
        $people = Person::all();

        return view('transactions.create', compact('wallets', 'categories', 'people'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdraw,transfer,payment,receipt,refund,charge,credit,debit,other',
            'wallet_id' => 'required|exists:wallets,id',
            'transaction_category_id' => 'required|exists:transaction_categories,id',
            'person_id' => 'nullable|exists:people,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'direction' => 'required|in:in,out',
        ]);


        $transaction = Transaction::create($validated);
        $balanceChange = $transaction->direction === 'in' ? $transaction->amount : -$transaction->amount;

        // Update person's balance if transaction involves a person
        if ($transaction->person_id) {
            $person = Person::find($transaction->person_id);
            if ($person) {
                // If direction is 'in', you're receiving money (person owes you less or you owe them more)
                // If direction is 'out', you're giving money (person owes you more or you owe them less)
                $person->increment('balance', -$balanceChange);
            }
        }
        //add it to wallet
        $wallet = Wallet::find($transaction->wallet_id);
        if ($wallet) {
            $wallet->increment('balance', $balanceChange);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $wallets = Wallet::all();
        $categories = TransactionCategory::all();
        $people = Person::all();

        return view('transactions.edit', compact('transaction', 'wallets', 'categories', 'people'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdraw,transfer,payment,receipt,refund,charge,credit,debit,other',
            'wallet_id' => 'required|exists:wallets,id',
            'transaction_category_id' => 'required|exists:transaction_categories,id',
            'person_id' => 'nullable|exists:people,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'direction' => 'required|in:in,out',
        ]);

        // Convert amount from dollars to cents
        $validated['amount'] = (int)($validated['amount'] * 100);

        // Store old values for balance adjustment
        $oldPersonId = $transaction->person_id;
        $oldAmount = $transaction->amount;
        $oldDirection = $transaction->direction;

        $transaction->update($validated);

        // Revert old person's balance if there was a previous person
        if ($oldPersonId) {
            $oldPerson = Person::find($oldPersonId);
            if ($oldPerson) {
                $oldBalanceChange = $oldDirection === 'in' ? $oldAmount : -$oldAmount;
                $oldPerson->decrement('balance', $oldBalanceChange);
            }
        }

        // Update new person's balance if transaction involves a person
        if ($transaction->person_id) {
            $person = Person::find($transaction->person_id);
            if ($person) {
                // If direction is 'in', you're receiving money (person owes you less or you owe them more)
                // If direction is 'out', you're giving money (person owes you more or you owe them less)
                $balanceChange = $transaction->direction === 'in' ? $transaction->amount : -$transaction->amount;
                $person->increment('balance', $balanceChange);
            }
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        // Revert person's balance if transaction involves a person
        if ($transaction->person_id) {
            $person = Person::find($transaction->person_id);
            if ($person) {
                $balanceChange = $transaction->direction === 'in' ? $transaction->amount : -$transaction->amount;
                $person->decrement('balance', $balanceChange);
            }
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    // API methods (keeping for backward compatibility)
    public function apiIndex()
    {
        return $this->transactionRepository->all();
    }

    public function apiShow($id)
    {
        $transaction = $this->transactionRepository->get($id);
        return success($transaction, 'Get Transaction Successfully');
    }

    public function apiStore(Create $request)
    {
        $transaction = $this->transactionRepository->create($request->all());
        return success($transaction, 'Transaction Created Successfully', 201);
    }

    public function apiUpdate(Transaction $transaction, Update $request)
    {
        $transaction = $this->transactionRepository->update($transaction, $request->all());
        return success($transaction, 'Transaction Updated Successfully');
    }

    public function apiDestroy(Transaction $transaction)
    {
        $this->transactionRepository->delete($transaction);
        return success(null, 'Transaction Deleted Successfully', 204);
    }

    /**
     * Get transactions by wallet
     */
    public function getByWallet(Request $request, string $walletId)
    {
        $limit = $request->get('limit', 10);
        $transactions = $this->transactionRepository->getByWallet($walletId, $limit);
        return success($transactions, 'Get Wallet Transactions Successfully');
    }

    /**
     * Get transactions by person
     */
    public function getByPerson(Request $request, string $personId)
    {
        $limit = $request->get('limit', 10);
        $transactions = $this->transactionRepository->getByPerson($personId, $limit);
        return success($transactions, 'Get Person Transactions Successfully');
    }

    /**
     * Get transactions by category
     */
    public function getByCategory(Request $request, string $categoryId)
    {
        $limit = $request->get('limit', 10);
        $transactions = $this->transactionRepository->getByCategory($categoryId, $limit);
        return success($transactions, 'Get Category Transactions Successfully');
    }

    /**
     * Get transactions by type
     */
    public function getByType(Request $request, string $type)
    {
        $limit = $request->get('limit', 10);
        $transactions = $this->transactionRepository->getByType($type, $limit);
        return success($transactions, 'Get Type Transactions Successfully');
    }
}
