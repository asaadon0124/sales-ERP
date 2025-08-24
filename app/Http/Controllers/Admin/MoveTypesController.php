<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoveTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض انواع حركات النقدية'],['only' => 'index']);
        $this->middleware(['permission:عرض انواع حركات النقدية المحذوفة'],['only' => 'softDelete']);
    }


    public function index()
    {
        return view('backEnd.move_types.index');
    }

    public function softDelete()
    {
        return view('backEnd.move_types.softDelete');
    }
}
