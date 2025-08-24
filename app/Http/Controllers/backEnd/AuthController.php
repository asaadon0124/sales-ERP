<?php

namespace App\Http\Controllers\backEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\backEnd\loginRequest;



class AuthController extends Controller
{
    public function login()
    {
        return view('auth.backEnd.login');
    } 

    public function makeLogin(loginRequest $request)
    {
        $remmberMe = $request->has('remember_me') ? true : false;

        if(Auth::guard('admin')->attempt(['email' =>$request->email,'password' =>$request->password]))
        {
            return redirect(RouteServiceProvider::DASHBORD);

        }
        return back()->with(['error' => 'البيانت غير صحيحة']);
    }
    

    public function dashBoard()
    {
        return view('backEnd.dashboard');
    }

    public function logout()
    {
        \auth()->guard('admin')->logout();
        return redirect()->route('backEnd.login');
    }
}
