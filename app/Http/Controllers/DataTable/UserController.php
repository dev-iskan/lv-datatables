<?php

namespace App\Http\Controllers\DataTable;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends DataTableController
{
    public function builder()
    {
        return User::query();
    }

    public function update ($id, Request $request) {
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:250',
                Rule::unique('users')->ignore($id),
            ],
           'name' => 'required|max:250'
        ]);
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
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

    public function getUpdatableColumns()
    {
        return [
            'name',
            'email'
        ];
    }

    public function getCustomColumnNames () {
        return [
            'name' => 'Full Name',
            'email' => 'Email Address'
        ];
    }
}
