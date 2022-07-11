<?php

namespace App\Http\Traits;

use Illuminate\Support\Arr;

trait SetPath
{
    public function setPathType($request, $path_name = 'video')
    {
        Arr::set($request, 'pathType', $path_name);
    }
}
