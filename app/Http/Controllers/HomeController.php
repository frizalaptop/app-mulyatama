<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mengarahkan respon halaman berdasarkan role user
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $userRole = Auth::user()->getRoleNames()->first();

        if($userRole === 'Admin'){
            return redirect()->route('admin.index');
        } elseif ($userRole === 'Klien') {
            return redirect()->route('klien.index');
        } else {
            return view('home');
        }
    }
}
