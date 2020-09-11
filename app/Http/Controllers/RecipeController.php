<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Image;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\Police;
use App\Models\Visibility;
use Illuminate\Http\Request;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->police->can_do(Recipe::class,'create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $recipe = new Recipe();
        $recipe->id = 0;
        $images = $recipe->images()->get();
        $ingredients = $recipe->ingredients()->get();
        $visibilities = Visibility::all();
        $steps = $recipe->steps()->get();
        $steps = $steps->map(function($step) {
            $step->images = $step->images()->get();
            return $step;
        });
        return view('recipes/form')->with([
            'recipe' => $recipe,
            'images' => $images,
            'ingredients' => $ingredients,
            'visibilities' => $visibilities,
            'steps' => $steps
        ]);
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
     * Display the specified resource.
     *
     * @param  int  $recipe_id
     * @return \Illuminate\Http\Response
     */
    public function show(int $recipe_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $recipe_id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $recipe_id)
    {
        if (!$this->police->can_do(Recipe::class,'edit',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $recipe = Recipe::find($recipe_id);
        $images = $recipe->images()->get();
        $ingredients = $recipe->ingredients()->get();
        $visibilities = Visibility::all();
        $steps = $recipe->steps()->get();
        $steps = $steps->map(function($step) {
            $step->images = $step->images()->get();
            return $step;
        });
        return view('recipes/form')->with([
            'recipe' => $recipe,
            'images' => $images,
            'ingredients' => $ingredients,
            'visibilities' => $visibilities,
            'steps' => $steps
        ]);
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
        if (!$this->police->can_do(Recipe::class,'edit',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $recipe = Recipe::find($recipe_id);
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
        //
    }

    public function uploadImage(Request $request) {
        #Anybody logged can upload files
        $params = $request->all();

        //TODO: Control error
        return Image::upload('recipe', $params['file']);
    }
}
