<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Police;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $recipeController = new RecipeController(new Police());
        $publicRecipes = $recipeController->search(new Request([
            'latest' => 1,
            'limit' => 10,
            'onlyPublic' => 1
        ]));
        $privateRecipes = $recipeController->search(new Request([
            'latest' => 1,
            'limit' => 10,
            'onlyPrivate' => 1
        ]));
        return view('home')->with([
            'lastPublicRecipes' => $publicRecipes,
            'lastPrivateRecipes' => $privateRecipes
        ]);
    }
}
