<?php

namespace App\Http\Controllers\DataTable;

use App\User;

class UserController extends DataTableController
{
    public function builder()
    {
        return User::query();
    }

    public function getDisplayableColumns()
    {
        return [
            'id',
            'name',
            'email',
            'created_at'
        ];
    }
}
