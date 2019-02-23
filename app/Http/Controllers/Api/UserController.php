<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UsersCollection;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index () {
        return new UsersCollection(User::paginate(5));
    }
}
