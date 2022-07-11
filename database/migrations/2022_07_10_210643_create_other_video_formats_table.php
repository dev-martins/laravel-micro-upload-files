<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_video_formats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('aws_videos')->onDelete('cascade');
            $table->string('format', 10);
            $table->string('url');
            $table->text('url_signed');
            $table->string('thumbnail_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_video_formats');
    }
};
