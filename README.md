# Github Login Site

Site built with Laravel to enable Github access and repository viewing.

The code leverages the Socialite package as well as Guzzle to login and pull information.

Laravel Cache is used to store the repositories.  This can be configured to use Memcache given it is setup.

Docker files have been included for easy setup although other local stacks can be used.

# Installation Instructions

1. Run make command to build docker images based off docker-compose.yml.  This will also setup the initial database (no schemas)
and the node assets via the node image.  
```
make
```

You may consider changing traefik's port to 80 using docker-compose.override.yml (excluded file).
docker-compose.override.yml
```
version: "3"

services:

  traefik:
    ports:
      - '80:80'
```

2. Create a .env file from the .env.example.  Adjust configuration details as needed.

2. Shell into the PHP instance to setup all the commands.
```
make shell
composer install
php artisan key:generate
php artisan migrate
```

3. Setup a Github OAuth Application here https://developer.github.com/apps/building-oauth-apps/.
Authorization Callback URL should be set to http://vanilla.docker.localhost/login/github/callback

4. Input the client secret ID in the .env file under the GITHUB_CLIENT_ID and GITHUB_CLIENT_SECRET respectively.

# Usage Instructions
1. Navigate to http://vanilla.docker.localhost and click Login at top

2. Click Login with Github

3. Proceed with Github login

4. Observe you are navigated to a page with your repos displayed.