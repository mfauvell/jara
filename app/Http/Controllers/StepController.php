<?php

namespace App\Http\Controllers;

use App\Http\Resources\StepResource;
use App\Http\Resources\ImageResource;
use App\Models\Step;
use App\Models\Police;
use App\Models\Image;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;

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
            Log::warning('No authorized attempt of create step in a recipe', ['request' => $request, 'user' => auth()->user()->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
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
        } catch (Throwable $e) {
            Log::error('An error has ocurred when create a step', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
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
        if ($recipe) {
            if (!$this->police->can_do('recipe','edit',auth()->user(),$recipe)) {
                Log::warning('No authorized attempt of update a step in a recipe', ['request' => $request, 'user' => auth()->user()->id, 'recipe' => $recipe->id, 'step' => $step->id]);
                return response()->json(['error' => 'Not authorized.'],403);
            }
        } else {
            if (!$this->police->can_do('recipe','edit',auth()->user())) {
                Log::warning('No authorized attempt of update a step without a recipe', ['request' => $request, 'user' => auth()->user()->id, 'step' => $step->id]);
                return response()->json(['error' => 'Not authorized.'],403);
            }
        }
        try {
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
        } catch (Throwable $e) {
            Log::error('An error has ocurred when update a step', ['exception' => $e, 'request' => $request, 'step' => $step->id]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }

    public function delete(Step $step)
    {
        $recipe = $step->recipe()->first();
        if ($recipe) {
            if (!$this->police->can_do('recipe','delete',auth()->user(),$recipe)) {
                Log::warning('No authorized attempt of delete a step in a recipe', ['user' => auth()->user()->id, 'recipe' => $recipe->id, 'step' => $step->id]);
                return response()->json(['error' => 'Not authorized.'],403);
            }
        } else {
            if (!$this->police->can_do('recipe','delete',auth()->user())) {
                Log::warning('No authorized attempt of delete a step without a recipe', ['user' => auth()->user()->id, 'step' => $step->id]);
                return response()->json(['error' => 'Not authorized.'],403);
            }
        }
        try {
            $step->delete();
            return response(['data' => $step->id],200);
        } catch (Throwable $e) {
            Log::error('An error has ocurred when delete a step', ['exception' => $e, 'step' => $step->id]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }

    public function getStep(Step $step) {
        $recipe = $step->recipe()->first();
        if ($recipe) {
            if (!$this->police->can_do('recipe','view',auth()->user(),$recipe)) {
                Log::warning('No authorized attempt of get a step in a recipe', ['user' => auth()->user()->id, 'recipe' => $recipe->id, 'step' => $step->id]);
                return response()->json(['error' => 'Not authorized.'],403);
            }
        } else {
            if (!$this->police->can_do('recipe','view',auth()->user())) {
                Log::warning('No authorized attempt of get a step without a recipe', ['user' => auth()->user()->id, 'step' => $step->id]);
                return response()->json(['error' => 'Not authorized.'],403);
            }
        }

        return response(['data' => StepResource::make($step)],200);
    }

    public function uploadImage(Request $request) {
        if (!$this->police->can_do('recipe','uploadImage',auth()->user())) {
            Log::warning('No authorized attempt of upload an image to a step in a recipe', ['request' => $request, 'user' => auth()->user()->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $params = $request->all();

            $image = Image::upload('step', $params['title'], $params['file']);

            return response(['data' => ImageResource::make($image)],200);
        } catch (Throwable $e) {
            Log::error('An error has ocurred when upload a image to a step', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }
}
