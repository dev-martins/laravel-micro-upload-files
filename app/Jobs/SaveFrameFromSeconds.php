<?php

namespace App\Jobs;

use App\Http\Traits\SetPath;
use App\Http\Traits\UploadFiles;
use App\Models\AwsVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Str;


class SaveFrameFromSeconds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UploadFiles, SetPath;

    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AwsVideo $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        FFMpeg::openUrl($this->video->url_signed)
            ->getFrameFromSeconds(2)
            ->export()
            ->toDisk('public')
            ->save("video-thumb-1920x1080.jpg");
        
        $url_file = $this->uploadPublicFileCompress(Storage::disk('public')
            ->get("video-thumb-1920x1080.jpg"), "video-thumb-1920x1080-" . Str::uuid() . ".jpg");
        $this->removeImagePublicDisk("video-thumb-1920x1080.jpg");
        $this->video->where('id', $this->video->id)->update(['thumbnail_url' => $url_file]);
    }
}
