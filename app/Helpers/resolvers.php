<?php

use App\Interfaces\Services\S3ServiceInterface;

if (!function_exists('s3Service')) {
    /**
     * Resolve the s3 service.
     * 
     * @return \App\Interfaces\Services\S3ServiceInterface
     */
    function s3Service(): S3ServiceInterface
    {
        return resolve(S3ServiceInterface::class);
    }
}
