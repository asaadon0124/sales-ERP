<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemUnitsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:عرض وحدات الاصناف'],['only' => 'index']);
        $this->middleware(['permission:عرض وحدات الاصناف المحذوفة'],['only' => 'softDelete']);
    }

    public function index()
    {
        return view('backEnd.ItemUnits.index');
    }

      public function softDelete()
    {
        return view('backEnd.ItemUnits.softDelete');
    }
}
