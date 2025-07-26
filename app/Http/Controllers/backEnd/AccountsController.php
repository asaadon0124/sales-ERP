<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض كل الحسابات'],['only' => 'index']);
        $this->middleware(['permission:عرض كل الحسابات المحذوفة'],['only' => 'softDelete']);
    }


    public function index()
    {
        return view('backEnd.accounts.index');
    }


    public function softDelete()
    {
        return view('backEnd.accounts.softDelete');
    }
}
