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

         $this->middleware(['permission:عرض فواتير مرتجع المبيعات'],['only' => 'index_returns']);
        $this->middleware(['permission:تفاصيل فاتورة مرتجع المبيعات'],['only' => 'show_returns']);
    }

    public function index()
    {
        return view('backEnd.salesOrder.index');
    }


    public function show($id)
    {
        return view('backEnd.salesOrder.show',compact('id'));
    }

    public function index_returns()
    {
        return view('backEnd.salesOrder.index_returns');
    }

    public function show_returns($id)
    {
        return view('backEnd.salesOrder.show_returns',compact('id'));
    }
}
