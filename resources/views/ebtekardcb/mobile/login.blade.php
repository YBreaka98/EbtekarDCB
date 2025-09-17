@extends('ebtekardcb::layouts.app', ['isMobileWebview' => true])

@section('title', __('Login - EbtekarDCB'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="px-4 py-4">
            <div class="flex items-center">
                <button onclick="goBack()" class="{{ ($locale ?? 'en') === 'ar' ? 'ml-3' : 'mr-3' }} p-2 -m-2 text-gray-600 hover:text-gray-800">
                    @if(($locale ?? 'en') === 'ar')
                        <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    @endif
                </button>
                <h1 class="text-xl font-semibold text-gray-800">
                    @if(($locale ?? 'en') === 'ar')
                        تسجيل الدخول
                    @else
                        Login
                    @endif
                </h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4">
        <div class="max-w-sm mx-auto">
            <!-- Service Logo -->
            <div class="text-center mb-8 mt-8">
                <img src="{{ $logoUrl ?? asset('images/service_logo.png') }}"
                     alt="{{ __('Service Logo') }}"
                     class="cptpl_logo h-20 w-auto mx-auto mb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    @if(($locale ?? 'en') === 'ar')
                        إبتكار
                    @else
                        Ebtekar
                    @endif
                </h2>
                <p class="text-gray-600 mt-2">
                    @if(($locale ?? 'en') === 'ar')
                        خدمة المحتوى المتميز
                    @else
                        Premium Content Service
                    @endif
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <form id="mobile-login-form" onsubmit="handleMobileLogin(event)">
                    <div class="space-y-6">
                        <!-- Phone Number Input -->
                        <div>
                            <label for="mobile-msisdn" class="block text-sm font-medium text-gray-700 mb-2">
                                @if(($locale ?? 'en') === 'ar')
                                    رقم الهاتف
                                @else
                                    Phone Number
                                @endif
                            </label>
                            <div class="relative">
                                <input type="tel"
                                       id="mobile-msisdn"
                                       name="msisdn"
                                       class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebtekar-500 focus:border-ebtekar-500 {{ ($locale ?? 'en') === 'ar' ? 'text-right' : 'text-left' }}"
                                       placeholder="{{ ($locale ?? 'en') === 'ar' ? 'أدخل رقم هاتفك' : 'Enter phone number' }}"
                                       required
                                       maxlength="12"
                                       pattern="2189[1-6][0-9]{7}"
                                       autocomplete="tel">
                                <div class="absolute inset-y-0 {{ ($locale ?? 'en') === 'ar' ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 {{ ($locale ?? 'en') === 'ar' ? 'text-right' : 'text-left' }}">
                                @if(($locale ?? 'en') === 'ar')
                                    مثال: 218912345678
                                @else
                                    Example: 218912345678
                                @endif
                            </p>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="device_type" value="app">

                        <!-- Submit Button -->
                        <button type="submit"
                                id="mobile-login-btn"
                                class="w-full bg-ebtekar-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-ebtekar-700 focus:outline-none focus:ring-2 focus:ring-ebtekar-500 focus:ring-offset-2 transition-colors">
                            <span id="mobile-login-text">
                                @if(($locale ?? 'en') === 'ar')
                                    تسجيل الدخول
                                @else
                                    Sign Up
                                @endif
                            </span>
                            <div id="mobile-login-spinner" class="hidden inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>
                </form>

                <!-- Error Message -->
                <div id="mobile-error" class="hidden mt-4 p-4 bg-red-100 border border-red-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="mobile-error-text" class="text-red-700 text-sm"></span>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="mobile-success" class="hidden mt-4 p-4 bg-green-100 border border-green-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="mobile-success-text" class="text-green-700 text-sm"></span>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="text-center text-xs text-gray-500 px-4">
                <p class="cptpl_terms">
                    @if(($locale ?? 'en') === 'ar')
                        بالمتابعة، فإنك توافق على
                    @else
                        By continuing, you agree to our
                    @endif
                    <a href="#" onclick="openTerms()" class="text-ebtekar-600 underline">
                        @if(($locale ?? 'en') === 'ar')
                            الشروط والأحكام
                        @else
                            Terms & Conditions
                        @endif
                    </a>
                    @if(($locale ?? 'en') === 'ar')
                        و
                    @else
                        and
                    @endif
                    <a href="#" onclick="openPrivacy()" class="text-ebtekar-600 underline">
                        @if(($locale ?? 'en') === 'ar')
                            سياسة الخصوصية
                        @else
                            Privacy Policy
                        @endif
                    </a>
                </p>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    let mobileTransactionIdentify = null;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        initializeMobileAntiFraud();
        setupMobilePhoneInput();

        // Notify app that page is loaded
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('pageLoaded', { page: 'login' });
        }
    });

    // Initialize anti-fraud protection for mobile
    function initializeMobileAntiFraud() {
        // Request protected script for mobile
        fetch(`${window.EbtekarDCB.apiBaseUrl}/request-protected-script`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.EbtekarDCB.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mobileTransactionIdentify = data.transaction_identify;
                localStorage.setItem('transaction_identify', mobileTransactionIdentify);

                // For mobile, we might not inject the script directly
                // Instead, we'll just store the transaction_identify
                console.log('Mobile anti-fraud initialized');
            } else {
                console.error('Failed to initialize mobile anti-fraud:', data.message);
            }
        })
        .catch(error => {
            console.error('Mobile anti-fraud error:', error);
        });
    }

    // Setup mobile phone input
    function setupMobilePhoneInput() {
        const phoneInput = document.getElementById('mobile-msisdn');

        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            e.target.value = value;

            // Visual feedback
            const isValid = validateLibyanPhone(value);
            if (value.length > 0) {
                if (isValid) {
                    e.target.classList.remove('border-red-300');
                    e.target.classList.add('border-green-300');
                } else {
                    e.target.classList.remove('border-green-300');
                    e.target.classList.add('border-red-300');
                }
            } else {
                e.target.classList.remove('border-red-300', 'border-green-300');
            }
        });

        // Focus the input automatically
        setTimeout(() => {
            phoneInput.focus();
        }, 500);
    }

    // Handle mobile login
    function handleMobileLogin(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const msisdn = formData.get('msisdn');

        // Validate phone number
        if (!validateLibyanPhone(msisdn)) {
            showMobileError(window.EbtekarDCB.locale === 'ar' ? 'رقم الهاتف غير صالح' : 'Invalid phone number');
            return;
        }

        // Show loading state
        setMobileLoading(true);
        hideMobileMessages();

        // Get transaction identify
        const transactionIdentifyValue = localStorage.getItem('transaction_identify');
        if (!transactionIdentifyValue) {
            showMobileError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في نظام الحماية' : 'Protection system error');
            setMobileLoading(false);
            return;
        }

        // Prepare request data
        const requestData = {
            msisdn: msisdn,
            device_type: 'app',
            transaction_identify: transactionIdentifyValue
        };

        // Send login request
        fetch(`${window.EbtekarDCB.apiBaseUrl}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.EbtekarDCB.csrfToken
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            setMobileLoading(false);

            if (data.success) {
                showMobileSuccess(window.EbtekarDCB.locale === 'ar' ? 'تم إرسال رمز التحقق' : 'Verification code sent');

                // Notify mobile app and navigate to OTP page
                if (window.EbtekarDCB.isMobileWebview) {
                    sendToApp('loginSuccess', {
                        msisdn: msisdn,
                        message: data.message
                    });
                }

                setTimeout(() => {
                    const locale = window.EbtekarDCB.locale;
                    window.location.href = `{{ route('ebtekardcb.mobile.otp') }}?msisdn=${encodeURIComponent(msisdn)}&locale=${locale}`;
                }, 1000);
            } else {
                // Handle errors
                if (data.fraud_detected) {
                    showMobileError(window.EbtekarDCB.locale === 'ar' ? 'تم اكتشاف نشاط مشبوه' : 'Suspicious activity detected');

                    if (window.EbtekarDCB.isMobileWebview) {
                        sendToApp('fraudDetected', {
                            message: data.message
                        });
                    }
                } else {
                    showMobileError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في إرسال رمز التحقق' : 'Failed to send verification code'));

                    if (window.EbtekarDCB.isMobileWebview) {
                        sendToApp('loginError', {
                            message: data.message
                        });
                    }
                }
            }
        })
        .catch(error => {
            console.error('Mobile login error:', error);
            setMobileLoading(false);
            showMobileError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');

            if (window.EbtekarDCB.isMobileWebview) {
                sendToApp('networkError', {
                    error: error.message
                });
            }
        });
    }

    // Navigation functions
    function goBack() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('goBack');
        } else {
            window.history.back();
        }
    }

    function openTerms() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('openTerms');
        } else {
            window.open('{{ route("ebtekardcb.terms") }}', '_blank');
        }
    }

    function openPrivacy() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('openPrivacy');
        } else {
            window.open('{{ route("ebtekardcb.privacy") }}', '_blank');
        }
    }

    // UI State Management
    function setMobileLoading(isLoading) {
        const btn = document.getElementById('mobile-login-btn');
        const text = document.getElementById('mobile-login-text');
        const spinner = document.getElementById('mobile-login-spinner');

        btn.disabled = isLoading;
        if (isLoading) {
            text.classList.add('hidden');
            spinner.classList.remove('hidden');
            btn.classList.add('opacity-75');
        } else {
            text.classList.remove('hidden');
            spinner.classList.add('hidden');
            btn.classList.remove('opacity-75');
        }
    }

    function showMobileError(message) {
        const errorDiv = document.getElementById('mobile-error');
        const errorText = document.getElementById('mobile-error-text');
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');

        // Scroll to error message
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function showMobileSuccess(message) {
        const successDiv = document.getElementById('mobile-success');
        const successText = document.getElementById('mobile-success-text');
        successText.textContent = message;
        successDiv.classList.remove('hidden');

        // Scroll to success message
        successDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideMobileMessages() {
        document.getElementById('mobile-error').classList.add('hidden');
        document.getElementById('mobile-success').classList.add('hidden');
    }

    // Handle app communication (if methods are provided by the app)
    window.onAppMessage = function(message) {
        try {
            const data = JSON.parse(message);
            console.log('Received message from app:', data);

            switch (data.action) {
                case 'prefillPhone':
                    if (data.phone) {
                        document.getElementById('mobile-msisdn').value = data.phone;
                    }
                    break;
                case 'autoSubmit':
                    document.getElementById('mobile-login-form').dispatchEvent(new Event('submit'));
                    break;
            }
        } catch (error) {
            console.error('Error handling app message:', error);
        }
    };
</script>
@endpush