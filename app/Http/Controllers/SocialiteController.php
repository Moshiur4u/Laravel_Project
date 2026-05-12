<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle(){
    return Socialite::driver('google')->redirect();
    }

    // ধাপ ২: Google থেকে ফিরে আসার পর
  public function handleGoogleCallback() {
    try {
      // Google থেকে user info আনা
      $googleUser = Socialite::driver('google')->Statless()->user();
        dd();
      // DB-তে খোঁজা বা নতুন তৈরি
      $user = User::updateOrCreate(
        ['google_id' => $googleUser->id],
        [
          'name' => $googleUser->name,
          'email' => $googleUser->email,
          'avatar' => $googleUser->avatar,
          'password' => null,
        ]
      );

      // Login করানো
      Auth::login($user, true);

      return redirect()->intended('dashboard')
               ->with('success', 'সফলভাবে লগইন হয়েছে!');

    } catch (\Exception $e) {
      return redirect()->route('login')
               ->with('error', 'Google Login ব্যর্থ হয়েছে!');
    }
  }
}
