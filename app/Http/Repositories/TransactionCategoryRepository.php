<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionCategoryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(TransactionCategory::class);
    }

    /**
     * Get all transaction categories with pagination
     */
    public function getAll(int $limit, array $filter = [])
    {
        return $this->queryBuilder($filter)->paginate($limit);
    }

    /**
     * Create new transaction category
     */
    public function create(array $data)
    {
        return DB::transaction(function() use($data) {
            $transactionCategory = parent::create($data);
            return $transactionCategory;
        });
    }

    /**
     * Update transaction category
     */
    public function update(Model $model, array $data)
    {
        return DB::transaction(function() use($model, $data) {
            $transactionCategory = parent::update($model, $data);
            return $transactionCategory;
        });
    }

    /**
     * Get transaction category by name
     */
    public function findByName(string $name)
    {
        return $this->findBy('name', $name);
    }
}
