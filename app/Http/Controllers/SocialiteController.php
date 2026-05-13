<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

  // ধাপ ১: Google-এ redirect করা
  public function redirectToGoogle() {
    return Socialite::driver('google')->redirect();
  }

  // ধাপ ২: Google থেকে ফিরে আসার পর
  public function handleGoogleCallback() {
      // Google থেকে user info আনা
      $googleUser = Socialite::driver('google')->stateless()->user();
    //  return response()->json($googleUser);
      // DB-তে খোঁজা বা নতুন তৈরি
      $user = User::updateOrCreate(
        // ['google_id' => $googleUser->id],
        [
          'name' => $googleUser->name,
          'email' => $googleUser->email,
        //   'avatar' => $googleUser->avatar,
          'password' => null,
        ]
      );

      // Login করানো
      Auth::login($user, true);

      return view('dashboard.dashboard');


  }
}
