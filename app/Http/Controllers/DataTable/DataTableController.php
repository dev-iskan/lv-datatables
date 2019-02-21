<?php

namespace App\Http\Controllers\DataTable;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

abstract class DataTableController extends Controller
{
    protected $builder;

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
                'records' => $this->getRecords($request),
            ]
        ]);
    }

    public function update ($id, Request $request) {
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }

    protected function getRecords (Request $request) {
        return $this->builder->limit($request->limit)->orderBy('id', 'asc')->get($this->getDisplayableColumns());
    }

    public function getDisplayableColumns() {
        return array_values(array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden()));
    }

    public function getUpdatableColumns() {
        return $this->getDisplayableColumns();
    }


    protected function getDatabaseColumnNames ()  {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }
}
