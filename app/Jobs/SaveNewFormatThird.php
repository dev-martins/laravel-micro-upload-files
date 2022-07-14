<?php

namespace App\Jobs;

use App\Models\AwsVideo;
use App\Services\VideoServiceThird;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveNewFormatThird implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $videoId, $urlSigned;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AwsVideo $video)
    {
        $this->videoId = $video->id;
        $this->urlSigned = $video->url_signed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data['video_id'] = $this->videoId;
        $data['url_signed'] = $this->urlSigned;
        new VideoServiceThird($data);
    }
}
