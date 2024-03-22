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
        return $this->getJson()['success']['subscriber'];
    }

    public function getSubscriberStatus()
    {
        return $this->getJson()['success']['details']['status'];
    }

    public function getExpirationDate()
    {
        return $this->getJson()['success']['details']['expiration_date'];
    }
}
