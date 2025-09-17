<?php

namespace Ybreaka98\EbtekarDCB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ybreaka98\EbtekarDCB\Facades\EbtekarDCB;
use Ybreaka98\EbtekarDCB\Exceptions\ValidationException;
use Ybreaka98\EbtekarDCB\Exceptions\FraudException;
use Ybreaka98\EbtekarDCB\Exceptions\NetworkException;
use Ybreaka98\EbtekarDCB\Exceptions\AuthenticationException;

class DemoController extends Controller
{
    /**
     * Show the landing page
     */
    public function landing(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::pages.landing', [
            'locale' => $locale,
            'logoUrl' => config('ebtekardcb.logo_url', asset('images/service_logo.png')),
            'serviceDescription' => config('ebtekardcb.service_description'),
            'showMobileLogin' => config('ebtekardcb.show_mobile_login', true),
        ]);
    }

    /**
     * Show the profile page
     */
    public function profile(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::pages.profile', [
            'locale' => $locale,
            'logoUrl' => config('ebtekardcb.logo_url', asset('images/service_logo.png')),
        ]);
    }

    /**
     * Show the mobile login page
     */
    public function mobileLogin(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::mobile.login', [
            'locale' => $locale,
            'isMobileWebview' => true,
            'logoUrl' => config('ebtekardcb.logo_url', asset('images/service_logo.png')),
        ]);
    }

    /**
     * Show the mobile OTP page
     */
    public function mobileOtp(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $msisdn = $request->get('msisdn');
        if (!$msisdn) {
            return redirect()->route('ebtekardcb.mobile.login', ['locale' => $locale]);
        }

        return view('ebtekardcb::mobile.otp', [
            'locale' => $locale,
            'isMobileWebview' => true,
            'msisdn' => $msisdn,
        ]);
    }

    /**
     * Show the mobile profile page
     */
    public function mobileProfile(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::mobile.profile', [
            'locale' => $locale,
            'isMobileWebview' => true,
        ]);
    }

    /**
     * Show error page
     */
    public function error(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $errorType = $request->get('type', 'general');
        $errorCode = $request->get('code');
        $errorMessage = $request->get('message');

        return view('ebtekardcb::pages.error', [
            'locale' => $locale,
            'errorType' => $errorType,
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
        ]);
    }

    /**
     * API: Request protected script for anti-fraud
     */
    public function requestProtectedScript(Request $request)
    {
        try {
            $targetedElement = $request->get('targeted_element', '#cta_button');
            $response = EbtekarDCB::requestProtectedScript($targetedElement);

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'transaction_identify' => $response->getTransactionIdentify(),
                    'dcbprotect' => $response->getDcbProtect(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get protected script',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Handle login request
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'msisdn' => 'required|string|regex:/^2189[1-6][0-9]{7}$/',
                'device_type' => 'required|string|in:web,app,android,ios',
                'transaction_identify' => 'required|string',
            ]);

            $response = EbtekarDCB::login(
                $request->msisdn,
                $request->transaction_identify,
                $request->device_type
            );

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'data' => $response->getJson(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $response->getJson(),
            ], 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (FraudException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Security check failed',
                'fraud_detected' => true,
                'message_code' => $e->getMessageCode(),
            ], 403);

        } catch (NetworkException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service temporarily unavailable',
            ], 503);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }

    /**
     * API: Handle OTP confirmation
     */
    public function confirmLogin(Request $request)
    {
        try {
            $request->validate([
                'msisdn' => 'required|string|regex:/^2189[1-6][0-9]{7}$/',
                'otp' => 'required|string|regex:/^[0-9]{4}$/',
                'device_type' => 'required|string|in:web,app,android,ios',
            ]);

            $response = EbtekarDCB::confirmLogin(
                $request->msisdn,
                $request->otp,
                $request->device_type
            );

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login confirmed successfully',
                    'subscriber_status' => $response->getSubscriberStatus(),
                    'data' => $response->getJson(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'OTP confirmation failed',
                'data' => $response->getJson(),
            ], 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'otp_error' => true,
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Confirmation failed',
            ], 400);
        }
    }

    /**
     * API: Get subscription details
     */
    public function subscriptionDetails(Request $request)
    {
        try {
            $request->validate([
                'msisdn' => 'required|string|regex:/^2189[1-6][0-9]{7}$/',
            ]);

            $response = EbtekarDCB::subscriptionDetails($request->msisdn);

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'subscription' => [
                        'msisdn' => $request->msisdn,
                        'status' => $response->getSubscriberStatus(),
                        'expiration_date' => $response->getExpirationDate(),
                        'is_active' => $response->isActive(),
                        'is_expired' => $response->isExpired(),
                        'is_canceled' => $response->isCanceled(),
                        'days_remaining' => $response->getDaysUntilExpiration(),
                        'data' => $response->getSubscriberData(),
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subscription details',
            ], 404);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscription details',
            ], 500);
        }
    }

    /**
     * API: Activate subscription
     */
    public function subscriptionActivation(Request $request)
    {
        try {
            $request->validate([
                'msisdn' => 'required|string|regex:/^2189[1-6][0-9]{7}$/',
            ]);

            $response = EbtekarDCB::subscriptionActivation($request->msisdn);

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Activation OTP sent successfully',
                    'data' => $response->getJson(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate subscription activation',
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * API: Unsubscribe
     */
    public function unsubscribe(Request $request)
    {
        try {
            $request->validate([
                'msisdn' => 'required|string|regex:/^2189[1-6][0-9]{7}$/',
            ]);

            $response = EbtekarDCB::unsubscribe($request->msisdn);

            if ($response->isSuccessful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unsubscribe OTP sent successfully',
                    'data' => $response->getJson(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate unsubscription',
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show terms and conditions
     */
    public function terms(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::pages.terms', [
            'locale' => $locale,
        ]);
    }

    /**
     * Show privacy policy
     */
    public function privacy(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::pages.privacy', [
            'locale' => $locale,
        ]);
    }

    /**
     * Show contact page
     */
    public function contact(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        return view('ebtekardcb::pages.contact', [
            'locale' => $locale,
        ]);
    }
}