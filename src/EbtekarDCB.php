<?php

namespace Ybreaka98\EbtekarDCB;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ybreaka98\EbtekarDCB\Exceptions\ApiException;
use Ybreaka98\EbtekarDCB\Exceptions\AuthenticationException;
use Ybreaka98\EbtekarDCB\Exceptions\ConfigurationException;
use Ybreaka98\EbtekarDCB\Exceptions\FraudException;
use Ybreaka98\EbtekarDCB\Exceptions\NetworkException;
use Ybreaka98\EbtekarDCB\Exceptions\OtpException;
use Ybreaka98\EbtekarDCB\Exceptions\SubscriptionException;
use Ybreaka98\EbtekarDCB\Exceptions\ValidationException;
use Ybreaka98\EbtekarDCB\Interfaces\EbtekarInterface;
use Ybreaka98\EbtekarDCB\Responses\ConfirmLoginResponse;
use Ybreaka98\EbtekarDCB\Responses\EbtekarResponse;
use Ybreaka98\EbtekarDCB\Responses\ProtectedScriptResponse;
use Ybreaka98\EbtekarDCB\Responses\ResponseFactory;
use Ybreaka98\EbtekarDCB\Responses\SubscriptionDetailsResponse;
use Ybreaka98\EbtekarDCB\Trait\Logging;
use Ybreaka98\EbtekarDCB\Trait\Validation;

class EbtekarDCB implements EbtekarInterface
{
    use Logging, Validation;

    private const DEFAULT_DEVICE_TYPE = 'android';

    private const PLATFORM = ['ebtekar'];

    private const TIMEOUT_SECONDS = 30;

    private const MAX_RETRIES = 3;

    private string $ebtekarBaseUrl;

    private string $token;

    private array $config;

    private int $timeout = self::TIMEOUT_SECONDS;

    private int $maxRetries = self::MAX_RETRIES;

    /**
     * @throws ConfigurationException
     * @throws AuthenticationException
     * @throws NetworkException
     */
    public function __construct()
    {
        $this->validateConfiguration();
        $this->loadConfiguration();
        $this->token = $this->authenticate();
    }

    /**
     * Make an HTTP request with comprehensive error handling.
     *
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    private function makeRequest(string $url, string $method, array $data = []): Response
    {
        $fullUrl = $this->ebtekarBaseUrl.$url;
        $attempt = 0;

        $this->logRequest($url, $method, $data);

        while ($attempt < $this->maxRetries) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withToken($this->token)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'EbtekarDCB-PHP/1.0',
                    ])
                    ->{$method}($fullUrl, $data);

                $this->logResponse($url, $response);
                $this->handleApiErrors($url, $response, $data);

                return $response;

            } catch (ConnectionException $e) {
                $attempt++;
                $this->logError($url, $e);

                if ($attempt >= $this->maxRetries) {
                    throw NetworkException::connectionFailed($url, $e);
                }

                // Exponential backoff
                usleep(1000000 * pow(2, $attempt - 1));

            } catch (RequestException $e) {
                $this->logError($url, $e);
                throw NetworkException::timeout($url, $e);
            }
        }

        throw NetworkException::connectionFailed($url);
    }

    /**
     * Get API configuration for a specific endpoint
     *
     * @throws ConfigurationException
     */
    private function getApiConfig(string $key): array
    {
        if (! isset($this->config['apis'][$key])) {
            throw ConfigurationException::missingApiConfiguration($key);
        }

        return $this->config['apis'][$key];
    }

