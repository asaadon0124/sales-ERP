<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class permissionsController extends Controller
{
     public function index()
    {
        return view('backEnd.permissions2.index');
    }
}
