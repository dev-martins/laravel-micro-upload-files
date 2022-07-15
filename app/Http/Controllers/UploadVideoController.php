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

    public function store(UploadVideoRequest $request)
    {
        return $this->videoRepository->uploadVideo($request);
    }

    public function update(UploadVideoRequest $request)
    {
        return $this->videoRepository->uploadVideo($request);
    }
}
