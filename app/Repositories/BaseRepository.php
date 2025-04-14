<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    // Inject model class in the constructor
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all records
    public function get()
    {
        return $this->model->get();
    }

     /**
     * Apply an array of conditions to the query.
     *
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereConditions(array $conditions)
    {
        $query = $this->model->newQuery();

        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);  // Support for `whereIn`
            } else {
                $query->where($field, $value);    // Standard `where`
            }
        }

        return $query;
    }

    /**
     * Get all records matching the conditions.
     *
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWhere(array $conditions)
    {
        return $this->whereConditions($conditions)->get();
    }

    // Get all records
    public function all()
    {
        return $this->model->all();
    }

    // Find a record by ID
    public function find($id)
    {
        return $this->model->find($id);
    }

    // Find a record by ID or Fail
    public function findOrFail($id)
    {
        return $this->model->find($id);
    }

    // Create a new record
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Update a record by ID
    public function update($id, array $data)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    // Delete a record by ID
    public function delete($id)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->delete();
            return true;
        }
        return false;
    }
}
