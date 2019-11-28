<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Login routes for Github.
Route::get('/login/github', 'Auth\LoginController@redirectToProvider')->name('github.login');
Route::get('/login/github/callback', 'Auth\LoginController@handleProviderCallback')->name('github.login.callback');
