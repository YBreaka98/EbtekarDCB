@extends('ebtekardcb::layouts.app')

@section('title', __('Profile - EbtekarDCB'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ $logoUrl ?? asset('images/service_logo.png') }}"
                         alt="{{ __('Service Logo') }}"
                         class="h-10 w-auto {{ ($locale ?? 'en') === 'ar' ? 'ml-3' : 'mr-3' }}">
                    <h1 class="text-2xl font-bold text-gray-800">
                        @if(($locale ?? 'en') === 'ar')
                            حسابي
                        @else
                            Profile
                        @endif
                    </h1>
                </div>

                <button onclick="logout()" class="text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Loading Overlay -->
    <div id="profile-loader" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="loader mx-auto mb-4"></div>
            <p class="text-gray-600">
                @if(($locale ?? 'en') === 'ar')
                    جار تحميل البيانات...
                @else
                    Loading data...
                @endif
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center {{ ($locale ?? 'en') === 'ar' ? 'flex-row-reverse' : 'flex-row' }} mb-6">
                    <div class="w-16 h-16 bg-ebtekar-100 rounded-full flex items-center justify-center {{ ($locale ?? 'en') === 'ar' ? 'ml-4' : 'mr-4' }}">
                        <svg class="w-8 h-8 text-ebtekar-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="{{ ($locale ?? 'en') === 'ar' ? 'text-right' : 'text-left' }}">
                        <h2 class="text-xl font-semibold text-gray-800">
                            @if(($locale ?? 'en') === 'ar')
                                الاسم (Name)
                            @else
                                (Name)
                            @endif
                        </h2>
                        <p id="user-phone" class="text-gray-600 font-mono">
                            <!-- Phone number will be loaded here -->
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status Card -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">
                            @if(($locale ?? 'en') === 'ar')
                                الحالة (Status)
                            @else
                                Status
                            @endif
                        </h3>
                        <div id="subscription-status" class="flex items-center">
                            <!-- Status will be loaded here -->
                        </div>
                    </div>

                    <!-- Expiration Date Card -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">
                            @if(($locale ?? 'en') === 'ar')
                                تاريخ الانتهاء
                            @else
                                Expiration Date
                            @endif
                        </h3>
                        <p id="expiration-date" class="text-lg font-semibold text-gray-800">
                            <!-- Date will be loaded here -->
                        </p>
                    </div>
                </div>
            </div>

            <!-- Subscription Actions Card -->
            <div id="subscription-actions" class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if(($locale ?? 'en') === 'ar')
                        إدارة الاشتراك
                    @else
                        Manage Subscription
                    @endif
                </h3>

                <div class="space-y-4">
                    <!-- Primary Action Button -->
                    <button id="primary-action-btn"
                            class="w-full ebtekar-btn ebtekar-btn-primary text-lg"
                            onclick="handlePrimaryAction()">
                        <span id="primary-action-text">
                            <!-- Text will be set based on subscription status -->
                        </span>
                        <div id="primary-action-spinner" class="ebtekar-spinner hidden"></div>
                    </button>

                    <!-- Secondary Action Button -->
                    <button id="secondary-action-btn"
                            class="w-full ebtekar-btn ebtekar-btn-outline text-lg hidden"
                            onclick="handleSecondaryAction()">
                        <span id="secondary-action-text">
                            <!-- Text will be set based on subscription status -->
                        </span>
                    </button>
                </div>

                <!-- Warning Messages -->
                <div id="warning-message" class="hidden mt-4 p-4 bg-yellow-100 border border-yellow-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span id="warning-text" class="text-yellow-700 text-sm"></span>
                    </div>
                </div>

                <!-- Error Messages -->
                <div id="error-message" class="hidden mt-4 p-4 bg-red-100 border border-red-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="error-text" class="text-red-700 text-sm"></span>
                    </div>
                </div>

                <!-- Success Messages -->
                <div id="success-message" class="hidden mt-4 p-4 bg-green-100 border border-green-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="success-text" class="text-green-700 text-sm"></span>
                    </div>
                </div>
            </div>

            <!-- Subscription Details Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if(($locale ?? 'en') === 'ar')
                        تفاصيل الاشتراك
                    @else
                        Subscription Details
                    @endif
                </h3>

                <div id="subscription-details" class="space-y-3 text-sm">
                    <!-- Details will be loaded here -->
                </div>
            </div>

            <!-- Support Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if(($locale ?? 'en') === 'ar')
                        الدعم والمساعدة
                    @else
                        Support & Help
                    @endif
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('ebtekardcb.contact') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700">
                            @if(($locale ?? 'en') === 'ar')
                                اتصل بنا
                            @else
                                Contact Us
                            @endif
                        </span>
                    </a>

                    <a href="{{ route('ebtekardcb.terms') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-gray-700">
                            @if(($locale ?? 'en') === 'ar')
                                الشروط والأحكام
                            @else
                                Terms & Conditions
                            @endif
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Action Confirmation Modal -->
@include('ebtekardcb::components.action-modal')

