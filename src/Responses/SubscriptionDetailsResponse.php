<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class SubscriptionDetailsResponse extends EbtekarResponse
{
    public function __construct(Response $response, string $token)
    {
        parent::__construct($response, $token);
    }

    public function getSubscriberData()
    {
        $data = $this->getJson();
        return $data['success']['subscriber'] ?? null;
    }

    public function getSubscriberStatus()
    {
        $data = $this->getJson();
        return $data['success']['details']['status'] ?? null;
    }

    public function getExpirationDate()
    {
        $data = $this->getJson();
        return $data['success']['details']['expiration_date'] ?? null;
    }
}
