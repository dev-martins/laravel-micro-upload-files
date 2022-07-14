<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Eihror\Compress\Compress;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadFiles
{
    public function uploadPublicFile($file, $folder = "images")
    {
        $filePath =  $folder . DIRECTORY_SEPARATOR;
        if (Storage::disk('s3-public')->put($filePath, $file))
            return env('CLOUD_FRONT_PUBLIC') . $filePath .  $file->hashName();
    }

    public function uploadPublicFileCompress($file, $file_name, $folder = 'images')
    {
        $filePath =  $folder . DIRECTORY_SEPARATOR;
        if (Storage::disk('s3-public')->put($filePath . $file_name, (string) $file))
            return env('CLOUD_FRONT_PUBLIC') . $filePath .  $file_name;
    }

    public function uploadFileConvert($file, $file_name, $folder = 'videos')
    {
        $filePath =  $folder . DIRECTORY_SEPARATOR;
        if (Storage::disk('s3')->put($filePath . $file_name, (string) $file))
            return  $filePath .  $file_name;
    }

    public function uploadFile($request)
    {
        $file = $request->file($request->input('pathType'));
        $filePath =  Str::plural($request->input('pathType')) . DIRECTORY_SEPARATOR;

        if (Storage::disk('s3')->put($filePath, $request->file($request->input('pathType'))))
            return $filePath .  $file->hashName();
    }

    public function getAuthorizationToDownloadFile($url = null, $model = null, $id = null, $column_select = null, $time = 3600)
    {
        if (!is_null($url)) {
            $url = Storage::disk('s3')->temporaryUrl(
                $url,
                Carbon::now()->addMinutes($time)
            );
        } else {
            $url = $model->where('id', $id)->select($column_select)->limit(1)->get();
            $url = Storage::disk('s3')->temporaryUrl(
                $url,
                Carbon::now()->addMinutes($time)
            );
        }
        return $url;
    }

    public function compressImages($file, $folder = 'images')
    {
        $quality = 1;
        $png_quality = 9;
        $folder = storage_path('app/public');
        $maxsize = 5245330;

        $image_compress = new Compress($file, $file->hashName(), $quality, $png_quality, $folder, $maxsize);
        $file = $image_compress->compress_image();

        $url_file = $this->uploadPublicFileCompress(Storage::disk('public')->get($file), $file);

        $this->removeImagePublicDisk($file);

        return $url_file;
    }

    public function removeImagePublicDisk($file, $disk = 'public')
    {
        Storage::disk($disk)->delete($file);
    }
}
