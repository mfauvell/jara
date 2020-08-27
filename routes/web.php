<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes(['register' => false]);

Route::get('/', function () {
    if (auth()->guest()) {
        return view('auth/login');
    } else {
        return view('home');
    }
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
});
