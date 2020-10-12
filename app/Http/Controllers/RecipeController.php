<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Models\Image;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\Police;
use App\Models\Visibility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RecipeController extends Controller
{

    /**
     * Police to control permissions
     *
     * @var \App\Models\Police
     */
    protected $police;

    public function __construct(Police $police)
    {
        $this->police = $police;
    }

    public function show(Recipe $recipe) {
        if (!$this->police->can_do('recipe','create',auth()->user(),$recipe)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        return response(['data' => RecipeResource::make($recipe)],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->police->can_do(Recipe::class,'create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $recipe = new Recipe();
        $recipe->id = 0;
        $recipe->title = $params['title'];
        $recipe->description = $params['description'];
        $recipe->time = $params['time'];
        $recipe->visibility_id = $params['visibility'];
        $recipe->user_id = auth()->user()->id;
        $rdo = $recipe->save();
        if ($params['nextImages'] && $rdo == 1) {
            foreach($params['nextImages'] as $addImage){
                $image = Image::find($addImage);
                $recipe->images()->save($image);
                $rdo = $recipe->save();
            }
        }
        if (isset($params['ingredientsList']) && $rdo == 1) {
            foreach ($params['ingredientsList'] as $ingredient_row) {
                $ingredient = Ingredient::find($ingredient_row['id']);
                $recipe->ingredients()->attach($ingredient, ['quantity' => $ingredient_row['quantity'], 'unit' => $ingredient_row['unit']]);
            }
        }
        if ($params['nextSteps'] && $rdo == 1) {
            foreach($params['nextSteps'] as $addStep){
                $step = Step::find($addStep);
                $recipe->steps()->save($step);
                $rdo = $recipe->save();
            }
        }
        return $recipe->id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $recipe_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $recipe_id)
    {
        $recipe = Recipe::find($recipe_id);
        if (!$this->police->can_do(Recipe::class,'edit',auth()->user(),$recipe)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $recipe->title = $params['title'];
        $recipe->description = $params['description'];
        $recipe->time = $params['time'];
        $recipe->visibility_id = $params['visibility'];
        $recipe->user_id = auth()->user()->id;
        $rdo = $recipe->save();
        if ($params['currentImages'] != $params['nextImages'] && $rdo == 1) {
            $deleteImages = array_diff($params['currentImages'],$params['nextImages']);
            foreach($deleteImages as $delImage) {
                $image = Image::find($delImage);
                $image->delete();
            }
            foreach($params['nextImages'] as $addImage){
                $image = Image::find($addImage);
                $recipe->images()->save($image);
                $rdo = $recipe->save();
            }
        }
        if (isset($params['ingredientsList']) && $rdo == 1) {
            $recipe->ingredients()->detach();
            foreach ($params['ingredientsList'] as $ingredient_row) {
                $ingredient = Ingredient::find($ingredient_row['id']);
                $recipe->ingredients()->attach($ingredient, ['quantity' => $ingredient_row['quantity'], 'unit' => $ingredient_row['unit']]);
            }
        }
        if ($params['currentSteps'] != $params['nextSteps'] && $rdo == 1) {
            $deleteSteps = array_diff($params['currentSteps'],$params['nextSteps']);
            foreach($deleteSteps as $delStep) {
                $step = Step::find($delStep);
                $step->delete();
            }
            foreach($params['nextSteps'] as $addStep){
                $step = Step::find($addStep);
                $recipe->steps()->save($step);
                $rdo = $recipe->save();
            }
        }
        return $rdo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $recipe_id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $recipe_id)
    {
        $recipe = Recipe::find($recipe_id);
        if (!$this->police->can_do(Recipe::class,'delete',auth()->user(),$recipe)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $recipe->delete();
        return Redirect::route('recipes');
    }

    public function uploadImage(Request $request) {
        #Anybody logged can upload files
        $params = $request->all();

        //TODO: Control error
        return Image::upload('recipe', $params['file']);
    }

    public function search(Request $request) {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        if (!$this->police->can_do('recipe','view',$user)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        #TODO: Change limit on search.
        $params['admin'] = false;
        if ($user->role()->first()->name == 'SuperAdmin') {
            $params['admin'] = true;
        }
        $recipes = Recipe::search($params);

        return response(['data' => RecipeResource::collection($recipes)],200);
    }
}
