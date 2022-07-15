<?php

namespace App\Repositories;

use App\Http\Traits\SetPath;
use App\Models\AwsVideo;
use App\Http\Traits\UploadFiles;
use App\Jobs\SaveFrameFromSeconds;
use App\Jobs\SaveNewFormatFirst;
use App\Jobs\SaveNewFormatSecond;
use App\Jobs\SaveNewFormatThird;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoRepository
{
    use UploadFiles, SetPath;

    public function __construct()
    {
        $this->video = app(AwsVideo::class);
    }

    public function uploadVideo($request)
    {
        $this->setPathType($request);
        $response = $this->uploadFile($request);
        $data['url'] = $response;
        $data['url_signed'] = $this->getAuthorizationToDownloadFile($data['url']);
        $data['duaration_in_seconds'] = $this->getDurationInSeconds($data['url_signed']);
        $data['created_at'] = Carbon::now()->toDateString();
        $data['updated_at'] = Carbon::now()->toDateString();
        $video = $this->video->create($data);
        $videoId = $this->video->select('id')->orderBy('id', 'desc')->first();

        SaveFrameFromSeconds::dispatch($video);
        SaveNewFormatFirst::dispatch($video);
        SaveNewFormatSecond::dispatch($video);
        SaveNewFormatThird::dispatch($video);

        return response()->json([
            'message' => 'Recurso adicionado!',
            'video_id' => $videoId->id,
            'duaration_in_seconds' => $data['duaration_in_seconds'],
        ], 201);
    }

    public function getDurationInSeconds($urlSigned)
    {
        return  FFMpeg::openUrl($urlSigned)
            ->getDurationInSeconds();
    }

    public function saveFrameFromSeconds($urlSigned, $dimensions, $videoId, $seconds = 1)
    {
        FFMpeg::openUrl($urlSigned)
            ->getFrameFromSeconds($seconds)
            ->export()
            ->toDisk('public')
            ->save("teste-${$dimensions}.jpg");

        $url_file = $this->uploadPublicFileCompress(Storage::disk('public')->get("teste-${$dimensions}.jpg"), "teste-${$dimensions}-" . Str::uuid() . ".jpg");
        $this->removeImagePublicDisk("teste-${$dimensions}.jpg");
        $this->video->where('id', $videoId)->update(['thumbnail_url' => $url_file]);
    }
}
