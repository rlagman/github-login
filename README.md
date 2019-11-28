# Vanilla Login Site

Site built with Laravel to enable Github access and repository viewing.

Docker files have been included for easy setup although other local stacks can be used.

# Installation Instructions

1. Run make command to build docker images.  This will also setup the initial database (no schemas)
and the node assets via the node image.
```
make
```

2. Shell into the PHP instance to setup all the commands.
```
make shell
php artisan migrate
```
