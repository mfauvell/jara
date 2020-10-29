<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Ingredient;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Police;
use App\Http\Resources\IngredientResource;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        if (!$this->police->can_do('ingredient','view',auth()->user(),$ingredient)) {
            Log::warning('No authorized attempt of get an Ingredient', ['user' => auth()->user()->id, 'ingredient' => $ingredient->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        return response(['data' => IngredientResource::make($ingredient)], 200);
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
            Log::warning('No authorized attempt of create an Ingredient', ['request' => $request, 'user' => auth()->user()->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
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
            return response(['data' => IngredientResource::make($ingredient)],201);
        } catch (Throwable $e) {
            Log::error('An error has ocurred when create an ingredient', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
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
            Log::warning('No authorized attempt of update an Ingredient', ['request' => $request, 'user' => auth()->user()->id, 'ingredient' => $ingredient->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
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
            return response(['data' => IngredientResource::make($ingredient)],200);
        } catch (Throwable $e) {
            Log::error('An error has ocurred when update an ingredient', ['exception' => $e, 'request' => $request, 'ingredient' => $ingredient->id]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
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
            Log::warning('No authorized attempt of delete an Ingredient', ['user' => auth()->user()->id, 'ingredient' => $ingredient->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $ingredient->delete();
            return response(['data' => $ingredient->id],200);
        } catch(Throwable $e) {
            Log::error('An error has ocurred when delete an ingredient', ['exception' => $e, 'ingredient' => $ingredient->id]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }

    public function uploadImage(Request $request) {
        if (!$this->police->can_do('ingredient','uploadImage',auth()->user())) {
            Log::warning('No authorized attempt of upload an image to an Ingredient', ['request' => $request, 'user' => auth()->user()->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $params = $request->all();

            $image = Image::upload('ingredient', $params['title'], $params['file']);

            return response(['data' => ImageResource::make($image)],200);
        } catch(Throwable $e) {
            Log::error('An error has ocurred when upload an imate to an ingredient', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }

    public function search(Request $request) {
        if (!$this->police->can_do('ingredient','view',auth()->user())) {
            Log::warning('No authorized attempt of search Ingredients', ['request' => $request, 'user' => auth()->user()->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $params = $request->all();

            $ingredients = Ingredient::search($params);

            return response(['data' => IngredientResource::collection($ingredients)],200);
        } catch(Throwable $e) {
            Log::error('An error has ocurred when search ingredients', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }
}
