<?php

namespace Ybreaka98\EbtekarDCB;

use Exception;
use Illuminate\Support\Facades\Http;
use Ybreaka98\EbtekarDCB\Interfaces\EbtekarPagesInterface;
use Ybreaka98\EbtekarDCB\Trait\Validation;

class EbtekarDCBPages implements EbtekarPagesInterface
{
    use Validation;

    private string $ebtekarBaseUrl;

    private string $token;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->ebtekarBaseUrl = config('ebtekardcb.base_url');
        $this->token = app(EbtekarDCB::class)->authenticate();
    }

    public function login($authUrl, $projectUrl)
    {
        return view('ebtekardcb::login', ['authUrl' => $authUrl, 'projectUrl' => $projectUrl]);
    }

    public function confirmLogin($msisdn, $url)
    {
        return view('ebtekardcb::confirm-login', ['msisdn' => $msisdn, 'url' => $url]);
    }

    public function webLogin($authUrl, $projectUrl)
    {
        return view('ebtekardcb::web-login', ['authUrl' => $authUrl, 'projectUrl' => $projectUrl]);
    }

    public function webConfirmLogin($msisdn, $url)
    {
        return view('ebtekardcb::web-confirm-login', ['msisdn' => $msisdn, 'url' => $url]);
    }

    public function subscriptionList($msisdn, $url)
    {
        try {

            $response = Http::withToken($this->token)->get($this->ebtekarBaseUrl.'subscription-list', [
                'msisdn' => $msisdn,
            ]);

            $subscriberDetails = Http::withToken($this->token)->get($this->ebtekarBaseUrl.'subscriber-details', [
                'msisdn' => $msisdn,
            ]);

            if ($response->status() >= 400) {
                return redirect()->route('error-500');
            }
            $data = $response->json();

            return view('ebtekardcb::subscription-list', ['subscriptions' => $data['success']['list'],
                'msisdn' => $data['success']['msisdn'],
                'current_subscription' => $subscriberDetails['success']['details']['subscription_name'],
                'current_subscription_status' => $subscriberDetails['success']['details']['status'],
                'url' => $url]);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function upgradeConfirm($msisdn, $url)
    {
        return view('ebtekardcb::upgrade-confirm', ['msisdn' => $msisdn, 'url' => $url]);
    }

    public function profile($msisdn, $url)
    {
        $response = Http::withToken($this->token)->get($this->ebtekarBaseUrl.'subscriber-details', [
            'msisdn' => $msisdn,
        ]);

        return view('ebtekardcb::profile', ['subscriber' => $response->json(), 'url' => $url]);
    }

    public function unSubscribeConfirm($msisdn, $url)
    {
        return view('ebtekardcb::unsubscribe-confirm', ['msisdn' => $msisdn, 'url' => $url]);
    }

    public function subscriptionActivationConfirm($msisdn, $url)
    {
        return view('ebtekardcb::subscription-activation-confirm', ['msisdn' => $msisdn, 'url' => $url]);
    }
}
