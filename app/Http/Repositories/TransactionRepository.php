<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    /**
     * Get all transactions with pagination
     */
    public function getAll(int $limit, array $filter = [])
    {
        return $this->queryBuilder($filter)->paginate($limit);
    }

    /**
     * Create new transaction
     */
    public function create(array $data)
    {
        return DB::transaction(function() use($data) {
            $transaction = parent::create($data);

            // Update wallet balance based on transaction direction
            $wallet = Wallet::find($data['wallet_id']);
            if ($wallet) {
                if ($data['direction'] === 'in') {
                    $wallet->balance += $data['amount'];
                } else {
                    $wallet->balance -= $data['amount'];
                }
                $wallet->save();
            }

            return $transaction;
        });
    }

    /**
     * Update transaction
     */
    public function update(Model $model, array $data)
    {
        return DB::transaction(function() use($model, $data) {
            $oldAmount = $model->amount;
            $oldDirection = $model->direction;
            $oldWalletId = $model->wallet_id;

            $transaction = parent::update($model, $data);

            // Revert old transaction effect on wallet
            $oldWallet = Wallet::find($oldWalletId);
            if ($oldWallet) {
                if ($oldDirection === 'in') {
                    $oldWallet->balance -= $oldAmount;
                } else {
                    $oldWallet->balance += $oldAmount;
                }
                $oldWallet->save();
            }

            // Apply new transaction effect on wallet
            if (isset($data['wallet_id']) || isset($data['amount']) || isset($data['direction'])) {
                $newWalletId = $data['wallet_id'] ?? $model->wallet_id;
                $newAmount = $data['amount'] ?? $model->amount;
                $newDirection = $data['direction'] ?? $model->direction;

                $newWallet = Wallet::find($newWalletId);
                if ($newWallet) {
                    if ($newDirection === 'in') {
                        $newWallet->balance += $newAmount;
                    } else {
                        $newWallet->balance -= $newAmount;
                    }
                    $newWallet->save();
                }
            }

            return $transaction;
        });
    }

    /**
     * Delete transaction
     */
    public function delete(Model $model)
    {
        return DB::transaction(function() use($model) {
            // Revert transaction effect on wallet
            $wallet = Wallet::find($model->wallet_id);
            if ($wallet) {
                if ($model->direction === 'in') {
                    $wallet->balance -= $model->amount;
                } else {
                    $wallet->balance += $model->amount;
                }
                $wallet->save();
            }

            return parent::delete($model);
        });
    }

    /**
     * Get transactions by wallet
     */
    public function getByWallet(string $walletId, int $limit = 10)
    {
        return $this->filter(['wallet_id' => $walletId])->paginate($limit);
    }

    /**
     * Get transactions by category
     */
    public function getByCategory(string $categoryId, int $limit = 10)
    {
        return $this->filter(['transaction_category_id' => $categoryId])->paginate($limit);
    }

    /**
     * Get transactions by type
     */
    public function getByType(string $type, int $limit = 10)
    {
        return $this->filter(['type' => $type])->paginate($limit);
    }
}
