<?php

namespace App\Http\Traits;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

trait ConvertVideos
{
    public function convertVideo640x360($file, $folder = 'videos')
    {
        $file = FFMpeg::fromDisk('public')
            ->open('4j1rPH3ETrKz4AX9ERgcbrtUzfm8a1CyFhDI4BI4.mp4')
            ->export()
            ->toDisk('public/640x360')
            ->inFormat(new \FFMpeg\Format\video\X264)
            ->resize(640, 360)
            ->save('yesterday.mp4');
    }
}
