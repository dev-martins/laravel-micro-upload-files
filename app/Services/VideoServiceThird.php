<?php

namespace App\Services;

use App\Http\Traits\SetPath;
use App\Http\Traits\UploadFiles;
use App\Models\OtherVideoFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoServiceThird
{
    use UploadFiles, SetPath;

    protected $otherVideoFormat, $data;

    public function __construct(array $data)
    {
        $this->otherVideoFormat = app(OtherVideoFormat::class);
        $this->data = $data;
        $this->convertFormatVideoFirst();
    }

    public function convertFormatVideoFirst()
    {
        FFMpeg::openUrl($this->data['url_signed'])
            ->export()
            ->toDisk('public')
            ->inFormat(new \FFMpeg\Format\video\X264)
            ->resize(640, 360)
            ->save('video-resize-640x360.mp4');

        $response = $this->uploadFileConvert(Storage::disk('public')
            ->get("video-resize-640x360.mp4"), "video-resize-640x360-" . Str::uuid() . ".mp4");

        $this->data['url'] = $response;
        $this->data['url_signed'] = $this->getAuthorizationToDownloadFile($this->data['url']);
        $this->data['created_at'] = Carbon::now()->toDateString();
        $this->data['updated_at'] = Carbon::now()->toDateString();
        $this->data['video_id'] = $this->data['video_id'];
        $this->data['format'] = '640x360';

        FFMpeg::openUrl($this->data['url_signed'])
            ->getFrameFromSeconds(2)
            ->export()
            ->toDisk('public')
            ->save("video-thumb-640x360.jpg");

        $url_file = $this->uploadPublicFileCompress(Storage::disk('public')
            ->get("video-thumb-640x360.jpg"), "video-thumb-640x360-" . Str::uuid() . ".jpg");

        $this->removeImagePublicDisk("video-thumb-640x360.jpg");
        $this->removeImagePublicDisk("video-resize-640x360.mp4");

        $this->data['thumbnail_url'] = $url_file;

        $this->otherVideoFormat->create($this->data);
    }
}
