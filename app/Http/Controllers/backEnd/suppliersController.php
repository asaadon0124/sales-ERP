<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class suppliersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض كل الموردين'],['only' => 'index']);
        $this->middleware(['permission:عرض كل الموردين المحذوفة'],['only' => 'softDelete']);
    }


    public function index()
    {
        return view('backEnd.suppliers.index');
    }

    public function softDelete()
    {
        return view('backEnd.suppliers.softDelete');
    }
}
