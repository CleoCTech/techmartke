<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     *
     * @return \Inertia\Response
     */
    public function edit()
    {
        return Inertia::render('Admin/Pages/Settings/ChangePassword');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // return to_route('password.edit', ['status' => 'Password updated successfully.']);
         // Flash status to shared data
        Inertia::share('success', 'Password updated successfully.');

        return to_route('password.edit');

         // Use Inertia's to_route() to pass the status prop
        return to_route('password.edit', ['status' => 'Password updated successfully.']);
        return redirect()->route('password.edit')->with('status', 'Password updated successfully.');
    }
}
