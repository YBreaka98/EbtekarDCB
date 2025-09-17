<!-- Login Modal -->
<div id="login-modal" class="ebtekar-modal" style="display: none;">
    <div class="ebtekar-modal-backdrop" onclick="closeLoginModal()"></div>
    <div class="ebtekar-modal-content animate-slide-up">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">
                @if(($locale ?? 'en') === 'ar')
                    تسجيل الدخول
                @else
                    Login
                @endif
            </h3>
            <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Login Form -->
            <form id="login-form" onsubmit="handleLogin(event)">
                <div class="space-y-6">
                    <!-- Phone Number Input -->
                    <div>
                        <label for="msisdn" class="block text-sm font-medium text-gray-700 mb-2">
                            @if(($locale ?? 'en') === 'ar')
                                رقم الهاتف
                            @else
                                Phone Number
                            @endif
                        </label>
                        <div class="relative">
                            <input type="tel"
                                   id="msisdn"
                                   name="msisdn"
                                   class="ebtekar-input {{ ($locale ?? 'en') === 'ar' ? 'text-right' : 'text-left' }}"
                                   placeholder="{{ ($locale ?? 'en') === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone number' }}"
                                   required
                                   maxlength="12"
                                   pattern="2189[1-6][0-9]{7}"
                                   title="{{ ($locale ?? 'en') === 'ar' ? 'يجب أن يكون الرقم ليبي صالح (2189XXXXXXXX)' : 'Must be a valid Libyan number (2189XXXXXXXX)' }}">
                            <div class="absolute inset-y-0 {{ ($locale ?? 'en') === 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(($locale ?? 'en') === 'ar')
                                مثال: 218912345678
                            @else
                                Example: 218912345678
                            @endif
                        </p>
                    </div>

                    <!-- Device Type (hidden) -->
                    <input type="hidden" id="device_type" name="device_type" value="web">

                    <!-- Submit Button -->
                    <button type="submit"
                            id="login-submit-btn"
                            class="ebtekar-btn ebtekar-btn-primary w-full">
                        <span id="login-btn-text">
                            @if(($locale ?? 'en') === 'ar')
                                إرسال رمز التحقق
                            @else
                                Send Verification Code
                            @endif
                        </span>
                        <div id="login-btn-spinner" class="ebtekar-spinner hidden"></div>
                    </button>
                </div>
            </form>

            <!-- Error Message -->
            <div id="login-error" class="hidden mt-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="login-error-text" class="text-red-700 text-sm"></span>
                </div>
            </div>

            <!-- Success Message -->
            <div id="login-success" class="hidden mt-4 p-3 bg-green-100 border border-green-300 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="login-success-text" class="text-green-700 text-sm"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OTP Modal -->
