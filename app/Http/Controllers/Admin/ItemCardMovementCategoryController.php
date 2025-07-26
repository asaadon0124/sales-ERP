<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemCardMovementCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض فئات حركات الاصناف'],['only' => 'index']);
        $this->middleware(['permission:عرض فئات الاصناف حركات المحزوفة'],['only' => 'softDelete']);
    }


    public function index()
    {
        return view('backEnd.itemCardCAtegories.index');
    }

    public function softDelete()
    {
        return view('backEnd.itemCardCAtegories.softDelete');
    }
}
