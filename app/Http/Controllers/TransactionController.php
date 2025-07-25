<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
        $this->authorizeApiResource("transactions");
    }

    public function index()
    {
        return $this->transactionRepository->all();
    }

    public function show($id)
    {
        $transaction = $this->transactionRepository->get($id);
        return success($transaction, 'Get Transaction Successfully');
    }

    public function store(Create $request)
    {
        $transaction = $this->transactionRepository->create($request->all());
        return success($transaction, 'Transaction Created Successfully', 201);
    }

    public function update(Transaction $transaction, Update $request)
    {
        $transaction = $this->transactionRepository->update($transaction, $request->all());
        return success($transaction, 'Transaction Updated Successfully');
    }

    public function destroy(Transaction $transaction)
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
