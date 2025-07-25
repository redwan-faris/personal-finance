<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Person;
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

            // Update wallet balance if wallet_id is provided
            if (isset($data['wallet_id'])) {
                $this->updateWalletBalance($data['wallet_id'], $data['amount'], $data['direction']);
            }

            // Update person balance if person_id is provided
            if (isset($data['person_id'])) {
                $this->updatePersonBalance($data['person_id'], $data['amount'], $data['direction']);
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
            $oldPersonId = $model->person_id;

            $transaction = parent::update($model, $data);

            // Revert old transaction effects
            if ($oldWalletId) {
                $this->revertWalletBalance($oldWalletId, $oldAmount, $oldDirection);
            }
            if ($oldPersonId) {
                $this->revertPersonBalance($oldPersonId, $oldAmount, $oldDirection);
            }

            // Apply new transaction effects
            if (isset($data['wallet_id']) || isset($data['amount']) || isset($data['direction'])) {
                $newWalletId = $data['wallet_id'] ?? $model->wallet_id;
                $newAmount = $data['amount'] ?? $model->amount;
                $newDirection = $data['direction'] ?? $model->direction;

                if ($newWalletId) {
                    $this->updateWalletBalance($newWalletId, $newAmount, $newDirection);
                }
            }

            if (isset($data['person_id']) || isset($data['amount']) || isset($data['direction'])) {
                $newPersonId = $data['person_id'] ?? $model->person_id;
                $newAmount = $data['amount'] ?? $model->amount;
                $newDirection = $data['direction'] ?? $model->direction;

                if ($newPersonId) {
                    $this->updatePersonBalance($newPersonId, $newAmount, $newDirection);
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
            // Revert transaction effects
            if ($model->wallet_id) {
                $this->revertWalletBalance($model->wallet_id, $model->amount, $model->direction);
            }
            if ($model->person_id) {
                $this->revertPersonBalance($model->person_id, $model->amount, $model->direction);
            }

            return parent::delete($model);
        });
    }

    /**
     * Update wallet balance
     */
    private function updateWalletBalance(string $walletId, int $amount, string $direction)
    {
        $wallet = Wallet::find($walletId);
        if ($wallet) {
            if ($direction === 'in') {
                $wallet->balance += $amount;
            } else {
                $wallet->balance -= $amount;
            }
            $wallet->save();
        }
    }

    /**
     * Revert wallet balance
     */
    private function revertWalletBalance(string $walletId, int $amount, string $direction)
    {
        $wallet = Wallet::find($walletId);
        if ($wallet) {
            if ($direction === 'in') {
                $wallet->balance -= $amount;
            } else {
                $wallet->balance += $amount;
            }
            $wallet->save();
        }
    }

    /**
     * Update person balance
     */
    private function updatePersonBalance(string $personId, int $amount, string $direction)
    {
        $person = Person::find($personId);
        if ($person) {
            if ($direction === 'in') {
                // Money coming in means they owe you less (or you owe them more)
                $person->balance -= $amount;
            } else {
                // Money going out means they owe you more (or you owe them less)
                $person->balance += $amount;
            }
            $person->save();
        }
    }

    /**
     * Revert person balance
     */
    private function revertPersonBalance(string $personId, int $amount, string $direction)
    {
        $person = Person::find($personId);
        if ($person) {
            if ($direction === 'in') {
                // Revert: they owe you more (or you owe them less)
                $person->balance += $amount;
            } else {
                // Revert: they owe you less (or you owe them more)
                $person->balance -= $amount;
            }
            $person->save();
        }
    }

    /**
     * Get transactions by wallet
     */
    public function getByWallet(string $walletId, int $limit = 10)
    {
        return $this->filter(['wallet_id' => $walletId])->paginate($limit);
    }

    /**
     * Get transactions by person
     */
    public function getByPerson(string $personId, int $limit = 10)
    {
        return $this->filter(['person_id' => $personId])->paginate($limit);
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
