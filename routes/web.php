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
    #User routes
    Route::get('/admin/users', 'UserController@index');
    Route::get('/admin/users/create','UserController@create');
    Route::get('/admin/users/search', 'UserController@search');
    Route::post('/admin/users','UserController@store');
    Route::get('/admin/users/{user_id}','UserController@edit');
    Route::post('/admin/users/{user_id}','UserController@update');
    Route::post('/admin/users/{user_id}/delete','UserController@delete');
    #Recipe routes
    Route::get('/recipes', 'RecipeController@index');
    Route::get('/recipes/create','RecipeController@create');
    Route::post('/recipes','RecipeController@store');
    Route::get('/recipes/{recipe_id}','RecipeController@show');
    Route::post('/recipes/{recipe_id}','RecipeController@update');
    Route::post('/recipes/{recipe_id}/delete','RecipeController@delete');
    #Ingredient routes
    Route::get('/ingredients', 'IngredientController@index');
    Route::get('/ingredients/create','IngredientController@create');
    Route::get('/ingredients/search', 'IngredientController@search');
    Route::post('/ingredients','IngredientController@store');
    Route::get('/ingredients/{ingredient_id}','IngredientController@edit');
    Route::post('/ingredients/{ingredient_id}','IngredientController@update');
    Route::post('/ingredients/{ingredient_id}/delete','IngredientController@delete');
    Route::post('/ingredients/image/upload', 'IngredientController@uploadImage');
    #Images
    Route::get('/images/{image_id}', 'ImageController@getImage');
});
