<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض كل العملاء'],['only' => 'index']);
        $this->middleware(['permission:عرض العملاء المحذوفين'],['only' => 'softDelete']);
    }


    public function index()
    {
        return view('backEnd.customers.index');
    }

    public function softDelete()
    {
        return view('backEnd.customers.softDelete');
    }
}
