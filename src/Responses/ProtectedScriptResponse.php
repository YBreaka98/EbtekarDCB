<?php

namespace Ybreaka98\EbtekarDCB\Responses;

use Illuminate\Http\Client\Response;

class ProtectedScriptResponse extends EbtekarResponse
{
    public function __construct(Response $response, string $token)
    {
        parent::__construct($response, $token);
    }

    public function getTransactionIdentify(): ?string
    {
        return $this->getJson()['success']['transaction_identify'] ?? null;
    }

    public function getDcbProtect(): ?string
    {
        return $this->getJson()['success']['dcbprotect'] ?? null;
    }
}
