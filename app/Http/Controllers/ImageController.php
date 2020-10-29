<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Police;
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

    public function getImage(Image $image) {
        if (!$image) {
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
}
