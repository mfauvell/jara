<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Police;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->police->can_do(Ingredient::class,'view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $ingredients = Ingredient::all();
        $ingredientsData = $ingredients->map(function ($ingredient) {
            return array(
                'ingredient' => $ingredient,
                'image' => $ingredient->images()->first()
            );
        });
        return view('ingredients/list')->with([
            'ingredients' => $ingredientsData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->police->can_do(Ingredient::class,'create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $ingredient = new Ingredient();
        $ingredient->id = 0;
        $image = new Image();
        $image->id = 0;
        return view('ingredients/form')->with([
            'ingredient' => $ingredient,
            'image' => $image
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
        if (!$this->police->can_do(Ingredient::class,'create',auth()->user())) {
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
     * Show the form for editing the specified resource.
     *
     * @param  int $ingredient_id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $ingredient_id)
    {
        if (!$this->police->can_do(Ingredient::class,'edit',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $ingredient = Ingredient::find($ingredient_id);
        $image = $ingredient->images()->first();
        if ($image == '') {
            $image = new Image();
            $image->id = 0;
        };
        return view('ingredients/form')->with([
            'ingredient' => $ingredient,
            'image' => $image
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ingredient_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $ingredient_id)
    {
        if (!$this->police->can_do(Ingredient::class,'edit',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $ingredient = Ingredient::find($ingredient_id);
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
     * @param  int $ingredient_id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $ingredient_id)
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
        if (!$this->police->can_do(Ingredient::class,'view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();

        $ingredients = Ingredient::search($params);
        $ingredientsData = $ingredients->map(function ($ingredient) {
            return array(
                'ingredient' => $ingredient,
                'image' => $ingredient->images()->first()
            );
        });
        return $ingredientsData;
    }

    public function getIngredient(int $ingredient_id) {
        if (!$this->police->can_do(Ingredient::class,'view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $ingredient = Ingredient::find($ingredient_id);
        return array(
            'ingredient' => $ingredient,
            'image' => $ingredient->images()->first()
        );
    }
}
