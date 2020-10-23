<?php

namespace App\Http\Controllers;

use App\Http\Resources\StepResource;
use App\Http\Resources\ImageResource;
use App\Models\Step;
use App\Models\Police;
use App\Models\Image;
use App\Models\Recipe;
use Illuminate\Http\Request;

class StepController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->police->can_do('recipe','create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $step = new Step();
        $step->id = 0;
        $step->title = $params['title'];
        $step->description = $params['description'];
        $step->time = $params['time'];
        $step->order = $params['order'];
        $rdo = $step->save();
        if ($params['nextImages'] && $rdo == 1) {
            foreach($params['nextImages'] as $addImage){
                $image = Image::find($addImage);
                $step->images()->save($image);
            }
            $rdo = $step->save();
        }
        return response(['data' => StepResource::make($step)],201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Step  $step
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Step $step)
    {
        $recipe = $step->recipe()->first();
        if ($recipe && !$this->police->can_do('recipe','edit',auth()->user(),$recipe)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $step->title = $params['title'];
        $step->description = $params['description'];
        $step->time = $params['time'];
        $step->order = $params['order'];
        $rdo = $step->save();
        if ($params['currentImages'] != $params['nextImages'] && $rdo == 1) {
            $deleteImages = array_diff($params['currentImages'],$params['nextImages']);
            foreach($deleteImages as $delImage) {
                $image = Image::find($delImage);
                $image->delete();
            }
            foreach($params['nextImages'] as $addImage){
                $image = Image::find($addImage);
                $step->images()->save($image);
            }
            $rdo = $step->save();
        }
        return response(['data' => StepResource::make($step)],200);
    }

    public function getStep(Step $step) {
        $recipe = $step->recipe()->first();
        if (!$this->police->can_do('recipe','view',auth()->user(),$recipe)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        return response(['data' => StepResource::make($step)],200);
    }

    public function uploadImage(Request $request) {
        if (!$this->police->can_do('recipe','uploadImage',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $params = $request->all();

        $image = Image::upload('step', $params['title'], $params['file']);

        return response(['data' => ImageResource::make($image)],200);
    }
}
