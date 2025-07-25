<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Wallet::class);
    }

    /**
     * Get all wallets with pagination
     */
    public function getAll(int $limit, array $filter = [])
    {
        return $this->queryBuilder($filter)->paginate($limit);
    }

    /**
     * Create new wallet
     */
    public function create(array $data)
    {
        return DB::transaction(function() use($data) {
            $wallet = parent::create($data);
            return $wallet;
        });
    }

    /**
     * Update wallet
     */
    public function update(Model $model, array $data)
    {
        return DB::transaction(function() use($model, $data) {
            $wallet = parent::update($model, $data);
            return $wallet;
        });
    }

    /**
     * Add balance to wallet
     */
    public function addBalance(Wallet $wallet, int $amount)
    {
        return DB::transaction(function() use($wallet, $amount) {
            $wallet->balance += $amount;
            $wallet->save();
            return $wallet;
        });
    }

    /**
     * Deduct balance from wallet
     */
    public function deductBalance(Wallet $wallet, int $amount)
    {
        return DB::transaction(function() use($wallet, $amount) {
            if ($wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }
            $wallet->balance -= $amount;
            $wallet->save();
            return $wallet;
        });
    }
}
