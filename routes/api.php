<?php

use App\Http\Controllers\IngredientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Auth\PassportController@login')->name('login');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    #Auth
    Route::post('/logout', 'Auth\PassportController@logout');
    #Ingredient
    Route::get('/ingredients', 'IngredientController@search');
    Route::get('/ingredients/{ingredient}', 'IngredientController@show');
});
