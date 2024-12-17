<?php

namespace Ybreaka98\EbtekarDCB;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ybreaka98\EbtekarDCB\Interfaces\EbtekarInterface;
use Ybreaka98\EbtekarDCB\Responses\ConfirmLoginResponse;
use Ybreaka98\EbtekarDCB\Responses\EbtekarResponse;
use Ybreaka98\EbtekarDCB\Responses\ProtectedScriptResponse;
use Ybreaka98\EbtekarDCB\Responses\ResponseFactory;
use Ybreaka98\EbtekarDCB\Responses\SubscriptionDetailsResponse;
use Ybreaka98\EbtekarDCB\Trait\Validation;

class EbtekarDCB implements EbtekarInterface
{
    use Validation;

    private const DEFAULT_DEVICE_TYPE = 'android';
    private const PLATFORM = ['ebtekar'];

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
     * Make an HTTP request with error handling.
     * @throws Exception
     */
    private function makeRequest(string $url, string $method, array $data = []): Response
    {
        try {
            return Http::withToken($this->token)->{$method}("$this->ebtekarBaseUrl$url", $data);
        } catch (ConnectionException|Exception) {
            throw new Exception('An error occurred. Please contact technical support.');
        }
    }

    private function getApiConfig(string $key): array
    {
        return config("ebtekardcb.apis.$key");
    }

    /**
     * @throws Exception
     */
    private function executeRequest(string $apiKey, array $data = []): Response
    {
        $config = $this->getApiConfig($apiKey);
        return $this->makeRequest($config['url'], $config['method'], $data);
    }

    /**
     * @throws Exception
     */
    public function requestProtectedScript(string $targeted_element): ProtectedScriptResponse
    {
        if (! Str::startsWith($targeted_element, '#')) {
            $targeted_element = '#'.$targeted_element;
        }

        $response = $this->executeRequest('protected-script', [
            'targeted_element' => $targeted_element,
            'pl' => self::PLATFORM,
        ]);

        return ResponseFactory::create('protected-script', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function login(string $msisdn, string $transaction_identify, string $device_type = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('login', [
            'msisdn' => $msisdn,
            'transaction_identify' => $transaction_identify,
            'device_type' => $device_type,
        ]);

        return ResponseFactory::create('login', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function confirmLogin(string $msisdn, string $otp, string $device_type = self::DEFAULT_DEVICE_TYPE): ConfirmLoginResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = $this->executeRequest('confirm-login', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_type,
        ]);

        return ResponseFactory::create('confirm-login', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function upgrade(string $msisdn, string $uuid): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('upgrade', [
            'msisdn' => $msisdn,
            'uuid' => $uuid,
        ]);

        return ResponseFactory::create('upgrade', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function upgradeConfirm(string $msisdn, string $otp, string $uuid, $device_token = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = $this->executeRequest('upgrade-confirm', [
            'msisdn' => $msisdn,
            'uuid' => $uuid,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return ResponseFactory::create('upgrade-confirm', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function subscriptionDetails(string $msisdn): SubscriptionDetailsResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('subscriber-details', [
            'msisdn' => $msisdn,
        ]);

        return ResponseFactory::create('subscriber-details', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function directUnsubscribe(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('direct-unsubscribe', [
            'msisdn' => $msisdn,
        ]);

        return ResponseFactory::create('direct-unsubscribe', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function unsubscribe(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('unsubscribe', [
            'msisdn' => $msisdn,
        ]);

        return ResponseFactory::create('unsubscribe', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function unsubscribeConfirm(string $msisdn, string $otp, $device_token = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = $this->executeRequest('unsubscribe-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return ResponseFactory::create('unsubscribe-confirm', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function subscriptionActivation(string $msisdn): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('subscription-activation', [
            'msisdn' => $msisdn,
        ]);

        return ResponseFactory::create('subscription-activation', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function subscriptionActivationConfirm(string $msisdn, string $otp, $device_token = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = $this->executeRequest('subscription-activation-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return ResponseFactory::create('subscription-activation-confirm', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function buyProduct(string $msisdn, string $product_id, string $invoice): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $response = $this->executeRequest('buy-product', [
            'msisdn' => $msisdn,
            'product_id' => $product_id,
            'invoice' => $invoice,
        ]);

        return ResponseFactory::create('buy-product', $response, $this->token);
    }

    /**
     * @throws Exception
     */
    public function buyProductConfirm(string $msisdn, string $otp, string $product_id, string $invoice): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $response = $this->executeRequest('buy-product-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'product_id' => $product_id,
            'invoiceNo' => $invoice,
        ]);

        return ResponseFactory::create('buy-product-confirm', $response, $this->token);
    }

    private function authenticate()
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
}
