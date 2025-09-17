<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

class ConfigurationException extends EbtekarException
{
    public static function missingCredentials(): self
    {
        return new self('Missing email or password configuration for Ebtekar DCB');
    }

    public static function invalidBaseUrl(string $url): self
    {
        return new self("Invalid base URL configured: {$url}");
    }

    public static function missingApiConfiguration(string $apiKey): self
    {
        return new self("Missing API configuration for: {$apiKey}");
    }
}
