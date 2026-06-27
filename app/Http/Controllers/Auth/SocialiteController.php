<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->email)->first();

        if (! $user) {
            $user = User::create([
                'email' => $googleUser->email,
                'name' => $googleUser->name ?? '',
                'provider_id' => $googleUser->id,
                'avatar_url' => $googleUser->avatar,
                'role' => 'new',
            ]);
        }

        Auth::login($user);

        if ($user->role === 'new') {
            return redirect('/role');
        }

        return redirect('/');
    }
}
