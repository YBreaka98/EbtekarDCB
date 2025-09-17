@extends('ebtekardcb::layouts.app', ['isMobileWebview' => true])

@section('title', __('Verification Code - EbtekarDCB'))

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
                        رمز التحقق
                    @else
                        Verification Code
                    @endif
                </h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4">
        <div class="max-w-sm mx-auto">
            <!-- Instructions -->
            <div class="text-center mb-8 mt-8">
                <div class="w-20 h-20 bg-ebtekar-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-ebtekar-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    @if(($locale ?? 'en') === 'ar')
                        أدخل رمز التحقق
                    @else
                        Enter Verification Code
                    @endif
                </h2>

                <p class="text-gray-600 mb-2">
                    @if(($locale ?? 'en') === 'ar')
                        تم إرسال رمز التحقق المكون من 4 أرقام إلى
                    @else
                        A 4-digit verification code has been sent to
                    @endif
                </p>

                <p id="mobile-phone-display" class="font-semibold text-gray-800 text-lg">
                    {{ request('msisdn') ? formatPhoneNumber(request('msisdn')) : '' }}
                </p>
            </div>

            <!-- OTP Input Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <form id="mobile-otp-form" onsubmit="handleMobileOtpConfirm(event)">
                    <div class="space-y-6">
                        <!-- OTP Input -->
                        <div>
                            <div class="flex justify-center space-x-4 {{ ($locale ?? 'en') === 'ar' ? 'space-x-reverse' : '' }}">
                                <input type="text" id="mobile-otp-1" class="mobile-otp-input w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-ebtekar-500 focus:border-ebtekar-500 transition-colors" maxlength="1" pattern="[0-9]" required>
                                <input type="text" id="mobile-otp-2" class="mobile-otp-input w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-ebtekar-500 focus:border-ebtekar-500 transition-colors" maxlength="1" pattern="[0-9]" required>
                                <input type="text" id="mobile-otp-3" class="mobile-otp-input w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-ebtekar-500 focus:border-ebtekar-500 transition-colors" maxlength="1" pattern="[0-9]" required>
                                <input type="text" id="mobile-otp-4" class="mobile-otp-input w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-ebtekar-500 focus:border-ebtekar-500 transition-colors" maxlength="1" pattern="[0-9]" required>
                            </div>
                            <p class="text-sm text-gray-500 text-center mt-3">
                                @if(($locale ?? 'en') === 'ar')
                                    أدخل الرمز المكون من 4 أرقام
                                @else
                                    Enter the 4-digit code
                                @endif
                            </p>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="msisdn" value="{{ request('msisdn') }}">
                        <input type="hidden" name="device_type" value="app">

                        <!-- Submit Button -->
                        <button type="submit"
                                id="mobile-otp-btn"
                                class="w-full bg-ebtekar-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-ebtekar-700 focus:outline-none focus:ring-2 focus:ring-ebtekar-500 focus:ring-offset-2 transition-colors">
                            <span id="mobile-otp-text">
                                @if(($locale ?? 'en') === 'ar')
                                    تأكيد (Confirm)
                                @else
                                    Confirm
                                @endif
                            </span>
                            <div id="mobile-otp-spinner" class="hidden inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        </button>

                        <!-- Resend Button -->
                        <button type="button"
                                id="mobile-resend-btn"
                                onclick="resendMobileOtp()"
                                class="w-full border-2 border-ebtekar-600 text-ebtekar-600 py-4 px-6 rounded-lg text-lg font-semibold hover:bg-ebtekar-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-ebtekar-500 focus:ring-offset-2 transition-colors"
                                disabled>
                            <span id="mobile-resend-text">
                                @if(($locale ?? 'en') === 'ar')
                                    إعادة الإرسال
                                @else
                                    Resend Code
                                @endif
                            </span>
                            <span id="mobile-resend-timer" class="font-mono">(60)</span>
                        </button>
                    </div>
                </form>

                <!-- Error Message -->
                <div id="mobile-otp-error" class="hidden mt-4 p-4 bg-red-100 border border-red-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="mobile-otp-error-text" class="text-red-700 text-sm"></span>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="mobile-otp-success" class="hidden mt-4 p-4 bg-green-100 border border-green-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="mobile-otp-success-text" class="text-green-700 text-sm"></span>
                    </div>
                </div>
            </div>

            <!-- Help Text -->
            <div class="text-center text-sm text-gray-500 px-4">
                <p>
                    @if(($locale ?? 'en') === 'ar')
                        لم تستلم الرمز؟ تأكد من أن رقم هاتفك صحيح واضغط على إعادة الإرسال.
                    @else
                        Didn't receive the code? Make sure your phone number is correct and tap resend.
                    @endif
                </p>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    let mobileResendTimer = null;
    let mobileResendCountdown = 60;
    const currentMsisdn = '{{ request("msisdn") }}';

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        setupMobileOtpInputs();
        startMobileResendTimer();

        // Notify app that OTP page is loaded
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('pageLoaded', {
                page: 'otp',
                msisdn: currentMsisdn
            });
        }

        // Auto-focus first input
        setTimeout(() => {
            document.getElementById('mobile-otp-1').focus();
        }, 500);
    });

    // Setup OTP inputs for mobile
    function setupMobileOtpInputs() {
        document.querySelectorAll('.mobile-otp-input').forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const value = e.target.value;

                // Only allow digits
                if (value && !/[0-9]/.test(value)) {
                    e.target.value = '';
                    return;
                }

                // Move to next input
                if (value && index < 3) {
                    document.getElementById(`mobile-otp-${index + 2}`).focus();
                }

                // Auto-submit when all fields are filled
                const allInputs = document.querySelectorAll('.mobile-otp-input');
                const allFilled = Array.from(allInputs).every(input => input.value.length === 1);
                if (allFilled) {
                    setTimeout(() => {
                        document.getElementById('mobile-otp-form').dispatchEvent(new Event('submit', { cancelable: true }));
                    }, 300);
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    document.getElementById(`mobile-otp-${index}`).focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/\D/g, '');

                if (digits.length === 4) {
                    document.querySelectorAll('.mobile-otp-input').forEach((otpInput, i) => {
                        otpInput.value = digits[i] || '';
                    });

                    // Auto-submit after paste
                    setTimeout(() => {
                        document.getElementById('mobile-otp-form').dispatchEvent(new Event('submit', { cancelable: true }));
                    }, 300);
                }
            });
        });
    }

    // Handle mobile OTP confirmation
    function handleMobileOtpConfirm(event) {
        event.preventDefault();

        // Collect OTP digits
        const otp = Array.from(document.querySelectorAll('.mobile-otp-input'))
            .map(input => input.value)
            .join('');

        if (!validateOTP(otp)) {
            showMobileOtpError(window.EbtekarDCB.locale === 'ar' ? 'رمز التحقق غير صالح' : 'Invalid verification code');
            return;
        }

        // Show loading state
        setMobileOtpLoading(true);
        hideMobileOtpMessages();

        // Prepare request data
        const requestData = {
            msisdn: currentMsisdn,
            otp: otp,
            device_type: 'app'
        };

        // Send OTP confirmation request
        fetch(`${window.EbtekarDCB.apiBaseUrl}/confirm-login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.EbtekarDCB.csrfToken
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            setMobileOtpLoading(false);

            if (data.success) {
                showMobileOtpSuccess(window.EbtekarDCB.locale === 'ar' ? 'تم التحقق بنجاح' : 'Verification successful');

                // Notify mobile app of successful login
                if (window.EbtekarDCB.isMobileWebview) {
                    sendToApp('otpSuccess', {
                        msisdn: currentMsisdn,
                        subscriberStatus: data.subscriber_status,
                        message: data.message,
                        data: data.data
                    });
                }

                setTimeout(() => {
                    const locale = window.EbtekarDCB.locale;
                    window.location.href = `{{ route('ebtekardcb.mobile.profile') }}?locale=${locale}`;
                }, 1500);
            } else {
                if (data.otp_error) {
                    showMobileOtpError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'رمز التحقق غير صحيح' : 'Incorrect verification code'));

                    // Clear inputs for retry
                    document.querySelectorAll('.mobile-otp-input').forEach(input => input.value = '');
                    document.getElementById('mobile-otp-1').focus();
                } else {
                    showMobileOtpError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في التحقق' : 'Verification failed'));
                }

                if (window.EbtekarDCB.isMobileWebview) {
                    sendToApp('otpError', {
                        message: data.message,
                        otpError: data.otp_error
                    });
                }
            }
        })
        .catch(error => {
            console.error('Mobile OTP confirmation error:', error);
            setMobileOtpLoading(false);
            showMobileOtpError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');

            if (window.EbtekarDCB.isMobileWebview) {
                sendToApp('networkError', {
                    error: error.message
                });
            }
        });
    }

    // Resend OTP for mobile
    function resendMobileOtp() {
        if (!currentMsisdn) {
            showMobileOtpError(window.EbtekarDCB.locale === 'ar' ? 'رقم الهاتف مفقود' : 'Phone number missing');
            return;
        }

        hideMobileOtpMessages();

        // Get transaction identify
        const transactionIdentifyValue = localStorage.getItem('transaction_identify');

        const requestData = {
            msisdn: currentMsisdn,
            device_type: 'app',
            transaction_identify: transactionIdentifyValue
        };

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
            if (data.success) {
                showMobileOtpSuccess(window.EbtekarDCB.locale === 'ar' ? 'تم إرسال رمز جديد' : 'New code sent');
                startMobileResendTimer();

                if (window.EbtekarDCB.isMobileWebview) {
                    sendToApp('otpResent', {
                        message: data.message
                    });
                }
            } else {
                showMobileOtpError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في إعادة الإرسال' : 'Failed to resend'));
            }
        })
        .catch(error => {
            console.error('Resend OTP error:', error);
            showMobileOtpError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');
        });
    }

    // Start resend timer for mobile
    function startMobileResendTimer() {
        mobileResendCountdown = 60;
        const resendBtn = document.getElementById('mobile-resend-btn');
        const timerSpan = document.getElementById('mobile-resend-timer');

        resendBtn.disabled = true;
        resendBtn.classList.add('opacity-50');

        mobileResendTimer = setInterval(() => {
            mobileResendCountdown--;
            timerSpan.textContent = `(${mobileResendCountdown})`;

            if (mobileResendCountdown <= 0) {
                clearInterval(mobileResendTimer);
                mobileResendTimer = null;
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50');
                timerSpan.textContent = '';
            }
        }, 1000);
    }

    // Navigation
    function goBack() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('goBack');
        } else {
            window.history.back();
        }
    }

    // UI State Management
    function setMobileOtpLoading(isLoading) {
        const btn = document.getElementById('mobile-otp-btn');
        const text = document.getElementById('mobile-otp-text');
        const spinner = document.getElementById('mobile-otp-spinner');

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

    function showMobileOtpError(message) {
        const errorDiv = document.getElementById('mobile-otp-error');
        const errorText = document.getElementById('mobile-otp-error-text');
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function showMobileOtpSuccess(message) {
        const successDiv = document.getElementById('mobile-otp-success');
        const successText = document.getElementById('mobile-otp-success-text');
        successText.textContent = message;
        successDiv.classList.remove('hidden');
        successDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideMobileOtpMessages() {
        document.getElementById('mobile-otp-error').classList.add('hidden');
        document.getElementById('mobile-otp-success').classList.add('hidden');
    }

    // Handle app communication for OTP auto-fill
    window.onAppMessage = function(message) {
        try {
            const data = JSON.parse(message);
            console.log('Received message from app:', data);

            switch (data.action) {
                case 'autofillOtp':
                    if (data.otp && data.otp.length === 4) {
                        document.querySelectorAll('.mobile-otp-input').forEach((input, i) => {
                            input.value = data.otp[i] || '';
                        });
                        // Auto-submit
                        setTimeout(() => {
                            document.getElementById('mobile-otp-form').dispatchEvent(new Event('submit'));
                        }, 500);
                    }
                    break;
                case 'clearOtp':
                    document.querySelectorAll('.mobile-otp-input').forEach(input => input.value = '');
                    document.getElementById('mobile-otp-1').focus();
                    break;
            }
        } catch (error) {
            console.error('Error handling app message:', error);
        }
    };

    // Helper function to format phone number (if not available globally)
    if (typeof formatPhoneNumber === 'undefined') {
        window.formatPhoneNumber = function(phone) {
            const cleaned = phone.replace(/\D/g, '');
            if (cleaned.length === 12 && cleaned.startsWith('2189')) {
                return cleaned.replace(/(\d{4})(\d{2})(\d{3})(\d{3})/, '$1 $2 $3 $4');
            }
            return phone;
        };
    }
</script>
@endpush