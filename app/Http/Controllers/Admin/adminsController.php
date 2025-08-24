<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class adminsController extends Controller
{

      public function __construct()
    {
        $this->middleware(['permission:عرض كل الموظفين'],['only' => 'index']);
        $this->middleware(['permission:تفاصيل الموظف'],['only' => 'show']);
        $this->middleware(['permission:عرض الموظفين المحذوفة'],['only' => 'softDelete']);
    }


    public function index()
    {
        return view('backEnd.Permissions.Emoloyees.index');
    }

   public function show($id)
   {
        return view('backEnd.Permissions.Emoloyees.show',compact('id'));
   }


   public function softDelete()
   {
        return view('backEnd.Permissions.Emoloyees.softDelete');
   }


}
