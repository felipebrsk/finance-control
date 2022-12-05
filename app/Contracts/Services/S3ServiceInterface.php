<?php

namespace App\Contracts\Services;

interface S3ServiceInterface
{
    /**
     * Create a file in s3 server.
     *
     * @param object $file
     * @param string $folder
     * @param string $visibility
     * @return string
     */
    public function create(object $file, string $folder, string $visibility = 'public'): string;

    /**
     * Delete file from s3 server.
     *
     * @param string $path
     * @param ?string $folder
     * @param string $visibility
     * @return void
     */
    public function delete(string $path, ?string $folder = ''): void;

    /**
     * Get a file s3 path.
     * 
     * @param ?string $path
     * @return ?string
     */
    public function getPath(?string $path): ?string;
}
