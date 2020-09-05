<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Police;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
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


    public function getImage(int $image_id) {
        $image = Image::find($image_id);
        if (!$image && $image_id != 0) {
            abort(404);
        }
        if ($image_id == 0) {
            $path = storage_path('app' . DIRECTORY_SEPARATOR . 'no-image.png');
            $type = 'image/png';
        } else {
            $path = storage_path($image->path . DIRECTORY_SEPARATOR . $image->file_name);
            $type = $image->mime;
        }
        $file = File::get($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }
}
