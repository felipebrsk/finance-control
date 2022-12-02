<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class StatusCodeException extends Exception implements HttpExceptionInterface
{
    /**
     *  The response code.
     *
     *  @var int
     */
    protected $statusCode;

    /**
     *  The response message.
     *
     *  @var string
     */
    protected $message;

    /**
     *  The response headers.
     *
     *  @var array
     */
    protected $headers = [];

    /**
     *  Get the status code.
     *
     *  @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     *  Get the headers.
     *
     *  @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
