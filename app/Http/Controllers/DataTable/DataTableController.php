<?php

namespace App\Http\Controllers\DataTable;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
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
     * @return Illuminate\Http\JsonResponse
     */
    public function index () {
        return response()->json([
            'data' =>  [
                'table' => $this->builder->getModel()->getTable(),
                'displayable' => $this->getDisplayableColumns(),
                'records' => $this->getRecords(),
            ]
        ]);
    }

    protected function getRecords () {
        return $this->builder->get($this->getDisplayableColumns());
    }

    public function getDisplayableColumns() {
        return array_values(array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden()));
    }
    protected function getDatabaseColumnNames ()  {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }
}
