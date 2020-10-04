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
        return $ingredient->id;
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
        if (!$this->police->can_do('ingredient','edit',auth()->user())) {
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
        return $rdo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function delete(Ingredient $ingredient)
    {
        //
    }

    public function uploadImage(Request $request) {
        #Anybody logged can upload files
        $params = $request->all();

        //TODO: Control error
        return Image::upload('ingredient', $params['file']);
    }

    public function search(Request $request) {
        if (!$this->police->can_do('ingredient','view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();

        $ingredients = Ingredient::search($params);

        return response(['data' => IngredientResource::collection($ingredients)],200);
    }

    // public function getIngredient(int $ingredient_id) {
    //     if (!$this->police->can_do(Ingredient::class,'view',auth()->user())) {
    //         return response()->json(['error' => 'Not authorized.'],403);
    //     }
    //     $ingredient = Ingredient::find($ingredient_id);
    //     return array(
    //         'ingredient' => $ingredient,
    //         'image' => $ingredient->images()->first()
    //     );
    // }
}
