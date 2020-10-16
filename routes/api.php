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
    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // });
    #Auth
    Route::post('/logout', 'Auth\PassportController@logout');
    #User
    Route::get('/users','UserController@search');
    Route::get('/users/{user}', 'UserController@show');
    #Ingredient
    Route::get('ingredients', 'IngredientController@search');
    Route::post('ingredients','IngredientController@store');
    Route::get('ingredients/{ingredient}', 'IngredientController@show');
    Route::put('ingredients/{ingredient}', 'IngredientController@update');
    Route::delete('ingredients/{ingredient}', 'IngredientController@delete');
    Route::post('ingredients/image', 'IngredientController@uploadImage');
    #Recipe
    Route::get('/recipes', 'RecipeController@search');
    Route::get('/recipes/{recipe}', 'RecipeController@show');
});

Route::group(['middleware' => ['signed']], function () {
    Route::get('/images/{image}','ImageController@getImage')->name('getImage');
});
