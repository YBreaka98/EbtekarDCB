<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

class ValidationException extends EbtekarException
{
    public static function invalidMsisdn(string $msisdn): self
    {
        return new self("Invalid MSISDN format: {$msisdn}. Must be Libyan mobile number (2189xxxxxxx)");
    }

    public static function invalidDeviceType(string $deviceType): self
    {
        return new self("Invalid device type: {$deviceType}. Allowed types: android, ios, web");
    }

    public static function emptyTransactionIdentify(): self
    {
        return new self('Transaction identify cannot be empty');
    }

    public static function transactionIdentifyTooLong(string $transactionIdentify): self
    {
        return new self("Transaction identify too long: {$transactionIdentify}. Maximum 255 characters allowed");
    }

    public static function invalidUuid(string $uuid): self
    {
        return new self("Invalid UUID format: {$uuid}");
    }

    public static function emptyProductId(): self
    {
        return new self('Product ID cannot be empty');
    }

    public static function emptyInvoice(): self
    {
        return new self('Invoice number cannot be empty');
    }

    public static function invalidTargetedElement(string $targetedElement): self
    {
        return new self("Invalid targeted element: {$targetedElement}. Must start with '#'");
    }

    public static function targetedElementTooShort(string $targetedElement): self
    {
        return new self("Targeted element too short: {$targetedElement}. Must be at least 2 characters");
    }
}
