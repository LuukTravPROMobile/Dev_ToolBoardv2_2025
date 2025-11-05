<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validatie
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Zoek gebruiker op email
        $user = User::where('email', $credentials['email'])->first();

        // Controleer of gebruiker bestaat en wachtwoord klopt
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect()->intended('/dashboard'); // of je gewenste route
        }

        // Foutmelding bij verkeerde login
        return back()->withErrors([
            'email' => 'Onjuiste inloggegevens.',
        ]);
    }
}
