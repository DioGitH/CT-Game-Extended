<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index');
        } elseif ($user->hasRole('student')) {
            if (!$user->tutorial_viewed) {
                session()->flash('show_tutorial_popup', true);
            }
            return redirect()->route('student.profile.index');
        } elseif ($user->hasRole('lecturer')) {
            return redirect()->route('lecturer.dashboard');
        }

        // Default redirect jika tidak ada role yang cocok
        return redirect('/');
        // ->route('login');
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
