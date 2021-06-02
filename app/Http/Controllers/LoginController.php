<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function setup(Request $request){
        return view('login.index');
    }
}
