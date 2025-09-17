<?php

namespace Ybreaka98\EbtekarDCB\Trait;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

trait Logging
{
    protected bool $loggingEnabled = true;

    protected string $logChannel = 'default';

    /**
     * Enable or disable logging
     */
    public function setLogging(bool $enabled): self
    {
        $this->loggingEnabled = $enabled;

        return $this;
    }

    /**
     * Set the log channel
     */
    public function setLogChannel(string $channel): self
    {
        $this->logChannel = $channel;

        return $this;
    }

    /**
     * Log API request
     */
    protected function logRequest(string $endpoint, string $method, array $data = []): void
    {
        if (! $this->loggingEnabled) {
            return;
        }

        $sanitizedData = $this->sanitizeLogData($data);

        Log::channel($this->logChannel)->info('Ebtekar DCB API Request', [
            'endpoint' => $endpoint,
            'method' => $method,
            'data' => $sanitizedData,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log API response
     */
    protected function logResponse(string $endpoint, Response $response): void
    {
        if (! $this->loggingEnabled) {
            return;
        }

        $responseData = null;
        try {
            $responseData = $response->json();
        } catch (\Exception) {
            $responseData = ['body' => $response->body()];
        }

        Log::channel($this->logChannel)->info('Ebtekar DCB API Response', [
            'endpoint' => $endpoint,
            'status_code' => $response->status(),
            'data' => $this->sanitizeLogData($responseData),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log API error
     */
    protected function logError(string $endpoint, \Exception $exception, ?Response $response = null): void
    {
        if (! $this->loggingEnabled) {
            return;
        }

        $errorData = [
            'endpoint' => $endpoint,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'timestamp' => now()->toISOString(),
        ];

        if ($response) {
            $errorData['response'] = [
                'status_code' => $response->status(),
                'body' => $response->body(),
            ];
        }

        Log::channel($this->logChannel)->error('Ebtekar DCB API Error', $errorData);
    }

    /**
     * Log authentication attempt
     */
    protected function logAuthentication(bool $successful, ?string $error = null): void
    {
        if (! $this->loggingEnabled) {
            return;
        }

        $level = $successful ? 'info' : 'warning';
        $message = $successful ? 'Authentication successful' : 'Authentication failed';

        $data = [
            'successful' => $successful,
            'timestamp' => now()->toISOString(),
        ];

        if ($error) {
            $data['error'] = $error;
        }

        Log::channel($this->logChannel)->log($level, "Ebtekar DCB {$message}", $data);
    }

    /**
     * Log fraud detection
     */
    protected function logFraud(string $endpoint, array $requestData, ?Response $response = null): void
    {
        if (! $this->loggingEnabled) {
            return;
        }

        Log::channel($this->logChannel)->warning('Ebtekar DCB Fraud Detected', [
            'endpoint' => $endpoint,
            'request_data' => $this->sanitizeLogData($requestData),
            'response' => $response ? [
                'status_code' => $response->status(),
                'message_code' => $response->json()['messageCode'] ?? null,
            ] : null,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Sanitize sensitive data from logs
     */
    protected function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = ['password', 'token', 'access_token', 'otp'];

        return $this->recursiveSanitize($data, $sensitiveKeys);
    }

    /**
     * Recursively sanitize sensitive data
     */
    private function recursiveSanitize(array $data, array $sensitiveKeys): array
    {
        foreach ($data as $key => $value) {
            if (in_array(strtolower((string) $key), $sensitiveKeys, true)) {
                $data[$key] = '***REDACTED***';
            } elseif (is_array($value)) {
                $data[$key] = $this->recursiveSanitize($value, $sensitiveKeys);
            }
        }

        return $data;
    }
}
