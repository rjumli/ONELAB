<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        if($request->option === 'activation'){
            $validated = $request->validate([
                'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
            ]);
            
            $request->user()->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => now(),
                'is_active' => 1
            ]);
        }else{
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => now()
            ]);
        }

        return back();
    }
}
