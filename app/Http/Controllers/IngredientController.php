<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Police;
use App\Http\Resources\IngredientResource;

class IngredientController extends Controller
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

    public function show(Ingredient $ingredient)
    {
        return response(['data' => new IngredientResource($ingredient)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->police->can_do('ingredient','create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $ingredient = new Ingredient();
        $ingredient->id = 0;
        $ingredient->name = $params['name'];
        $rdo = $ingredient->save();
        if ($params['nextImage'] != 0) {
            $image = Image::find($params['nextImage']);
            $ingredient->images()->save($image);
            $rdo = $ingredient->save();
        }
        return response(['data' => new IngredientResource($ingredient)],201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Ingredient  $ingredient_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        if (!$this->police->can_do('ingredient','edit',auth()->user(),$ingredient)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $ingredient->name = $params['name'];
        $rdo = $ingredient->save();
        if ($params['currentImage'] != $params['nextImage'] && $rdo == 1) {
            if ($params['currentImage'] != 0) {
                $image = Image::find($params['currentImage']);
                $image->delete();
            }
            if ($params['nextImage'] != 0) {
                $image = Image::find($params['nextImage']);
                $ingredient->images()->save($image);
                $rdo = $ingredient->save();
            }
        }
        return response(['data' => new IngredientResource($ingredient)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function delete(Ingredient $ingredient)
    {
        if (!$this->police->can_do('ingredient','delete',auth()->user(),$ingredient)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $ingredient->delete();
        return response(['data' => $ingredient->id],200);
    }

    public function uploadImage(Request $request) {
        #TODO: Change to API response
        #Anybody logged can upload files
        $params = $request->all();

        //TODO: Control error
        return Image::upload('ingredient', $params['title'], $params['file']);
    }

    public function search(Request $request) {
        if (!$this->police->can_do('ingredient','view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();

        $ingredients = Ingredient::search($params);

        return response(['data' => IngredientResource::collection($ingredients)],200);
    }
}
