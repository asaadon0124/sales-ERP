<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class servantsController extends Controller
{
     public function __construct()
    {
        $this->middleware(['permission:عرض كل المناديب'],['only' => 'index']);
        $this->middleware(['permission:عرض المناديب المحذوفين'],['only' => 'softDelete']);
    }


     public function index()
    {
        return view('backEnd.servants.index');
    }

     public function softDelete()
    {
        return view('backEnd.servants.softDelete');
    }
}
