<?php

namespace App\Http\Controllers\DataTable;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

abstract class DataTableController extends Controller
{
    protected $builder;
    protected $allowCreation = true;
    protected $allowDeletion = false;
    abstract public function builder();

    public function __construct(){
        $builder = $this->builder();
        if (!$builder instanceof Builder) {
            throw new \Exception('Entity builder not instance of Builder');
        }

        $this->builder = $builder;
    }

    /**
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index (Request $request) {
        return response()->json([
            'data' =>  [
                'table' => $this->builder->getModel()->getTable(),
                'displayable' => $this->getDisplayableColumns(),
                'updatable' => $this->getUpdatableColumns(),
                'allow' => [
                    'creation' => $this->allowCreation,
                    'deletion' => $this->allowCreation
                ],
                'custom_columns' => $this->getCustomColumnNames(),
                'records' => $this->getRecords($request),
            ]
        ]);
    }

    public function update ($id, Request $request) {
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }

    public function destroy ($ids, Request $request) {
        if(!$this->allowDeletion) {
            return;
        }
        $this->builder->whereIn('id', explode(',', $ids))->delete();
    }

    public function getCustomColumnNames () {
        return [];
    }

    /**
     * @param Request $request
     */

    public function store (Request $request) {
        if(!$this->allowCreation) {
            return;
        }

        $this->builder->create($request->only($this->getUpdatableColumns()));
    }

    protected function getRecords (Request $request) {
        $builder = $this->builder;

        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }

        try {
            return $this->builder->limit($request->limit)->orderBy('id', 'asc')->get($this->getDisplayableColumns());
        } catch (QueryException $e) {
            return [];
        }
    }

    public function getDisplayableColumns() {
        return array_values(array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden()));
    }

    protected function hasSearchQuery(Request $request) {
        return count(array_filter($request->only(['column', 'operator', 'value']))) === 3;
    }

    protected function buildSearch(Builder $builder, Request $request) {
        $queryParts = $this->resolveQueryParts($request->operator, $request->value);

        return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }

    protected function resolveQueryParts ($operator, $value) {
        return  array_get([
            'equals' => [
                'operator' => '=',
                'value' => $value
            ],
            'contains' => [
                'operator' => 'LIKE',
                'value' => "%{$value}%"
            ],
            'starts_with' => [
                'operator' => 'LIKE',
                'value' => "{$value}%"
            ],
            'ends_with' => [
                'operator' => 'LIKE',
                'value' => "%{$value}"
            ],
            'greater_than' => [
                'operator' => '>',
                'value' => $value
            ],
            'less_than' => [
                'operator' => '<',
                'value' => $value
            ],

        ], $operator);
    }

    public function getUpdatableColumns() {
        return $this->getDisplayableColumns();
    }


    protected function getDatabaseColumnNames ()  {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }
}
