<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemCardMovementTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:انواع حركات الاصناف'],['only' => 'index']);

    }
    public function index()
    {
        return view('backEnd.itemCardTypes.index');
    }
}
