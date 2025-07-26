<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class rolesController extends Controller
{
      public function index()
    {
        return view('backEnd.roles.index');
    }
}
