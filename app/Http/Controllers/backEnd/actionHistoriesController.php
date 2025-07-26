<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class actionHistoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض التفاصيل حركات النظام'],['only' => 'index']);
    }

    public function index()
    {
        return view('backEnd.actionHistory.index');
    }
}
