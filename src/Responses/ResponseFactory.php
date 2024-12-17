<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class ResponseFactory
{
    public static function create(string $type, Response $response, string $token)
    {
        return match ($type) {
            'protected_script' => new ProtectedScriptResponse($response, $token),
            'subscriber-details' => new SubscriptionDetailsResponse($response, $token),
            'confirm-login' => new ConfirmLoginResponse($response, $token),
            default => new EbtekarResponse($response, $token),
        };
    }
}