    /**
     * Execute API request with proper configuration
     *
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    private function executeRequest(string $apiKey, array $data = []): Response
    {
        $config = $this->getApiConfig($apiKey);

        return $this->makeRequest($config['url'], $config['method'], $data);
    }

    /**
     * Request protected script for anti-fraud integration
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    public function requestProtectedScript(string $targeted_element): ProtectedScriptResponse
    {
        $this->validateTargetedElement($targeted_element);

        if (! Str::startsWith($targeted_element, '#')) {
            $targeted_element = '#'.$targeted_element;
        }

        $response = $this->executeRequest('protected-script', [
            'targeted_element' => $targeted_element,
            'pl' => self::PLATFORM,
        ]);

        /** @var ProtectedScriptResponse */
        return ResponseFactory::create('protected-script', $response, $this->token);
    }

    /**
     * Initiate login process for subscription
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    public function login(string $msisdn, string $transaction_identify, string $device_type = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateTransactionIdentify($transaction_identify);
        $this->validateDeviceType($device_type);

        $response = $this->executeRequest('login', [
            'msisdn' => $msisdn,
            'transaction_identify' => $transaction_identify,
            'device_type' => $device_type,
        ]);

        return ResponseFactory::create('login', $response, $this->token);
    }

    /**
     * Confirm login with OTP verification
     *
     * @throws ValidationException
     * @throws OtpException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    public function confirmLogin(string $msisdn, string $otp, string $device_type = self::DEFAULT_DEVICE_TYPE): ConfirmLoginResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $this->validateDeviceType($device_type);

        $response = $this->executeRequest('confirm-login', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_type,
        ]);

        /** @var ConfirmLoginResponse */
        return ResponseFactory::create('confirm-login', $response, $this->token);
    }

    /**
     * Initiate subscription upgrade
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    public function upgrade(string $msisdn, string $uuid): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateUuid($uuid);

        $response = $this->executeRequest('upgrade', [
            'msisdn' => $msisdn,
            'uuid' => $uuid,
        ]);

        return ResponseFactory::create('upgrade', $response, $this->token);
    }

    /**
     * Confirm subscription upgrade with OTP
     *
     * @throws ValidationException
     * @throws OtpException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws FraudException
     */
    public function upgradeConfirm(string $msisdn, string $otp, string $uuid, string $device_token = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $this->validateUuid($uuid);
        $this->validateDeviceType($device_token);

        $response = $this->executeRequest('upgrade-confirm', [
            'msisdn' => $msisdn,
            'uuid' => $uuid,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return ResponseFactory::create('upgrade-confirm', $response, $this->token);
    }

    /**
     * Get subscription details for a subscriber
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
     */
    public function subscriptionDetails(string $msisdn): SubscriptionDetailsResponse
    {
        $this->validateMsisdn($msisdn);

        $response = $this->executeRequest('subscriber-details', [
            'msisdn' => $msisdn,
        ]);

        /** @var SubscriptionDetailsResponse */
        return ResponseFactory::create('subscriber-details', $response, $this->token);
    }

    /**
     * Directly unsubscribe without OTP
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
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
     * Initiate unsubscribe process (requires OTP confirmation)
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
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
     * Confirm unsubscribe with OTP
     *
     * @throws ValidationException
     * @throws OtpException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
     */
    public function unsubscribeConfirm(string $msisdn, string $otp, string $device_token = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $this->validateDeviceType($device_token);

        $response = $this->executeRequest('unsubscribe-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return ResponseFactory::create('unsubscribe-confirm', $response, $this->token);
    }

    /**
     * Initiate subscription activation process
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
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
     * Confirm subscription activation with OTP
     *
     * @throws ValidationException
     * @throws OtpException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
     */
    public function subscriptionActivationConfirm(string $msisdn, string $otp, string $device_token = self::DEFAULT_DEVICE_TYPE): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $this->validateDeviceType($device_token);

        $response = $this->executeRequest('subscription-activation-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'device_type' => $device_token,
        ]);

        return ResponseFactory::create('subscription-activation-confirm', $response, $this->token);
    }

    /**
     * Initiate product purchase
     *
     * @throws ValidationException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
     */
    public function buyProduct(string $msisdn, string $product_id, string $invoice): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateProductId($product_id);
        $this->validateInvoice($invoice);

        $response = $this->executeRequest('buy-product', [
            'msisdn' => $msisdn,
            'product_id' => $product_id,
            'invoice' => $invoice,
        ]);

        return ResponseFactory::create('buy-product', $response, $this->token);
    }

    /**
     * Confirm product purchase with OTP
     *
     * @throws ValidationException
     * @throws OtpException
     * @throws ConfigurationException
     * @throws NetworkException
     * @throws ApiException
     * @throws SubscriptionException
     */
    public function buyProductConfirm(string $msisdn, string $otp, string $product_id, string $invoice): EbtekarResponse
    {
        $this->validateMsisdn($msisdn);
        $this->validateOtp($otp);
        $this->validateProductId($product_id);
        $this->validateInvoice($invoice);

        $response = $this->executeRequest('buy-product-confirm', [
            'msisdn' => $msisdn,
            'otp' => $otp,
            'product_id' => $product_id,
            'invoiceNo' => $invoice,
        ]);

        return ResponseFactory::create('buy-product-confirm', $response, $this->token);
    }

    /**
     * Authenticate with Ebtekar API and get access token
     *
     * @throws AuthenticationException
     * @throws NetworkException
     */
    private function authenticate(): string
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->ebtekarBaseUrl.'auth-login', [
                    'email' => $this->config['email'],
                    'password' => $this->config['password'],
                ]);

            $this->logAuthentication($response->successful());

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['access_token'])) {
                    return $data['data']['access_token'];
                }
            }

            $this->logAuthentication(false, $response->body());
            throw AuthenticationException::invalidCredentials($response);
        } catch (ConnectionException $e) {
            $this->logError('auth-login', $e);
            throw NetworkException::connectionFailed('auth-login', $e);
        } catch (RequestException $e) {
            $this->logError('auth-login', $e);
            throw NetworkException::timeout('auth-login', $e);
        }
    }

    /**
     * Validate configuration settings
     *
     * @throws ConfigurationException
     */
    private function validateConfiguration(): void
    {
        $baseUrl = config('ebtekardcb.base_url');
        $email = config('ebtekardcb.email');
        $password = config('ebtekardcb.password');

        if (empty($baseUrl) || ! filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            throw ConfigurationException::invalidBaseUrl($baseUrl ?? 'null');
        }

        if (empty($email) || empty($password)) {
            throw ConfigurationException::missingCredentials();
        }
    }

    /**
     * Load configuration from config file
     */
    private function loadConfiguration(): void
    {
        $this->config = config('ebtekardcb');
        $this->ebtekarBaseUrl = $this->config['base_url'];
    }

    /**
     * Handle API response errors and throw appropriate exceptions
     *
     * @throws FraudException
     * @throws ApiException
     * @throws SubscriptionException
     * @throws OtpException
     */
    private function handleApiErrors(string $endpoint, Response $response, array $requestData = []): void
    {
        if ($response->successful()) {
            return;
        }

        $data = null;
        try {
            $data = $response->json();
        } catch (Exception) {
            // Response is not JSON
        }

        $messageCode = $data['messageCode'] ?? null;

        // Handle fraud detection
        if ($messageCode === '300') {
            $this->logFraud($endpoint, $requestData, $response);
            throw FraudException::detected($endpoint, $response);
        }

        // Handle specific error codes based on endpoint and message
        $this->throwSpecificException($endpoint, $response, $messageCode);

        // Generic API exception for unhandled cases
        throw new ApiException(
            $data['message'] ?? 'API request failed',
            $endpoint,
            $response,
            $response->status()
        );
    }

    /**
     * Throw specific exceptions based on endpoint and error code
     *
     * @throws SubscriptionException
     * @throws OtpException
     * @throws ApiException
     */
    private function throwSpecificException(string $endpoint, Response $response, ?string $messageCode): void
    {
        $statusCode = $response->status();

        // Handle subscription-related errors
        if (str_contains($endpoint, 'subscriber') || str_contains($endpoint, 'subscription')) {
            if ($statusCode === 404) {
                $msisdn = $this->extractMsisdnFromResponse($response);
                throw SubscriptionException::notFound($msisdn, $endpoint, $response);
            }
            if ($statusCode === 410) {
                $msisdn = $this->extractMsisdnFromResponse($response);
                throw SubscriptionException::expired($msisdn, $endpoint, $response);
            }
            if ($statusCode === 402) {
                $msisdn = $this->extractMsisdnFromResponse($response);
                throw SubscriptionException::insufficientBalance($msisdn, $endpoint, $response);
            }
        }

        // Handle OTP-related errors
        if (str_contains($endpoint, 'confirm') || str_contains($endpoint, 'otp')) {
            if ($statusCode === 400) {
                throw OtpException::invalid($endpoint, $response);
            }
            if ($statusCode === 410) {
                throw OtpException::expired($endpoint, $response);
            }
            if ($statusCode === 429) {
                throw OtpException::tooManyAttempts($endpoint, $response);
            }
        }
    }

    /**
     * Extract MSISDN from response for error reporting
     */
    private function extractMsisdnFromResponse(Response $response): string
    {
        try {
            $data = $response->json();

            return $data['msisdn'] ?? 'unknown';
        } catch (Exception) {
            return 'unknown';
        }
    }

    /**
     * Set request timeout in seconds
     */
    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Set maximum number of retries for failed requests
     */
    public function setMaxRetries(int $retries): self
    {
        $this->maxRetries = $retries;

        return $this;
    }
}
