<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class GithubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('home', function($view) {
            $repositories = [];

            // Retrieve the account from GitHub.
            $user = auth()->user();
            if ($user && $user->provider == 'github') {
                // Add all repositories for this user.
                $repositories = Cache::get("github_repos_{$user->provider_id}");
            }

            $view->with('repositories', $repositories);
        });
    }
}
