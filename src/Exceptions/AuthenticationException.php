<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

class AuthenticationException extends EbtekarException
{
    public static function invalidCredentials(?\Illuminate\Http\Client\Response $response = null): self
    {
        $message = 'Authentication failed: Invalid email or password';

        if ($response) {
            try {
                $data = $response->json();
                $message = $data['message'] ?? $message;
            } catch (\Exception) {
                // Response is not JSON, use default message
            }
        }

        return new self($message);
    }

    public static function tokenExpired(): self
    {
        return new self('Authentication token has expired. Please re-authenticate.');
    }

    public static function missingToken(): self
    {
        return new self('Authentication token is missing.');
    }
}
