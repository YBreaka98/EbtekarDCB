<?php

namespace Ybreaka98\EbtekarDCB\Responses;
use Illuminate\Http\Client\Response;

class ProtectedScriptResponse extends EbtekarResponse
{

    public function __construct(Response $response, string $token)
    {
        parent::__construct($response, $token);
    }

    public function getTransactionIdentify()
    {
        return $this->getJson()['protect_data']['success']['transaction_identify'];
    }

    public function getDcbProtect()
    {
        return $this->getJson()['protect_data']['success']['dcbprotect'];
    }




}
