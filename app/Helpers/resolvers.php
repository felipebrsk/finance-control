<?php

use PHPOpenSourceSaver\JWTAuth\JWT;
use App\Contracts\Services\S3ServiceInterface;

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

if (!function_exists('jwt')) {
    /**
     * Resolve the jwt class.
     *
     * @return \PHPOpenSourceSaver\JWTAuth\JWT
     */
    function jwt()
    {
        return resolve(JWT::class);
    }
}
