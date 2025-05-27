<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.users.index');
                } elseif ($user->hasRole('student')) {
                    return redirect()->route('student.profile.index');
                } elseif ($user->hasRole('lecturer')) {
                    return redirect()->route('lecturer.dashboard');
                }

                return redirect('/');
            }
        }

        return $next($request);
    }
}
