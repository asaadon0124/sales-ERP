<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:الشيفتات'],['only' => 'index']);
        $this->middleware(['permission:تفاصيل الشيفت'],['only' => 'show']);
    }

    public function index()
    {
        return view('backEnd.shifts.index');
    }

     public function show($id)
    {
        return view('backEnd.shifts.show',compact('id'));
    }
}
