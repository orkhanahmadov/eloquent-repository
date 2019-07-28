<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Contracts;

interface Repository
{
    /**
     * Creates model.
     *
     * @param mixed $properties
     *
     * @return mixed
     */
    public function create($properties);

    /**
     * Returns all models.
     *
     * @return mixed
     */
    public function all();

    /**
     * Returns all models with selected columns.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function get(array $columns = ['*']);

    /**
     * Paginates models.
     *
     * @param int $perPage
     *
     * @return mixed
     */
    public function paginate(int $perPage);

    /**
     * Finds a model with ID.
     *
     * @param int|string $modelId
     *
     * @return mixed
     */
    public function find($modelId);

    /**
     * Finds models with "where" condition.
     *
     * @param string $column
     * @param mixed $value
     *
     * @return mixed
     */
    public function getWhere($column, $value);

    /**
     * Finds models with "whereIn" condition.
     *
     * @param string $column
     * @param array|mixed $values
     *
     * @return mixed
     */
    public function getWhereIn(string $column, $values);

    /**
     * Finds first model with "where" condition.
     *
     * @param string $column
     * @param mixed $value
     *
     * @return mixed
     */
    public function getWhereFirst($column, $value);

    /**
     * Finds first model with "whereIn" condition.
     *
     * @param string $column
     * @param array|mixed $values
     *
     * @return mixed
     */
    public function getWhereInFirst(string $column, $values);

    /**
     * Updates a model given properties.
     *
     * @param int|string $modelId
     * @param mixed $properties
     *
     * @return mixed
     */
    public function update($modelId, $properties);

    /**
     * Finds a model with ID and updates it with given properties.
     *
     * @param int|string $modelId
     * @param array|mixed $properties
     *
     * @return mixed
     */
    public function findAndUpdate($modelId, $properties);

    /**
     * Deletes a model.
     *
     * @param mixed $model
     *
     * @return bool|mixed|null
     */
    public function delete($model);

    /**
     * Finds a model with ID and deletes it.
     *
     * @param int|string $modelId
     *
     * @return bool|mixed|null
     */
    public function findAndDelete($modelId);

    /**
     * Restores soft deleted model.
     *
     * @param mixed $model
     *
     * @return bool
     */
    public function restore($model);

    /**
     * Finds a soft deleted model with given ID and restores it.
     *
     * @param int|string $modelId
     *
     * @return bool
     */
    public function findAndRestore($modelId);

    /**
     * Finds a soft deleted model with given ID.
     *
     * @param int|string $modelId
     *
     * @return mixed
     */
    public function findFromTrashed($modelId);
}
