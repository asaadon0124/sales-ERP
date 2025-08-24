<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class rebortsController extends Controller
{
    public function suppliers_reborts()
    {
        return view('backEnd.Reborts.Suppliers.index');
    }


    public function customers_reborts()
    {
        return view('backEnd.Reborts.Customers.index');
    }


    public function servants_reborts()
    {
        return view('backEnd.Reborts.Servants.index');
    }


    public function employees_reborts()
    {
        return view('backEnd.Reborts.Employees.index');
    }


    public function items_reborts()
    {
        return view('backEnd.Reborts.Items.index');
    }


    public function stores_reborts()
    {
        return view('backEnd.Reborts.Stores.index');
    }
}
