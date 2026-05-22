<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password-manual');
    }

    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'name' => 'required',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = User::where('email', $request->email)
                ->where('name', $request->name)
                ->first();

    if (!$user) {
        return back()->with('error', 'Email atau nama tidak cocok');
    }

    if ($request->filled('password_lama')) {
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }
    }

    // ✅ pakai 'password' (bukan password_baru)
    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return redirect('/login')->with('success', 'Password berhasil diubah, silakan login');
}
}