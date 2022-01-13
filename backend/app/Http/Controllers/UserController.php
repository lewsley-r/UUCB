<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAllUsers(Request $req){
        $users = User::where('id','!=', Auth::id())->get();
        return $users;
    }    
}
