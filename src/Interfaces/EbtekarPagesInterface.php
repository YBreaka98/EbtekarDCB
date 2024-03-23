<?php

namespace Ybreaka98\EbtekarDCB\Interfaces;

interface EbtekarPagesInterface
{
    public function login($authUrl, $projectUrl);

    public function confirmLogin($msisdn, $url);

    public function webLogin($authUrl, $projectUrl);

    public function webConfirmLogin($msisdn, $url);

    public function subscriptionList($msisdn, $url);

    public function upgradeConfirm($msisdn, $url);

    public function profile($msisdn, $url);

    public function unSubscribeConfirm($msisdn, $url);

    public function subscriptionActivationConfirm($msisdn, $url);
}
