<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Person::class);
    }

    public function getAll(int $limit, array $filter = [])
    {
        return $this->queryBuilder($filter)->paginate($limit);
    }

    public function create(array $data)
    {
        return DB::transaction(function() use($data) {
            return parent::create($data);
        });
    }

    public function update(Model $model, array $data)
    {
        return DB::transaction(function() use($model, $data) {
            return parent::update($model, $data);
        });
    }
}
