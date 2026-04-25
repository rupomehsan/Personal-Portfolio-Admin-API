<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| uploader
|--------------------------------------------------------------------------
*/

if (!function_exists('uploader')) {
    function uploader($source, $path, $width = null, $height = null, $file_name = null)
    {
        $mime_type   = $source->getClientMimeType();
        $ext         = strtolower($source->getClientOriginalExtension());
        $storagePath = $path ?: 'uploads';
        $dir         = public_path($storagePath);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!$file_name) {
            $file_name = Str::slug(Carbon::now()->toDateTimeString()) . rand(999, 99999) . '.' . $ext;
        } else {
            $file_name = Str::slug($file_name) . '.' . $ext;
        }

        $full_name = $storagePath . '/' . $file_name;

        // Use Intervention Image for JPEG/PNG only when a resize is requested
        $interventionTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        if (in_array($mime_type, $interventionTypes) && ($width || $height)) {
            $image = Image::make($source);
            $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save(public_path($full_name));
        } else {
            // WebP, GIF, SVG, PDF, and everything else:
            // move directly into public/ so the file is web-accessible.
            $source->move($dir, $file_name);
        }

        return $full_name;
    }
}