@endsection

@push('scripts')
<script>
    let currentSubscriptionData = null;
    let currentAction = null;

    // Initialize profile page
    document.addEventListener('DOMContentLoaded', function() {
        loadSubscriptionDetails();
    });

    // Load subscription details
    function loadSubscriptionDetails() {
        const msisdn = new URLSearchParams(window.location.search).get('msisdn') || localStorage.getItem('msisdn');

        if (!msisdn) {
            // Redirect to login if no phone number
            window.location.href = `{{ route('ebtekardcb.landing') }}?locale=${window.EbtekarDCB.locale}`;
            return;
        }

        fetch(`${window.EbtekarDCB.apiBaseUrl}/subscription-details?msisdn=${encodeURIComponent(msisdn)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.EbtekarDCB.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentSubscriptionData = data.subscription;
                displaySubscriptionData(currentSubscriptionData);
                setupActionButtons(currentSubscriptionData);
            } else {
                showError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في تحميل بيانات الاشتراك' : 'Failed to load subscription data'));
            }
        })
        .catch(error => {
            console.error('Error loading subscription details:', error);
            showError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');
        })
        .finally(() => {
            document.getElementById('profile-loader').style.display = 'none';
        });
    }

    // Display subscription data
    function displaySubscriptionData(subscription) {
        // Update phone number
        document.getElementById('user-phone').textContent = formatPhoneNumber(subscription.msisdn || '');

        // Update status
        const statusElement = document.getElementById('subscription-status');
        const status = subscription.status;
        const isActive = subscription.is_active;

        let statusHTML = '';
        if (isActive) {
            statusHTML = `
                <div class="w-3 h-3 bg-green-500 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}"></div>
                <span class="text-green-700 font-semibold">
                    ${window.EbtekarDCB.locale === 'ar' ? 'نشط' : 'Active'}
                </span>
            `;
        } else if (status === 'canceled') {
            statusHTML = `
                <div class="w-3 h-3 bg-red-500 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}"></div>
                <span class="text-red-700 font-semibold">
                    ${window.EbtekarDCB.locale === 'ar' ? 'ملغي' : 'Canceled'}
                </span>
            `;
        } else {
            statusHTML = `
                <div class="w-3 h-3 bg-gray-500 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}"></div>
                <span class="text-gray-700 font-semibold">
                    ${status || (window.EbtekarDCB.locale === 'ar' ? 'غير معروف' : 'Unknown')}
                </span>
            `;
        }
        statusElement.innerHTML = statusHTML;

        // Update expiration date
        const expirationDate = subscription.expiration_date;
        if (expirationDate) {
            const date = new Date(expirationDate);
            const formattedDate = date.toLocaleDateString(window.EbtekarDCB.locale === 'ar' ? 'ar-EG' : 'en-US');
            document.getElementById('expiration-date').textContent = formattedDate;

            // Check if expiring soon
            const daysRemaining = subscription.days_remaining;
            if (daysRemaining !== null && daysRemaining <= 3 && daysRemaining > 0) {
                showWarning(window.EbtekarDCB.locale === 'ar' ?
                    `ينتهي اشتراكك خلال ${daysRemaining} أيام` :
                    `Your subscription expires in ${daysRemaining} day${daysRemaining > 1 ? 's' : ''}`);
            }
        } else {
            document.getElementById('expiration-date').textContent = window.EbtekarDCB.locale === 'ar' ? 'غير محدد' : 'Not specified';
        }

        // Update subscription details
        const detailsElement = document.getElementById('subscription-details');
        const details = subscription.data || {};
        let detailsHTML = '';

        Object.keys(details).forEach(key => {
            if (details[key] !== null && details[key] !== undefined && details[key] !== '') {
                detailsHTML += `
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">${key}:</span>
                        <span class="text-gray-800 font-medium">${details[key]}</span>
                    </div>
                `;
            }
        });

        if (detailsHTML === '') {
            detailsHTML = `
                <p class="text-gray-500 text-center">
                    ${window.EbtekarDCB.locale === 'ar' ? 'لا توجد تفاصيل إضافية' : 'No additional details available'}
                </p>
            `;
        }

        detailsElement.innerHTML = detailsHTML;
    }

    // Setup action buttons based on subscription status
    function setupActionButtons(subscription) {
        const primaryBtn = document.getElementById('primary-action-btn');
        const primaryText = document.getElementById('primary-action-text');
        const secondaryBtn = document.getElementById('secondary-action-btn');
        const secondaryText = document.getElementById('secondary-action-text');

        const isActive = subscription.is_active;
        const status = subscription.status;

        if (isActive) {
            // Active subscription - show cancel button
            primaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'إلغاء الاشتراك' : 'Cancel Subscription';
            primaryBtn.className = 'w-full ebtekar-btn ebtekar-btn-outline text-lg';
            primaryBtn.onclick = () => handleCancelSubscription();

            secondaryBtn.classList.add('hidden');
        } else if (status === 'canceled') {
            // Canceled subscription - show activate button
            primaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'تفعيل الاشتراك' : 'Activate Subscription';
            primaryBtn.className = 'w-full ebtekar-btn ebtekar-btn-primary text-lg';
            primaryBtn.onclick = () => handleActivateSubscription();

            secondaryBtn.classList.add('hidden');
        } else {
            // Unknown status - show both options
            primaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'تفعيل الاشتراك' : 'Activate Subscription';
            primaryBtn.className = 'w-full ebtekar-btn ebtekar-btn-primary text-lg';
            primaryBtn.onclick = () => handleActivateSubscription();

            secondaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'إلغاء الاشتراك' : 'Cancel Subscription';
            secondaryBtn.classList.remove('hidden');
            secondaryBtn.onclick = () => handleCancelSubscription();
        }
    }

    // Handle subscription activation
    function handleActivateSubscription() {
        currentAction = 'activate';
        openActionModal(
            window.EbtekarDCB.locale === 'ar' ? 'تفعيل الاشتراك' : 'Activate Subscription',
            window.EbtekarDCB.locale === 'ar' ?
                'هل أنت متأكد من رغبتك في تفعيل الاشتراك؟ سيتم إرسال رمز التحقق إلى رقم هاتفك.' :
                'Are you sure you want to activate your subscription? A verification code will be sent to your phone.',
            window.EbtekarDCB.locale === 'ar' ? 'تفعيل' : 'Activate'
        );
    }

    // Handle subscription cancellation
    function handleCancelSubscription() {
        currentAction = 'cancel';
        openActionModal(
            window.EbtekarDCB.locale === 'ar' ? 'إلغاء الاشتراك' : 'Cancel Subscription',
            window.EbtekarDCB.locale === 'ar' ?
                'هل أنت متأكد من رغبتك في إلغاء الاشتراك؟ سيتم إرسال رمز التحقق إلى رقم هاتفك للتأكيد.' :
                'Are you sure you want to cancel your subscription? A verification code will be sent to your phone for confirmation.',
            window.EbtekarDCB.locale === 'ar' ? 'إلغاء' : 'Cancel'
        );
    }

    // Execute the confirmed action
    function executeAction() {
        if (!currentAction || !currentSubscriptionData) return;

        const msisdn = currentSubscriptionData.msisdn;
        let endpoint = '';

        if (currentAction === 'activate') {
            endpoint = 'subscription-activation';
        } else if (currentAction === 'cancel') {
            endpoint = 'unsubscribe';
        }

        // Send request
        fetch(`${window.EbtekarDCB.apiBaseUrl}/${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.EbtekarDCB.csrfToken
            },
            body: JSON.stringify({ msisdn: msisdn })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(window.EbtekarDCB.locale === 'ar' ? 'تم إرسال رمز التحقق' : 'Verification code sent');

                // Navigate to OTP confirmation
                const locale = window.EbtekarDCB.locale;
                window.location.href = `{{ route('ebtekardcb.action-otp') }}?action=${currentAction}&msisdn=${encodeURIComponent(msisdn)}&locale=${locale}`;
            } else {
                showError(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في العملية' : 'Operation failed'));
            }
        })
        .catch(error => {
            console.error('Action error:', error);
            showError(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error');
        });
    }

    // Logout function
    function logout() {
        // Clear any stored data
        localStorage.removeItem('msisdn');
        localStorage.removeItem('transaction_identify');

        // Redirect to landing page
        window.location.href = `{{ route('ebtekardcb.landing') }}?locale=${window.EbtekarDCB.locale}`;
    }

    // Message display functions
    function showError(message) {
        hideAllMessages();
        const errorDiv = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
    }

    function showSuccess(message) {
        hideAllMessages();
        const successDiv = document.getElementById('success-message');
        const successText = document.getElementById('success-text');
        successText.textContent = message;
        successDiv.classList.remove('hidden');
    }

    function showWarning(message) {
        hideAllMessages();
        const warningDiv = document.getElementById('warning-message');
        const warningText = document.getElementById('warning-text');
        warningText.textContent = message;
        warningDiv.classList.remove('hidden');
    }

    function hideAllMessages() {
        document.getElementById('error-message').classList.add('hidden');
        document.getElementById('success-message').classList.add('hidden');
        document.getElementById('warning-message').classList.add('hidden');
    }
</script>
@endpush