<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Query\Builder;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    /**
     * Base Repository
     *
     * @param \Illuminate\Database\Eloquent\Model|string $model
     * @return void
     */
    function __construct(Model|string $model)
    {
        $this->model = is_string($model)
                ? app($model)
                : $model;
    }

    /**
     * Spatie allowed fields to filters
     *
     * @return array
     */
    protected function getAllowedFilters()
    {
        $filters = $this->model->allowedFilters ?? [];
        return array_merge($filters, [
            AllowedFilter::exact("id"),
            AllowedFilter::trashed()
        ]);
    }

    /**
     * Spatie allowed relation includes
     *
     * @return array
     */
    protected function getAllowedIncludes()
    {
        return $this->model->allowedIncludes ?? [];
    }

    /**
     * Spatie allowed fields to sort
     *
     * @return array
     */
    protected function getAllowedSorts()
    {
        $sorts = $this->model->allowedSorts ?? [];

        return array_merge($sorts, [
            "id", "created_at", "updated_at"
        ]);
    }

    /**
     * Spatie Query Builder
     *
     * @param array $filters []
     * @return QueryBuilder
     */
    public function queryBuilder(array $filters = [])
    {
        return QueryBuilder::for($this->filter($filters))
                    ->allowedFilters($this->getAllowedFilters())
                    ->allowedIncludes($this->getAllowedIncludes())
                    ->allowedSorts($this->getAllowedSorts());
    }

    /**
     * filter
     *
     * @param array $args []
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $args = [])
    {
        $filter = $this->model::query();
        $filter->when(isset($args['id']), fn($q)=> $q->where("id", $args['id']));

        return $filter;
    }

    /**
     * Get records
     *
     * @param int $limit 10
     * @param array $filter []
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($limit = 10, array $filters = [])
    {
        return $this->queryBuilder($filters)->paginate($limit);
    }

    /**
     * find record by $field name
     *
     * @param mixed $pk
     * @param string $field "id"
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException|abort(404)
     */
    public function get(mixed $pk, string $field = "id")
    {
        $record = $this->queryBuilder()->where($field, $pk)->first();
        if (!is_null($record)) return $record;
        throw new NotFoundHttpException($this->model::class." record not found");
    }

    /**
     * find by field name
     *
     * @param string $field
     * @param mixed $value
     * @return Model|object|QueryBuilder
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException|abort(404)
     */
    public function findBy($column, $value, $conditions = [])
    {
        $query =  QueryBuilder::for($this->model)
                    ->allowedIncludes($this->getAllowedIncludes())
                    ->where($column, $value);

        foreach ($conditions as $field => $condition) {
            if (is_array($condition)) {
                $query->where(function (Builder $query) use ($condition, $column) {
                    foreach ($condition as $operator => $value) {
                        $query->where($column, $operator, $value);
                    }
                });
            } else {
                $query->where($field, $condition);
            }
        }
        return $query->first();
    }

    /**
     * Create new records
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $model = $this->model->create($data);
        $model->refresh();
        return $model;
    }

    /**
     * Update model
     *
     * @param \Illuminate\Database\Eloquent\Model|mixed $model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Model $model, array $data)
    {
        $model->update($data);
        $model->refresh();
        return $model;
    }

    /**
     * Delete model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool|null
     */
    public function delete(Model $model)
    {
    return $model->delete();
    }


}
