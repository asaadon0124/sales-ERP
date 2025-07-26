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
    }
    public function index()
    {
        return view('backEnd.purchaseOrders.index');
    }

    public function show($id)
    {
        return view('backEnd.purchaseOrders.show',compact('id'));
    }
}
