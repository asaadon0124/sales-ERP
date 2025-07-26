<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class suppliersCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:عرض اقسام الموردين'],['only' => 'index']);
        $this->middleware(['permission:عرض اقسام الموردين المحذوفة'],['only' => 'softDelete']);
    }
    public function index()
    {
        return view('backEnd.suppliersCategory.index');
    }

    public function softDelete()
    {
        return view('backEnd.suppliersCategory.softDelete');
    }
}
