<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function show() {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if(!Hash::check($request->input('old_password'), $user->password)) {
            return back()->withErrors(['old_password' => 'Obecne hasło jest nie poprawne']);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return back()->with('success', 'Hasło zostało zmienione');

    }
}
