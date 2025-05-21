<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('client')->attempt($credentials)) {
            return redirect()->route('client.dashboard');
        }
        return back()->withErrors(['email' => 'Only Clients Can Logged In From here.']);
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        return redirect()->route('home');
    }

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Client::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'client'
            ]);

            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // event(new Registered($client));

            Auth::guard('client')->login($client);
            DB::commit();
            return redirect(route('client.dashboard', absolute: false));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
