<?php

namespace Ybreaka98\EbtekarDCB\Trait;

use Ybreaka98\EbtekarDCB\Exceptions\OtpException;
use Ybreaka98\EbtekarDCB\Exceptions\ValidationException;

trait Validation
{
    /**
     * Validate Libyan mobile number format (Libyana/Almadar)
     *
     * @throws ValidationException
     */
    private function validateMsisdn(string $msisdn): void
    {
        // Remove any whitespace or special characters
        $cleanMsisdn = preg_replace('/[^0-9]/', '', $msisdn) ?? '';

        // Check if it matches Libyan mobile format
        if (! preg_match('/^2189[1-6][0-9]{7}$/', $cleanMsisdn)) {
            throw ValidationException::invalidMsisdn($msisdn);
        }
    }

    /**
     * Validate OTP format (4 digits)
     *
     * @throws OtpException
     */
    private function validateOtp(string $otp): void
    {
        if (! preg_match('/^[0-9]{4}$/', $otp)) {
            throw OtpException::invalidFormat($otp);
        }
    }

    /**
     * Validate device type
     *
     * @throws ValidationException
     */
    private function validateDeviceType(string $deviceType): void
    {
        $allowedTypes = ['android', 'ios', 'web'];

        if (! in_array(strtolower($deviceType), $allowedTypes, true)) {
            throw ValidationException::invalidDeviceType($deviceType);
        }
    }

    /**
     * Validate transaction identify format
     *
     * @throws ValidationException
     */
    private function validateTransactionIdentify(string $transactionIdentify): void
    {
        if (empty(trim($transactionIdentify))) {
            throw ValidationException::emptyTransactionIdentify();
        }

        if (strlen($transactionIdentify) > 255) {
            throw ValidationException::transactionIdentifyTooLong($transactionIdentify);
        }
    }

    /**
     * Validate UUID format
     *
     * @throws ValidationException
     */
    private function validateUuid(string $uuid): void
    {
        if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
            throw ValidationException::invalidUuid($uuid);
        }
    }

    /**
     * Validate product ID
     *
     * @throws ValidationException
     */
    private function validateProductId(string $productId): void
    {
        if (empty(trim($productId))) {
            throw ValidationException::emptyProductId();
        }
    }

    /**
     * Validate invoice number
     *
     * @throws ValidationException
     */
    private function validateInvoice(string $invoice): void
    {
        if (empty(trim($invoice))) {
            throw ValidationException::emptyInvoice();
        }
    }

    /**
     * Validate targeted element format for compliance protect
     *
     * @throws ValidationException
     */
    private function validateTargetedElement(string $targetedElement): void
    {
        if (! str_starts_with($targetedElement, '#')) {
            throw ValidationException::invalidTargetedElement($targetedElement);
        }

        if (strlen($targetedElement) < 2) {
            throw ValidationException::targetedElementTooShort($targetedElement);
        }
    }
}
