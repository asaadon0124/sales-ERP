<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreasuriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض الخزن'],['only' => 'index']);
        $this->middleware(['permission:تفاصيل الخزن'],['only' => 'show']);
    }

    public function index()
    {
        return view('backEnd.treasuries.index');
    }
    public function softDelete()
    {
        return view('backEnd.treasuries.softDelete');
    }

    public function show($id)
    {
        return view('backEnd.treasuries.show');
    }



}
