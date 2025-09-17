<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

use Illuminate\Http\Client\Response;

class OtpException extends ApiException
{
    public static function invalidFormat(string $otp): self
    {
        return new self(
            "Invalid OTP format: {$otp}. OTP must be 4 digits.",
            'validation',
            null,
            400
        );
    }

    public static function expired(string $endpoint, ?Response $response = null): self
    {
        return new self(
            'OTP has expired. Please request a new one.',
            $endpoint,
            $response,
            410
        );
    }

    public static function invalid(string $endpoint, ?Response $response = null): self
    {
        return new self(
            'Invalid OTP provided.',
            $endpoint,
            $response,
            400
        );
    }

    public static function tooManyAttempts(string $endpoint, ?Response $response = null): self
    {
        return new self(
            'Too many OTP attempts. Please wait before trying again.',
            $endpoint,
            $response,
            429
        );
    }
}
