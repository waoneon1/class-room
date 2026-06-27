<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginField => $request->login, 'password' => $request->password];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'login' => 'Email/username atau password salah.',
            ])->onlyInput('login');
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectByRole()
    {
        return match (auth()->user()->role) {
            'admin' => redirect('/admin/pengguna'),
            'guru'  => redirect('/guru/dashboard'),
            'siswa' => redirect('/siswa/dashboard'),
            default => redirect('/login'),
        };
    }
}
