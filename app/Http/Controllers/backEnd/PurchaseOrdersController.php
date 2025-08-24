<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض فواتير المشتريات'],['only' => 'index']);
        $this->middleware(['permission:تفاصيل فاتورة المشتريات'],['only' => 'show']);
        $this->middleware(['permission:عرض فواتير مرتجع المشتريات'],['only' => 'index_returns']);
        $this->middleware(['permission:تفاصيل فاتورة مرتجع المشتريات'],['only' => 'show_returns']);
    }

    public function index()
    {
        return view('backEnd.purchaseOrders.index');
    }

    public function show($id)
    {
        return view('backEnd.purchaseOrders.show',compact('id'));
    }

    public function index_returns()
    {
        return view('backEnd.purchaseOrders.index_returns');
    }

    public function show_returns($id)
    {
        return view('backEnd.purchaseOrders.show_returns',compact('id'));
    }
}
