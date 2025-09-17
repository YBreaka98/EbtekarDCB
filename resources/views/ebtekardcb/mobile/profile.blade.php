@extends('ebtekardcb::layouts.app', ['isMobileWebview' => true])

@section('title', __('Profile - EbtekarDCB'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="px-4 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-800">
                    @if(($locale ?? 'en') === 'ar')
                        حسابي (Profile)
                    @else
                        Profile
                    @endif
                </h1>
                <button onclick="logout()" class="p-2 text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Loading Overlay -->
    <div id="mobile-profile-loader" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
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
    <main class="p-4">
        <div class="max-w-sm mx-auto space-y-4">
            <!-- Profile Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-ebtekar-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-ebtekar-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        @if(($locale ?? 'en') === 'ar')
                            الاسم (Name)
                        @else
                            (Name)
                        @endif
                    </h2>
                    <p id="mobile-user-phone" class="text-gray-600 font-mono text-lg">
                        <!-- Phone number will be loaded here -->
                    </p>
                </div>

                <!-- Status Row -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">
                            @if(($locale ?? 'en') === 'ar')
                                الحالة
                            @else
                                Status
                            @endif
                        </span>
                        <div id="mobile-subscription-status" class="flex items-center">
                            <!-- Status will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Expiration Row -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">
                            @if(($locale ?? 'en') === 'ar')
                                تاريخ الانتهاء
                            @else
                                Expires
                            @endif
                        </span>
                        <span id="mobile-expiration-date" class="text-sm font-semibold text-gray-800">
                            <!-- Date will be loaded here -->
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if(($locale ?? 'en') === 'ar')
                        إدارة الاشتراك
                    @else
                        Manage Subscription
                    @endif
                </h3>

                <div class="space-y-3">
                    <!-- Primary Action Button -->
                    <button id="mobile-primary-action-btn"
                            class="w-full py-4 px-6 rounded-lg text-lg font-semibold transition-colors"
                            onclick="handleMobilePrimaryAction()">
                        <span id="mobile-primary-action-text">
                            <!-- Text will be set based on subscription status -->
                        </span>
                        <div id="mobile-primary-action-spinner" class="hidden inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>

                    <!-- Secondary Action Button -->
                    <button id="mobile-secondary-action-btn"
                            class="w-full py-4 px-6 border-2 rounded-lg text-lg font-semibold transition-colors hidden"
                            onclick="handleMobileSecondaryAction()">
                        <span id="mobile-secondary-action-text">
                            <!-- Text will be set based on subscription status -->
                        </span>
                    </button>
                </div>

                <!-- Messages -->
                <div id="mobile-profile-messages" class="mt-4 space-y-2">
                    <!-- Messages will be displayed here -->
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if(($locale ?? 'en') === 'ar')
                        إعدادات سريعة
                    @else
                        Quick Actions
                    @endif
                </h3>

                <div class="space-y-2">
                    <button onclick="refreshData()" class="w-full flex items-center justify-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="text-gray-700">
                            @if(($locale ?? 'en') === 'ar')
                                تحديث البيانات
                            @else
                                Refresh Data
                            @endif
                        </span>
                    </button>

                    <button onclick="openSupport()" class="w-full flex items-center justify-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 {{ ($locale ?? 'en') === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700">
                            @if(($locale ?? 'en') === 'ar')
                                المساعدة والدعم
                            @else
                                Help & Support
                            @endif
                        </span>
                    </button>

                    <button onclick="viewTerms()" class="w-full flex items-center justify-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
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
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    let mobileCurrentSubscriptionData = null;
    let mobileCurrentAction = null;

    // Initialize mobile profile page
    document.addEventListener('DOMContentLoaded', function() {
        loadMobileSubscriptionDetails();

        // Notify app that profile page is loaded
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('pageLoaded', { page: 'profile' });
        }
    });

    // Load subscription details for mobile
    function loadMobileSubscriptionDetails() {
        const msisdn = new URLSearchParams(window.location.search).get('msisdn') || localStorage.getItem('msisdn');

        if (!msisdn) {
            // Redirect to login if no phone number
            window.location.href = `{{ route('ebtekardcb.mobile.login') }}?locale=${window.EbtekarDCB.locale}`;
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
                mobileCurrentSubscriptionData = data.subscription;
                displayMobileSubscriptionData(mobileCurrentSubscriptionData);
                setupMobileActionButtons(mobileCurrentSubscriptionData);

                // Notify app of successful data load
                if (window.EbtekarDCB.isMobileWebview) {
                    sendToApp('dataLoaded', {
                        subscription: mobileCurrentSubscriptionData
                    });
                }
            } else {
                showMobileMessage(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في تحميل بيانات الاشتراك' : 'Failed to load subscription data'), 'error');
            }
        })
        .catch(error => {
            console.error('Error loading subscription details:', error);
            showMobileMessage(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error', 'error');
        })
        .finally(() => {
            document.getElementById('mobile-profile-loader').style.display = 'none';
        });
    }

    // Display subscription data for mobile
    function displayMobileSubscriptionData(subscription) {
        // Update phone number
        document.getElementById('mobile-user-phone').textContent = formatPhoneNumber(subscription.msisdn || '');

        // Update status
        const statusElement = document.getElementById('mobile-subscription-status');
        const status = subscription.status;
        const isActive = subscription.is_active;

        let statusHTML = '';
        if (isActive) {
            statusHTML = `
                <div class="w-3 h-3 bg-green-500 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}"></div>
                <span class="text-green-700 font-semibold text-sm">
                    ${window.EbtekarDCB.locale === 'ar' ? 'نشط' : 'Active'}
                </span>
            `;
        } else if (status === 'canceled') {
            statusHTML = `
                <div class="w-3 h-3 bg-red-500 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}"></div>
                <span class="text-red-700 font-semibold text-sm">
                    ${window.EbtekarDCB.locale === 'ar' ? 'ملغي' : 'Canceled'}
                </span>
            `;
        } else {
            statusHTML = `
                <div class="w-3 h-3 bg-gray-500 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'ml-2' : 'mr-2' }}"></div>
                <span class="text-gray-700 font-semibold text-sm">
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
            document.getElementById('mobile-expiration-date').textContent = formattedDate;

            // Check if expiring soon
            const daysRemaining = subscription.days_remaining;
            if (daysRemaining !== null && daysRemaining <= 3 && daysRemaining > 0) {
                showMobileMessage(window.EbtekarDCB.locale === 'ar' ?
                    `ينتهي اشتراكك خلال ${daysRemaining} أيام` :
                    `Expires in ${daysRemaining} day${daysRemaining > 1 ? 's' : ''}`, 'warning');
            }
        } else {
            document.getElementById('mobile-expiration-date').textContent = window.EbtekarDCB.locale === 'ar' ? 'غير محدد' : 'Not specified';
        }
    }

    // Setup action buttons for mobile
    function setupMobileActionButtons(subscription) {
        const primaryBtn = document.getElementById('mobile-primary-action-btn');
        const primaryText = document.getElementById('mobile-primary-action-text');
        const secondaryBtn = document.getElementById('mobile-secondary-action-btn');
        const secondaryText = document.getElementById('mobile-secondary-action-text');

        const isActive = subscription.is_active;
        const status = subscription.status;

        if (isActive) {
            // Active subscription - show cancel button
            primaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'إلغاء الاشتراك' : 'Cancel Subscription';
            primaryBtn.className = 'w-full py-4 px-6 border-2 border-red-600 text-red-600 rounded-lg text-lg font-semibold hover:bg-red-600 hover:text-white transition-colors';
            mobileCurrentAction = 'cancel';

            secondaryBtn.classList.add('hidden');
        } else if (status === 'canceled') {
            // Canceled subscription - show activate button
            primaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'تفعيل الاشتراك' : 'Activate Subscription';
            primaryBtn.className = 'w-full py-4 px-6 bg-ebtekar-600 text-white rounded-lg text-lg font-semibold hover:bg-ebtekar-700 transition-colors';
            mobileCurrentAction = 'activate';

            secondaryBtn.classList.add('hidden');
        } else {
            // Unknown status - show activate button
            primaryText.textContent = window.EbtekarDCB.locale === 'ar' ? 'تفعيل الاشتراك' : 'Activate Subscription';
            primaryBtn.className = 'w-full py-4 px-6 bg-ebtekar-600 text-white rounded-lg text-lg font-semibold hover:bg-ebtekar-700 transition-colors';
            mobileCurrentAction = 'activate';

            secondaryBtn.classList.add('hidden');
        }
    }

    // Handle primary action for mobile
    function handleMobilePrimaryAction() {
        if (!mobileCurrentAction || !mobileCurrentSubscriptionData) return;

        // Confirm action with user
        const confirmMessage = mobileCurrentAction === 'activate' ?
            (window.EbtekarDCB.locale === 'ar' ? 'هل تريد تفعيل الاشتراك؟' : 'Do you want to activate the subscription?') :
            (window.EbtekarDCB.locale === 'ar' ? 'هل تريد إلغاء الاشتراك؟' : 'Do you want to cancel the subscription?');

        if (!confirm(confirmMessage)) return;

        executeMobileAction();
    }

    // Handle secondary action for mobile
    function handleMobileSecondaryAction() {
        // For future use if needed
    }

    // Execute mobile action
    function executeMobileAction() {
        if (!mobileCurrentAction || !mobileCurrentSubscriptionData) return;

        const msisdn = mobileCurrentSubscriptionData.msisdn;
        let endpoint = '';

        if (mobileCurrentAction === 'activate') {
            endpoint = 'subscription-activation';
        } else if (mobileCurrentAction === 'cancel') {
            endpoint = 'unsubscribe';
        }

        setMobilePrimaryLoading(true);

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
            setMobilePrimaryLoading(false);

            if (data.success) {
                showMobileMessage(window.EbtekarDCB.locale === 'ar' ? 'تم إرسال رمز التحقق' : 'Verification code sent', 'success');

                // Notify app and navigate to OTP
                if (window.EbtekarDCB.isMobileWebview) {
                    sendToApp('actionInitiated', {
                        action: mobileCurrentAction,
                        msisdn: msisdn
                    });
                }

                setTimeout(() => {
                    const locale = window.EbtekarDCB.locale;
                    window.location.href = `{{ route('ebtekardcb.mobile.action-otp') }}?action=${mobileCurrentAction}&msisdn=${encodeURIComponent(msisdn)}&locale=${locale}`;
                }, 1500);
            } else {
                showMobileMessage(data.message || (window.EbtekarDCB.locale === 'ar' ? 'فشل في العملية' : 'Operation failed'), 'error');
            }
        })
        .catch(error => {
            console.error('Mobile action error:', error);
            setMobilePrimaryLoading(false);
            showMobileMessage(window.EbtekarDCB.locale === 'ar' ? 'خطأ في الشبكة' : 'Network error', 'error');
        });
    }

    // Mobile utility functions
    function refreshData() {
        document.getElementById('mobile-profile-loader').style.display = 'flex';
        clearMobileMessages();
        loadMobileSubscriptionDetails();
    }

    function openSupport() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('openSupport');
        } else {
            window.open('{{ route("ebtekardcb.contact") }}', '_blank');
        }
    }

    function viewTerms() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('openTerms');
        } else {
            window.open('{{ route("ebtekardcb.terms") }}', '_blank');
        }
    }

    function logout() {
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('logout');
        } else {
            localStorage.removeItem('msisdn');
            localStorage.removeItem('transaction_identify');
            window.location.href = `{{ route('ebtekardcb.mobile.login') }}?locale=${window.EbtekarDCB.locale}`;
        }
    }

    // UI State Management for Mobile
    function setMobilePrimaryLoading(isLoading) {
        const btn = document.getElementById('mobile-primary-action-btn');
        const text = document.getElementById('mobile-primary-action-text');
        const spinner = document.getElementById('mobile-primary-action-spinner');

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

    function showMobileMessage(message, type = 'info') {
        const container = document.getElementById('mobile-profile-messages');

        const colors = {
            success: 'bg-green-100 border-green-300 text-green-700',
            error: 'bg-red-100 border-red-300 text-red-700',
            warning: 'bg-yellow-100 border-yellow-300 text-yellow-700',
            info: 'bg-blue-100 border-blue-300 text-blue-700'
        };

        const messageDiv = document.createElement('div');
        messageDiv.className = `p-3 border rounded-lg ${colors[type]}`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <span class="text-sm">${message}</span>
            </div>
        `;

        container.appendChild(messageDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (container.contains(messageDiv)) {
                container.removeChild(messageDiv);
            }
        }, 5000);
    }

    function clearMobileMessages() {
        document.getElementById('mobile-profile-messages').innerHTML = '';
    }
</script>
@endpush