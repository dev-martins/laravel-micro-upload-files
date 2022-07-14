<?php

namespace App\Observers;

use App\Models\AwsVideo;

class VideoObserver
{
    /**
     * Handle the AwsVideo "created" event.
     *
     * @param  \App\Models\AwsVideo  $awsVideo
     * @return void
     */
    public function created(AwsVideo $awsVideo)
    {
        //
    }

    /**
     * Handle the AwsVideo "updated" event.
     *
     * @param  \App\Models\AwsVideo  $awsVideo
     * @return void
     */
    public function updated(AwsVideo $awsVideo)
    {
        //
    }

    /**
     * Handle the AwsVideo "deleted" event.
     *
     * @param  \App\Models\AwsVideo  $awsVideo
     * @return void
     */
    public function deleted(AwsVideo $awsVideo)
    {
        //
    }

    /**
     * Handle the AwsVideo "restored" event.
     *
     * @param  \App\Models\AwsVideo  $awsVideo
     * @return void
     */
    public function restored(AwsVideo $awsVideo)
    {
        //
    }

    /**
     * Handle the AwsVideo "force deleted" event.
     *
     * @param  \App\Models\AwsVideo  $awsVideo
     * @return void
     */
    public function forceDeleted(AwsVideo $awsVideo)
    {
        //
    }
}
