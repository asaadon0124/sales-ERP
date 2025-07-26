<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreasuryTransactionController extends Controller
{
      public function __construct()
    {
        $this->middleware(['permission:حركات تحصيل النقدية'],['only' => 'index']);
        $this->middleware(['permission:حركات صرف النقدية'],['only' => 'index_pay']);
        // $this->middleware(['permission:عرض الاصناف المحزوفة'],['only' => 'softDelete']);
    }
    public function index()
    {
        return view('backEnd.treasury_transations.index');
    }

    public function index_pay()
    {
        return view('backEnd.treasury_transations.index_pay');
    }
}
