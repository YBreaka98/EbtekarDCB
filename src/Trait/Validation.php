<?php

namespace Ybreaka98\EbtekarDCB\Trait;

use Exception;

trait Validation
{
    /**
     * @throws Exception
     */
    private function validateMsisdn(string $msisdn): void
    {
        if (! preg_match('/^2189[1-6][0-9]{7}$/i', $msisdn)) {
            throw new Exception('Invalid MSISDN');
        }
    }

    /**
     * @throws Exception
     */
    private function validateOtp(string $otp): void
    {
        if (! preg_match('/^[0-9]{4}$/i', $otp)) {
            throw new Exception('Invalid OTP');
        }
    }
}
