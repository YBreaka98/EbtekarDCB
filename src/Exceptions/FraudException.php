<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

use Illuminate\Http\Client\Response;

class FraudException extends ApiException
{
    public function __construct(
        string $endpoint,
        ?Response $response = null,
        string $message = 'Fraud detected by anti-fraud system'
    ) {
        parent::__construct($message, $endpoint, $response, 300);
    }

    public static function detected(string $endpoint, ?Response $response = null): self
    {
        return new self($endpoint, $response);
    }
}
