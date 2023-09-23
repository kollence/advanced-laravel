<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmYourEmail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // Hash the email using SHA-256
        $hash = hash('sha256', $request->email);
        // Truncate the hash to 25 characters
        $uniqueToken = substr($hash, 0, 25);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirmation_token' => $uniqueToken,
        ]);

        // event(new Registered($user));
        $this->registered($user);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function confirm()
    {   
        $user = User::where('confirmation_token', request()->query('token'))->first();
        // if user is not found by token
        if(! $user){
            return redirect(route('threads.index'))->with('flash', 'The confirmation link is invalid.');
        }
        
        $user->update(['confirmed_email' => true, 'confirmation_token' => null]);

        return redirect(route('threads.create'));
    }

    protected function registered($user)
    {
        Mail::to($user->email)->send(new ConfirmYourEmail($user));
    }
}
