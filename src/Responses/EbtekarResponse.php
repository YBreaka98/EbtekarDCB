<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class EbtekarResponse
{
    protected int $statusCode;

    protected object $response;

    protected string $token;

    public function __construct(Response $response, string $token)
    {
        $this->statusCode = $this->getStatusCodeFromResponse();
        $this->response = $response;
        $this->token = $token;
    }

    public function getStatusCodeFromResponse(): int
    {
        return $this->response->status();
    }

    public function getResponse(): object
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isSuccessful(): bool
    {
        return $this->response->successful();
    }

    public function isClientError(): bool
    {
        return $this->response->clientError();
    }

    public function isServerError(): bool
    {
        return $this->response->serverError();
    }

    public function isFailed(): bool
    {
        return $this->response->failed();
    }

    public function getJson(): array
    {
        return $this->response->json();
    }

    public function getBody(): string
    {
        return $this->response->body();
    }
}
