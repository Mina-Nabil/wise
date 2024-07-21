<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    // public function welcome()
    // {
    //     return view('welcome');
    // }

    public function index()
    {
        return view('home.index');
    }

    public function calendar()
    {
        return view('home.calendar');
    }

    public function login(Request $request)
    {
        /** @var User */
        $user = Auth::user();
        if ($user) return redirect('/');
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->action([self::class, 'login']);
    }

    public function authenticate(LoginRequest $request)
    {

        $res = User::login(...$request->validated());
        if ($res === true) {
            return redirect('/');
        } else {
            return redirect('login')->with(['alert_msg' => $res]);
        }
    }
}
