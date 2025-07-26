<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض فواتير المبيعات'],['only' => 'index']);
        $this->middleware(['permission:تفاصيل فاتورة المبيعات'],['only' => 'show']);
    }

    public function index()
    {
        return view('backEnd.salesOrder.index');
    }


    public function show($id)
    {
        return view('backEnd.salesOrder.show',compact('id'));
    }
}
