<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;


    /**
     * To enable softdeletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function imagable(){
        return $this->morphTo();
    }

    /**
     * Undocumented function
     *
     * @param String $prefix
     * @param UploadedFile $file
     * @return void
     */
    public static function upload(String $prefix, UploadedFile $file){
        try {
            $content = File::get($file->getPathName());
            $fileName = $prefix . '_' . date('YmdHis') . '_' . $file->getClientOriginalName();
            Storage::disk('images')->put($fileName, $content);
            #Create db row
            $image = new Image();
            $image->title = '';
            $image->file_name = $fileName;
            $image->mime = $file->getMimeType();
            $image->md5 = md5(storage_path('app/images/'.$fileName));
            $image->path = 'app/images';
            $image->size = File::size(storage_path('app/images/'.$fileName));
            $image->save();
            return $image;
        } catch(\Exception $e) {
            var_dump($e);
            return 0;
        }
    }
}
