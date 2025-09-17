<?php

namespace Ybreaka98\EbtekarDCB\Interfaces;

use Ybreaka98\EbtekarDCB\Responses\ConfirmLoginResponse;
use Ybreaka98\EbtekarDCB\Responses\EbtekarResponse;
use Ybreaka98\EbtekarDCB\Responses\ProtectedScriptResponse;
use Ybreaka98\EbtekarDCB\Responses\SubscriptionDetailsResponse;

interface EbtekarInterface
{
    public function requestProtectedScript(string $targeted_element): ProtectedScriptResponse;

    public function login(string $msisdn, string $transaction_identify, string $device_type = 'android'): EbtekarResponse;

    public function confirmLogin(string $msisdn, string $otp, string $device_type = 'android'): ConfirmLoginResponse;

    public function upgrade(string $msisdn, string $uuid): EbtekarResponse;

    public function upgradeConfirm(string $msisdn, string $otp, string $uuid, string $device_token = 'android'): EbtekarResponse;

    public function subscriptionDetails(string $msisdn): SubscriptionDetailsResponse;

    public function directUnsubscribe(string $msisdn): EbtekarResponse;

    public function unsubscribe(string $msisdn): EbtekarResponse;

    public function unsubscribeConfirm(string $msisdn, string $otp, string $device_token = 'android'): EbtekarResponse;

    public function subscriptionActivation(string $msisdn): EbtekarResponse;

    public function subscriptionActivationConfirm(string $msisdn, string $otp, string $device_token = 'android'): EbtekarResponse;

    public function buyProduct(string $msisdn, string $product_id, string $invoice): EbtekarResponse;

    public function buyProductConfirm(string $msisdn, string $otp, string $product_id, string $invoice): EbtekarResponse;
}
