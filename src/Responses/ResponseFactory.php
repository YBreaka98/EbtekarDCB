<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class ResponseFactory
{
    /**
     * @return ($type is 'protected-script' ? ProtectedScriptResponse : ($type is 'subscriber-details' ? SubscriptionDetailsResponse : ($type is 'confirm-login' ? ConfirmLoginResponse : EbtekarResponse)))
     */
    public static function create(string $type, Response $response, string $token): EbtekarResponse|ProtectedScriptResponse|ConfirmLoginResponse|SubscriptionDetailsResponse
    {
        return match ($type) {
            'protected-script' => new ProtectedScriptResponse($response, $token),
            'subscriber-details' => new SubscriptionDetailsResponse($response, $token),
            'confirm-login' => new ConfirmLoginResponse($response, $token),
            default => new EbtekarResponse($response, $token),
        };
    }
}
