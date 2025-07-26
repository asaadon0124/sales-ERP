<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoresController extends Controller
{

     public function __construct()
    {
        $this->middleware(['permission:عرض المخازن'],['only' => 'index']);
        $this->middleware(['permission:تفاصيل المخزن'],['only' => 'show']);
        $this->middleware(['permission:عرض المخازن المحذوفة'],['only' => 'softDelete']);
    }



    public function index()
    {
        return view('backEnd.stores.index');
    }

    public function show($id)
    {
        return view('backEnd.stores.show',compact('id'));
    }

    public function softDelete()
    {
        return view('backEnd.stores.softDelete');
    }
}
