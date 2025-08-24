<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemsController extends Controller
{

     public function __construct()
    {
        $this->middleware(['permission:عرض الاصناف'],['only' => 'index']);
        $this->middleware(['permission:اضافة صنف جديد'],['only' => 'create']);
        $this->middleware(['permission:تعديل صنف'],['only' => 'edit']);
        $this->middleware(['permission:تفاصيل الصنف'],['only' => 'show']);
        $this->middleware(['permission:عرض الاصناف المحزوفة'],['only' => 'softDelete']);
    }

    public function index()
    {
        return view('backEnd.items.index');
    }


    public function create()
    {
        return view('backEnd.items.create');
    }

    public function edit($id)
    {
        return view('backEnd.items.edit',compact('id'));
    }

    public function show($id)
    {
        return view('backEnd.items.show',compact('id'));
    }


    public function softDelete()
    {
        return view('backEnd.items.softDelete');
    }
}
