<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class SubscriptionDetailsResponse extends EbtekarResponse
{
    public function __construct(Response $response, string $token)
    {
        parent::__construct($response, $token);
    }

    /**
     * Get complete subscriber data
     */
    public function getSubscriberData(): ?array
    {
        $data = $this->getJson();

        return $data['success']['subscriber'] ?? null;
    }

    /**
     * Get subscription status (active, canceled, etc.)
     */
    public function getSubscriberStatus(): ?string
    {
        $data = $this->getJson();

        return $data['success']['details']['status'] ?? null;
    }

    /**
     * Get subscription expiration date
     */
    public function getExpirationDate(): ?string
    {
        $data = $this->getJson();

        return $data['success']['details']['expiration_date'] ?? null;
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->getSubscriberStatus() === 'active';
    }

    /**
     * Check if subscription is canceled
     */
    public function isCanceled(): bool
    {
        return $this->getSubscriberStatus() === 'canceled';
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired(): bool
    {
        $expirationDate = $this->getExpirationDate();

        if (! $expirationDate) {
            return false;
        }

        try {
            return now()->gt(\Carbon\Carbon::parse($expirationDate));
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Get remaining days until expiration
     */
    public function getDaysUntilExpiration(): ?int
    {
        $expirationDate = $this->getExpirationDate();

        if (! $expirationDate) {
            return null;
        }

        try {
            $expiry = \Carbon\Carbon::parse($expirationDate);
            $diff = now()->diffInDays($expiry, false);

            return $diff > 0 ? (int) $diff : 0;
        } catch (\Exception) {
            return null;
        }
    }
}
