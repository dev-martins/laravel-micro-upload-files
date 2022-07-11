<?php

namespace App\Repositories;

use App\Http\Traits\SetPath;
use App\Models\AwsVideo;
use App\Http\Traits\UploadFiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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
        return $this->saveFrameFromSeconds('https://puravidaprime.s3.sa-east-1.amazonaws.com/videos/Pbtk83XgpU1Vl4wkECqUnN0ZSWADHKXiF8a2bq62.mp4?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVTMKWUE2KMXLGOWZ%2F20220711%2Fsa-east-1%2Fs3%2Faws4_request&X-Amz-Date=20220711T194834Z&X-Amz-SignedHeaders=host&X-Amz-Expires=216000&X-Amz-Signature=8f6812f09ce1a9baab81b38296b892aa5bae5ccd6decca5f26e2bde799a62827');
        $this->setPathType($request);
        $response = $this->uploadFile($request);
        $data['url'] = $response;
        $data['url_signed'] = $this->getAuthorizationToDownloadFile($data['url']);
        $data['duaration_in_seconds'] = $this->getDurationInSeconds($data['url_signed']);
        $data['created_at'] = Carbon::now()->toDateString();
        $data['updated_at'] = Carbon::now()->toDateString();
        $video_id = $this->video->insertGetId($data);

        return response()->json([
            'video_id' => $video_id,
            'duaration_in_seconds' => $data['duaration_in_seconds'],
        ], 201);
    }

    public function getDurationInSeconds($urlSigned)
    {
        return  FFMpeg::openUrl($urlSigned)
            ->getDurationInSeconds();
    }

    public function saveFrameFromSeconds($urlSigned, $seconds = 1)
    {
        FFMpeg::openUrl($urlSigned)
            ->getFrameFromSeconds($seconds)
            ->export()
            ->toDisk('public')
            ->save('teste.jpg');

        $url_file = $this->uploadPublicFileCompress(Storage::disk('public')->get('teste.jpg'), 'teste.jpg');
        $this->removeImagePublicDisk('teste.jpg');
        $this->video->where('id', 1)->update(['thumbnail_url' => $url_file]);
    }


    /**
     * 1° - upload do video                     = OK
     * 2° - upload da thumb do video principal  = OK
     * 3° - upload do novo formato do video 
     * 4° - upload da thumb do video secundário
     */
}
