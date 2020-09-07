<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Image;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        return view('recipes/form')->with([
            'recipe' => $recipe,
            'images' => $images,
            'ingredients' => $ingredients,
            'visibilities' => $visibilities
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
