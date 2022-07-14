<?php

namespace App\Services;

use App\Http\Traits\SetPath;
use App\Http\Traits\UploadFiles;
use App\Models\OtherVideoFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoServiceFirst
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
            ->resize(1280, 720)
            ->save('video-resize-12080x720.mp4');

        $response = $this->uploadFileConvert(Storage::disk('public')
            ->get("video-resize-12080x720.mp4"), "video-resize-12080x720-" . Str::uuid() . ".mp4");

        $this->data['url'] = $response;
        $this->data['url_signed'] = $this->getAuthorizationToDownloadFile($this->data['url']);
        $this->data['created_at'] = Carbon::now()->toDateString();
        $this->data['updated_at'] = Carbon::now()->toDateString();
        $this->data['video_id'] = $this->data['video_id'];
        $this->data['format'] = '12080x720';

        FFMpeg::openUrl($this->data['url_signed'])
            ->getFrameFromSeconds(2)
            ->export()
            ->toDisk('public')
            ->save("video-thumb-12080x720.jpg");

        $url_file = $this->uploadPublicFileCompress(Storage::disk('public')
            ->get("video-thumb-12080x720.jpg"), "video-thumb-12080x720-" . Str::uuid() . ".jpg");

        $this->removeImagePublicDisk("video-thumb-12080x720.jpg");
        $this->removeImagePublicDisk("video-resize-12080x720.mp4");

        $this->data['thumbnail_url'] = $url_file;

        $this->otherVideoFormat->create($this->data);
    }
}