<div id="otp-modal" class="ebtekar-modal" style="display: none;">
    <div class="ebtekar-modal-backdrop" onclick="closeOtpModal()"></div>
    <div class="ebtekar-modal-content animate-slide-up">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">
                @if(($locale ?? 'en') === 'ar')
                    أدخل رمز التحقق
                @else
                    Enter Verification Code
                @endif
            </h3>
            <button onclick="closeOtpModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-gray-600">
                    @if(($locale ?? 'en') === 'ar')
                        تم إرسال رمز التحقق المكون من 4 أرقام إلى
                    @else
                        A 4-digit verification code has been sent to
                    @endif
                </p>
                <p id="otp-phone-display" class="font-semibold text-gray-800 mt-1"></p>
            </div>

            <!-- OTP Form -->
            <form id="otp-form" onsubmit="handleOtpConfirm(event)">
                <div class="space-y-6">
                    <!-- OTP Input -->
                    <div>
                        <div class="flex justify-center space-x-3 {{ ($locale ?? 'en') === 'ar' ? 'space-x-reverse' : '' }}">
                            <input type="text" id="otp-1" class="otp-input" maxlength="1" pattern="[0-9]" required>
                            <input type="text" id="otp-2" class="otp-input" maxlength="1" pattern="[0-9]" required>
                            <input type="text" id="otp-3" class="otp-input" maxlength="1" pattern="[0-9]" required>
                            <input type="text" id="otp-4" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        </div>
                        <p class="text-xs text-gray-500 text-center mt-2">
                            @if(($locale ?? 'en') === 'ar')
                                أدخل الرمز المكون من 4 أرقام
                            @else
                                Enter the 4-digit code
                            @endif
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            id="otp-submit-btn"
                            class="ebtekar-btn ebtekar-btn-primary w-full">
                        <span id="otp-btn-text">
                            @if(($locale ?? 'en') === 'ar')
                                تأكيد
                            @else
                                Confirm
                            @endif
                        </span>
                        <div id="otp-btn-spinner" class="ebtekar-spinner hidden"></div>
                    </button>

                    <!-- Resend Button -->
                    <button type="button"
                            id="resend-otp-btn"
                            onclick="resendOtp()"
                            class="ebtekar-btn ebtekar-btn-outline w-full"
                            disabled>
                        <span id="resend-text">
                            @if(($locale ?? 'en') === 'ar')
                                إعادة الإرسال
                            @else
                                Resend Code
                            @endif
                        </span>
                        <span id="resend-timer" class="font-mono">(60)</span>
                    </button>
                </div>
            </form>

            <!-- Error Message -->
            <div id="otp-error" class="hidden mt-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="otp-error-text" class="text-red-700 text-sm"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPhoneNumber = '';
    let resendTimer = null;
    let resendCountdown = 60;

    // Phone number input formatting and validation
    document.getElementById('msisdn').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        // Limit to 12 digits (Libyan format)
        if (value.length > 12) {
            value = value.substring(0, 12);
        }

        e.target.value = value;

        // Real-time validation feedback
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

    // OTP input handling
    document.querySelectorAll('.otp-input').forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;

            if (value && !/[0-9]/.test(value)) {
                e.target.value = '';
                return;
            }

            if (value && index < 3) {
                document.getElementById(`otp-${index + 2}`).focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                document.getElementById(`otp-${index}`).focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/\D/g, '');

            if (digits.length === 4) {
                document.querySelectorAll('.otp-input').forEach((otpInput, i) => {
                    otpInput.value = digits[i] || '';
                });
            }
        });
    });

    // Handle login form submission
    function handleLogin(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const msisdn = formData.get('msisdn');

        // Validate phone number
        if (!validateLibyanPhone(msisdn)) {
            showLoginError(window.EbtekarDCB.locale === 'ar' ? 'رقم الهاتف غير صالح' : 'Invalid phone number');
            return;
        }

        // Store phone number for OTP verification
        currentPhoneNumber = msisdn;

        // Show loading state
        setLoginLoading(true);
        hideLoginMessages();

        // Add transaction identify from localStorage
        const transactionIdentifyValue = localStorage.getItem('transaction_identify');
        if (!transactionIdentifyValue) {
            showLoginError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في نظام الحماية من الاحتيال' : 'Fraud protection error');
            setLoginLoading(false);
            return;
        }

        // Prepare request data
        const requestData = {
            msisdn: msisdn,
            device_type: formData.get('device_type'),
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
            setLoginLoading(false);

            if (data.success) {
                showLoginSuccess(window.EbtekarDCB.locale === 'ar' ? 'تم إرسال رمز التحقق' : 'Verification code sent');
                setTimeout(() => {
                    closeLoginModal();
                    openOtpModal();
                }, 1000);
            } else {
                // Check for fraud detection
                if (data.fraud_detected) {
                    showLoginError(window.EbtekarDCB.locale === 'ar' ? 'تم اكتشاف نشاط مشبوه' : 'Suspicious activity detected');
                } else {
                    showLoginError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في إرسال رمز التحقق' : 'Failed to send verification code'));
                }
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            setLoginLoading(false);
            showLoginError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');
        });
    }

    // Handle OTP confirmation
    function handleOtpConfirm(event) {
        event.preventDefault();

        // Collect OTP digits
        const otp = Array.from(document.querySelectorAll('.otp-input'))
            .map(input => input.value)
            .join('');

        if (!validateOTP(otp)) {
            showOtpError(window.EbtekarDCB.locale === 'ar' ? 'رمز التحقق غير صالح' : 'Invalid verification code');
            return;
        }

        // Show loading state
        setOtpLoading(true);
        hideOtpMessages();

        // Prepare request data
        const requestData = {
            msisdn: currentPhoneNumber,
            otp: otp,
            device_type: 'web'
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
            setOtpLoading(false);

            if (data.success) {
                // Successful login - redirect to profile or home
                if (window.EbtekarDCB.locale === 'ar') {
                    showToast('تم تسجيل الدخول بنجاح', 'success');
                } else {
                    showToast('Login successful', 'success');
                }

                setTimeout(() => {
                    window.location.href = `{{ route('ebtekardcb.profile') }}?locale=${window.EbtekarDCB.locale}`;
                }, 1000);
            } else {
                if (data.otp_error) {
                    showOtpError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'رمز التحقق غير صحيح' : 'Incorrect verification code'));
                } else {
                    showOtpError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في تأكيد رمز التحقق' : 'Failed to confirm verification code'));
                }
            }
        })
        .catch(error => {
            console.error('OTP confirmation error:', error);
            setOtpLoading(false);
            showOtpError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');
        });
    }

    // OTP Modal functions
    function openOtpModal() {
        document.getElementById('otp-phone-display').textContent = formatPhoneNumber(currentPhoneNumber);
        document.getElementById('otp-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Focus first OTP input
        document.getElementById('otp-1').focus();

        // Start resend timer
        startResendTimer();
    }

    function closeOtpModal() {
        document.getElementById('otp-modal').style.display = 'none';
        document.body.style.overflow = 'auto';

        // Clear OTP inputs
        document.querySelectorAll('.otp-input').forEach(input => input.value = '');

        // Clear timer
        if (resendTimer) {
            clearInterval(resendTimer);
            resendTimer = null;
        }
    }

    // Resend OTP functionality
    function resendOtp() {
        handleLogin({ target: document.getElementById('login-form'), preventDefault: () => {} });
        startResendTimer();
    }

    function startResendTimer() {
        resendCountdown = 60;
        const resendBtn = document.getElementById('resend-otp-btn');
        const timerSpan = document.getElementById('resend-timer');

        resendBtn.disabled = true;
        resendBtn.classList.add('opacity-50');

        resendTimer = setInterval(() => {
            resendCountdown--;
            timerSpan.textContent = `(${resendCountdown})`;

            if (resendCountdown <= 0) {
                clearInterval(resendTimer);
                resendTimer = null;
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50');
                timerSpan.textContent = '';
            }
        }, 1000);
    }

    // Utility functions for UI state management
    function setLoginLoading(isLoading) {
        const btn = document.getElementById('login-submit-btn');
        const text = document.getElementById('login-btn-text');
        const spinner = document.getElementById('login-btn-spinner');

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

    function setOtpLoading(isLoading) {
        const btn = document.getElementById('otp-submit-btn');
        const text = document.getElementById('otp-btn-text');
        const spinner = document.getElementById('otp-btn-spinner');

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

    function showLoginError(message) {
        const errorDiv = document.getElementById('login-error');
        const errorText = document.getElementById('login-error-text');
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
    }

    function showLoginSuccess(message) {
        const successDiv = document.getElementById('login-success');
        const successText = document.getElementById('login-success-text');
        successText.textContent = message;
        successDiv.classList.remove('hidden');
    }

    function showOtpError(message) {
        const errorDiv = document.getElementById('otp-error');
        const errorText = document.getElementById('otp-error-text');
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
    }

    function hideLoginMessages() {
        document.getElementById('login-error').classList.add('hidden');
        document.getElementById('login-success').classList.add('hidden');
    }

    function hideOtpMessages() {
        document.getElementById('otp-error').classList.add('hidden');
    }
</script>