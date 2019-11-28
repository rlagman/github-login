<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use Socialite;
use App\User;

class LoginGithubController extends LoginController
{
    /*
    |--------------------------------------------------------------------------
    | Login Github Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    const CACHE_LIFETIME = 86400;
 
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
        $account = Socialite::driver('github')->user();

        // Store information regarding the users repositories (cached).
        $this->loadRepositories($account);

        // Register the user.
        $user = $this->createUser($account, 'github');

        // Login the user.
        auth()->login($user);

        // Redirect back to the homepage.
        return redirect()->route('home');
    }

    /**
     * Load repositories for a particular user.
     * 
     * @param string $account
     *   The account details.
     */
    protected function loadRepositories($account) 
    {
        return Cache::remember("github_repos_{$account->id}", self::CACHE_LIFETIME, function () use ($account) {
            // Call GitHub API to get user repositories.
            $token = $account->token;

            $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.github.com']);
            $response = $client->request(
                'GET',
                '/user/repos',
                [
                    'headers' => [
                        'Authorization' => "Bearer {$token}",
                        'Accept' => 'application/json',
                    ],
                ]
            );

            // Init the result.
            $result = [];

            // Retrieve the repositories.
            $repos = json_decode($response->getBody());

            // Iterate through each.
            foreach ($repos as $repo)
            {
                // Extract all the details.
                $owner = $repo->owner->login;

                // Add the repo name to this owner.
                $result[$owner][] = $repo;
            }

            return $result;
        });
    }

    /**
     * Attempt to create a user based on a Socialite user.
     */
    protected function createUser($account, $provider) 
    {
        $user = User::where('provider_id', $account->id)->first();

        // Check that we found a user.
        if (!$user) 
        {
            // Create one if we have not found one.
            $user = User::create([
                'name' => $account->name,
                'email' => $account->email,
                'password' => Str::random(),
                'provider' => $provider,
                'provider_id' => $account->id,
            ]);
        }

        return $user;
    }
}
