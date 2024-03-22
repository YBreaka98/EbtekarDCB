<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class ConfirmLoginResponse extends EbtekarResponse
{
    public function __construct(Response $response, string $token)
    {
        parent::__construct($response, $token);
    }


    public function getSubscriberStatus(): string
    {
        return $this->getJson()['success']['subscriber'];
    }

    public function isSubscriberStatus($status): bool
    {
        return $this->getSubscriberStatus() === $status;
    }
}
