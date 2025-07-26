<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض فئات الاصناف'],['only' => 'index']);
        $this->middleware(['permission:عرض فئات الاصناف المحزوفة'],['only' => 'softDelete']);
    }

    public function index()
    {
        return view('backEnd.itemCategories.index');
    }


    public function softDelete()
    {
        return view('backEnd.itemCategories.softDelete');
    }
}
