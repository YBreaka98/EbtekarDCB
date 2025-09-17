<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

use Illuminate\Http\Client\Response;

class SubscriptionException extends ApiException
{
    public static function notFound(string $msisdn, string $endpoint, ?Response $response = null): self
    {
        return new self(
            "Subscription not found for MSISDN: {$msisdn}",
            $endpoint,
            $response,
            404
        );
    }

    public static function alreadyActive(string $msisdn, string $endpoint, ?Response $response = null): self
    {
        return new self(
            "Subscription is already active for MSISDN: {$msisdn}",
            $endpoint,
            $response,
            409
        );
    }

    public static function alreadyCanceled(string $msisdn, string $endpoint, ?Response $response = null): self
    {
        return new self(
            "Subscription is already canceled for MSISDN: {$msisdn}",
            $endpoint,
            $response,
            409
        );
    }

    public static function expired(string $msisdn, string $endpoint, ?Response $response = null): self
    {
        return new self(
            "Subscription has expired for MSISDN: {$msisdn}",
            $endpoint,
            $response,
            410
        );
    }

    public static function insufficientBalance(string $msisdn, string $endpoint, ?Response $response = null): self
    {
        return new self(
            "Insufficient balance for MSISDN: {$msisdn}",
            $endpoint,
            $response,
            402
        );
    }
}
