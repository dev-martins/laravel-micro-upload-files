<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadVideoRequest;
use App\Repositories\VideoRepository;

class UploadVideoController extends Controller
{

    protected $videoRepository;

    public function __construct()
    {
        $this->videoRepository = app(VideoRepository::class);
    }

    public function storeThumbmainVideo()
    {
    }

    public function store(UploadVideoRequest $request)
    {
        //spatie/laravel-cronless-schedule
        return $this->videoRepository->uploadVideo($request);
        // $formats = ['640x360', '960x540', '1280x720', '1920x1080'];
        #converte em um arquivo de audio
        // $file = FFMpeg::fromDisk('public')
        // ->open('4j1rPH3ETrKz4AX9ERgcbrtUzfm8a1CyFhDI4BI4.mp4')
        // ->export()
        // ->toDisk('public')
        // ->inFormat(new \FFMpeg\Format\Audio\Mp3)
        // ->save('yesterday.mp3');

        // #converte o formato do arquivo
        // $file = FFMpeg::fromDisk('public')
        // ->open('4j1rPH3ETrKz4AX9ERgcbrtUzfm8a1CyFhDI4BI4.mp4')
        // ->export()
        // ->toDisk('public')
        // ->inFormat(new \FFMpeg\Format\video\X264)
        // ->resize(640, 480)
        // ->save('yesterday.mp4');

        // #captura a thumb do video
        // $file = FFMpeg::fromDisk('public')
        // ->open('4j1rPH3ETrKz4AX9ERgcbrtUzfm8a1CyFhDI4BI4.mp4')
        // ->getFrameFromSeconds(10)
        // ->export()
        // ->toDisk('public')
        // ->save('FrameAt10sec.png');


        // $this->uploadPublicFile('teste.png');
        // return $this->videoRepository->uploadVideo($request);
    }
}
