<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait FileUtils
{
    public function saveUploadedFile($file, $prefix = '', $path = ''): string
    {
        $filename = sprintf('%d_%s.%s', Carbon::now()->getTimestamp(), Str::random(), $file->getClientOriginalExtension());

        if ($prefix) {
            $filename = $prefix . $filename;
        }

        return 'uploads/' . $file->storeAs($path, $filename, 'uploads');
    }
}
