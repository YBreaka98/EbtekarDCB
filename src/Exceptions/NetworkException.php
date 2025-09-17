<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

use Exception;

class NetworkException extends EbtekarException
{
    protected string $endpoint;

    public function __construct(
        string $message,
        string $endpoint,
        int $code = 0,
        ?Exception $previous = null
    ) {
        $this->endpoint = $endpoint;

        parent::__construct($message, $code, $previous);
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public static function connectionFailed(string $endpoint, ?Exception $previous = null): self
    {
        return new self(
            "Failed to connect to endpoint: {$endpoint}",
            $endpoint,
            0,
            $previous
        );
    }

    public static function timeout(string $endpoint, ?Exception $previous = null): self
    {
        return new self(
            "Request timeout for endpoint: {$endpoint}",
            $endpoint,
            0,
            $previous
        );
    }
}
