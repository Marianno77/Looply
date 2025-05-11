<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ])) {
            return redirect()->intended('/');
        } else {
            return back()->withErrors([
                'email' => 'Podano błędne dane logowania.',
            ]);
        }
    }
}
