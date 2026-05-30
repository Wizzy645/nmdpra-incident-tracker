<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    public function showLoginForm() { return view('auth.login'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login_at' => now(), 'last_login_ip' => $request->ip()]);
            return in_array($user->role, ['nmdpra_admin', 'nmdpra_inspector', 'system_auditor']) 
                ? redirect()->route('dashboard.admin') 
                : redirect()->route('dashboard.operator');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
