<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home'; // Ini default, akan kita timpa fungsinya

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // --- TAMBAHKAN FUNGSI INI ---
    protected function authenticated(Request $request, $user)
    {
        // Jika rolenya admin, lempar ke dashboard admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa, lempar ke halaman menu
        return redirect()->route('user.menus');
    }
}
