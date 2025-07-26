<?php

namespace App\Http\Controllers\backEnd;

use App\Models\Treasuries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTreasuriesController extends Controller
{

     public function __construct()
    {

        $this->middleware(['permission:عرض اضافة خزن لنفس الموظف'],['only' => 'add_treasuries_to_employees']);
    }
    public function index()
    {
        return view('backEnd.Permissions.admins_treasuries.index');
    }

    public function add_treasuries_to_employees()
    {
        return view('backEnd.treasuries.admin_treasures.add_treasuries_to_employees');
    }

    public function show($id)
    {
        $item = Treasuries::find($id);
        return view('backEnd.treasuries.admin_treasures.show',compact('item'));
    }
}
