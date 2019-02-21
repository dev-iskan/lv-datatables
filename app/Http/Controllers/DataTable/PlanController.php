<?php

namespace App\Http\Controllers\DataTable;

use App\Plan;

class PlanController extends DataTableController
{
    public function builder()
    {
        return Plan::query();
    }
}
