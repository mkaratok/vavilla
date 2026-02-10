<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'kullanici_adi' => 'required|string',
            'sifre' => 'required|string',
        ]);

        $admin = Admin::where('kullanici_adi', $request->kullanici_adi)->first();

        if (!$admin) {
            return back()->with('error', 'Kullanıcı bulunamadı.');
        }

        // Legacy system uses MD5, check both MD5 and bcrypt
        $passwordValid = false;
        
        // Check MD5 (legacy)
        if ($admin->sifre === md5($request->sifre)) {
            $passwordValid = true;
            // Optionally upgrade to bcrypt
            // $admin->sifre = Hash::make($request->sifre);
            // $admin->save();
        }
        
        // Check bcrypt (new)
        if (!$passwordValid && Hash::check($request->sifre, $admin->sifre)) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            return back()->with('error', 'Şifre hatalı.');
        }

        // Update last login info
        $admin->songiris = now()->format('d.m.Y H:i');
        $admin->songirisip = $request->ip();
        $admin->save();

        Auth::guard('admin')->login($admin, $request->has('remember'));

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
