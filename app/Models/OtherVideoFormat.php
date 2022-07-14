<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherVideoFormat extends Model
{
    use HasFactory;

    protected $fillable = ['video_id','format','url','url_signed','thumbnail_url'];
}
