<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\Police;
use App\Models\Image;
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
        #Anyboyd can edit recipes must be capable of create steps
        if (!$this->police->can_do(Recipe::class,'edit',auth()->user())) {
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
        return $step->id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Step  $step
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $step_id)
    {
        $step = Step::find($step_id);
        $recipe = $step->recipe();
        if (!$this->police->can_do(Recipe::class,'edit',auth()->user(),$recipe)) {
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
        return $rdo;
    }

    public function getStep(int $step_id) {
        $step = Step::find($step_id);
        $recipe = $step->recipe();
        if (!$this->police->can_do(Recipe::class,'view',auth()->user(),$recipe)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $step->images = $step->images()->get();
        return $step;
    }

    public function uploadImage(Request $request) {
        #Anybody logged can upload files
        $params = $request->all();

        //TODO: Control error
        return Image::upload('step', $params['file']);
    }
}
