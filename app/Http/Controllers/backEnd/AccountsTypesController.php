<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountsTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض انواع الحسابات'],['only' => 'index']);
        $this->middleware(['permission:عرض انواع الحسابات المحذوفة'],['only' => 'softDelete']);
    }


    public function index()
    {

        return view('backEnd.accountsTypes.index');
    }

    public function softDelete()
    {
        return view('backEnd.accountsTypes.softDelete');
    }
}
