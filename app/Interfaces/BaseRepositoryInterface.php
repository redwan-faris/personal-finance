<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface {

    /**
     * Spatie Query Builder
     *
     * @param array $filters []
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function queryBuilder(array $filters = []);


    /**
     * filter
     *
     * @param array $args []
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $args = []);

    /**
     * find by $pk
     *
     * @param mixed $field
     * @param string $pk "id"
     * @return Illuminate\Database\Eloquent\Model
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException|abort(404)
     */
    public function get(mixed $pk, string $field = "id");

    /**
     * find by field name
     *
     * @param string $field
     * @param mixed $value
     * @return Illuminate\Database\Eloquent\Model
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException|abort(404)
     */
    public function findBy(string $field, mixed $value);

    /**
     * Get records
     *
     * @param int $limit 10
     * @param array $filter []
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(int $limit = 10, array $filters = []);

    /**
     * Create new records
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Model $model, array $data);

    /**
     * Delete model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool|null
     */
    public function delete(Model $model);
}
