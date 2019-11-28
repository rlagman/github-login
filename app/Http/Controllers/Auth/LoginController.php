<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();

        // Register the user.
        $this->createUser($user, 'github');
        


        // Redirect back to the homepage.
        return redirect('/');
    }

    /**
     * Attempt to create a user based on a Socialite user.
     */
    protected function createUser($account, $provider) {
        $user = User::where('provider_id', $account->id)->first();

        // Check that we found a user.
        if (!$user) {
            // Create one if we have not found one.
            $user = User::create([
                'name' => $account->name,
                'email' => $account->email,
                'provider' => $provider,
                'provider_id' => $account->id,
            ]);
        }

        return $user;
    }
}
