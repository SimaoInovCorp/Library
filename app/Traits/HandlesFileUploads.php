<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait HandlesFileUploads
{
    /**
     * Handle file upload and return the stored path or null.
     *
     * @param UploadedFile|null $file
     * @param string $folder
     * @return string|null
     */
    public function uploadFile(?UploadedFile $file, string $folder): ?string
    {
        return $file ? $file->store($folder, 'public') : null;
    }
}
