<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class TwoFactorAuthenticatedSessionController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/TwoFactorChallenge', [
            'status' => session('status'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();

        if ($request->code === $user->two_factor_code) {
            $user->resetTwoFactorCode();
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'code' => 'The provided two factor authentication code was invalid.',
        ]);
    }
} 