<?php

namespace App\Http\Controllers\Klien;

use App\Http\Controllers\Controller;

/**
 * Controller khusus role Klien
 */
class DashboardController extends Controller
{
    /**
     * Mengembalikan view home user
     * @return \Illuminate\Contracts\View\View
     */
    public function index () {
        return view('home');
    }
}
