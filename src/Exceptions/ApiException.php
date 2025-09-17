<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class ApiException extends EbtekarException
{
    protected ?Response $response;

    protected string $endpoint;

    public function __construct(
        string $message,
        string $endpoint,
        ?Response $response = null,
        int $code = 0,
        ?Exception $previous = null
    ) {
        $this->endpoint = $endpoint;
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function getResponseBody(): ?string
    {
        return $this->response?->body();
    }

    public function getResponseData(): ?array
    {
        if (! $this->response) {
            return null;
        }

        try {
            return $this->response->json();
        } catch (Exception) {
            return null;
        }
    }

    public function getStatusCode(): ?int
    {
        return $this->response?->status();
    }

    public function isClientError(): bool
    {
        return $this->response?->clientError() ?? false;
    }

    public function isServerError(): bool
    {
        return $this->response?->serverError() ?? false;
    }

    public function getMessageCode(): ?string
    {
        $data = $this->getResponseData();

        return $data['messageCode'] ?? null;
    }

    public function isFraudDetected(): bool
    {
        return $this->getMessageCode() === '300';
    }
}
