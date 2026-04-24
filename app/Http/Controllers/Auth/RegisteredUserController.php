<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VolunteerJoinedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'languages' => ['required', 'string', 'max:500'],
            'skills' => ['required', 'string', 'max:500'],
            'availability' => ['required', 'string', 'max:50'],
        ]);

        $isFirstUser = User::query()->doesntExist();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isFirstUser ? User::ROLE_ADMIN : User::ROLE_VOLUNTEER,
            'phone' => $request->phone,
            'city' => $request->city,
            'languages' => $request->languages,
            'skills' => $request->skills,
            'availability' => $request->availability,
        ]);

        event(new Registered($user));

        if ($user->role === User::ROLE_VOLUNTEER) {
            $admins = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->get();

            Notification::send($admins, new VolunteerJoinedNotification($user));
        }

        Auth::login($user);

        return redirect(route($user->dashboardRouteName(), absolute: false));
    }
}
