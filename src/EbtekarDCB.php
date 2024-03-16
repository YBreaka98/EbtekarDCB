<?php

namespace Ybreaka98\EbtekarDCB;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ybreaka98\EbtekarDCB\Interfaces\EbtekarInterface;
use Ybreaka98\EbtekarDCB\Responses\EbtekarResponse;
use Ybreaka98\EbtekarDCB\Responses\ProtectedScriptResponse;

class EbtekarDCB implements EbtekarInterface
{
    private string $ebtekarBaseUrl;

    private string $token;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->ebtekarBaseUrl = config('ebtekardcb.base_url');
        $this->token = $this->authenticate();
    }

    /**
     * @throws Exception
     */
    public function requestProtectedScript(string $targeted_element): ProtectedScriptResponse
    {
        if (! Str::startsWith($targeted_element, '#')) {
            $targeted_element = '#'.$targeted_element;
        }

        $protectResponse = Http::withToken($this->token)
            ->get($this->ebtekarBaseUrl.'protected-script', [
                'targeted_element' => $targeted_element,
                'pl' => ['ebtekar'],
            ]);

        return new ProtectedScriptResponse($protectResponse, $this->token);
    }

    /**
     * @throws Exception
     */
    public function login(string $msisdn, string $transaction_identify, string $device_type = 'android'): EbtekarResponse
    {

        $this->validateMsisdn($msisdn);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'login', [
            'msisdn' => $msisdn,
            'transaction_identify' => $transaction_identify,
            'device_type' => $device_type,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function confirmLogin(string $msisdn, string $otp, string $device_type = 'android'): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'login-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_type,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function upgrade(string $msisdn, string $uuid): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'upgrade', [
            'msisdn' => $msisdn,
            'uuid' => $uuid,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function upgradeConfirm(string $msisdn, string $otp, string $uuid, $device_token = 'android'): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'upgrade-confirm', [
            'msisdn' => $msisdn,
            'uuid' => $uuid,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function subscriptionDetails(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = Http::withToken($this->token)->get($this->ebtekarBaseUrl.'subscriber-details', [
            'msisdn' => $msisdn,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function directUnsubscribe(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'direct-unsubscribe', [
            'msisdn' => $msisdn,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function unsubscribe(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'unsubscribe', [
            'msisdn' => $msisdn,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function unsubscribeConfirm(string $msisdn, string $otp, $device_token = 'android'): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'unsubscribe-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function subscriptionActivation(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'subscription-activation', [
            'msisdn' => $msisdn,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function subscriptionActivationConfirm(string $msisdn, string $otp, $device_token = 'android'): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = Http::withToken($this->token)->post($this->ebtekarBaseUrl.'subscription-activation-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return new EbtekarResponse($response, $this->token);
    }

    public function authenticate()
    {
        $response = Http::post($this->ebtekarBaseUrl.'auth-login', [
            'email' => config('ebtekardcb.email'),
            'password' => config('ebtekardcb.password'),
        ]);

        if ($response->status() === 200) {
            return $response->json()['data']['access_token'];
        } else {
            return response()->json(['message' => 'Failed to authenticate', 'status' => 'error', 'error' => $response->body()], $response->status());
        }
    }

    /**
     * @throws Exception
     */
    private function validateMsisdn(string $msisdn): void
    {
        if (! preg_match('/^21809[1-6][0-9]{7}$/i', $msisdn)) {
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
