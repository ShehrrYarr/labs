<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Publication;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user() != null) {
            if (Auth::user()->is_admin) {
                $totalPublications = Publication::get()->count();
                $totalUsers = User::get();
                return view('admin_dashboard', compact('totalUsers', 'totalPublications'));
            }
            else if(!Auth::user()->is_admin){
                return redirect()->route('user.index');
            }
        }
       ;
        return view('home');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
