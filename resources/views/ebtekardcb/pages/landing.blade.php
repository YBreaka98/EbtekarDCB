@extends('ebtekardcb::layouts.app')

@section('title', __('EbtekarDCB - Premium Content Service'))

@section('content')
<div class="min-h-screen ebtekar-gradient-bg ebtekar-pattern">
    <!-- Header -->
    <header class="relative z-10 pt-8 pb-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-center">
                <img src="{{ $logoUrl ?? asset('images/service_logo.png') }}"
                     alt="{{ __('Service Logo') }}"
                     class="cptpl_logo h-16 w-auto">
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto">
                <!-- Service Description Card -->
                <div class="ebtekar-card fade-in mb-8">
                    <div class="p-8 text-center">
                        <!-- Service Description -->
                        <div class="mb-6">
                            <span class="main_span cptpl_service text-xl font-semibold text-gray-800 block mb-4">
                                {{ $serviceDescription ?? __('Enjoy premium content and exclusive features with our subscription service') }}
                            </span>
                        </div>

                        <!-- Pricing Information -->
                        <div class="mb-6">
                            <p class="price_title cptpl_price text-lg font-medium text-gray-700">
                                @if(($locale ?? 'en') === 'ar')
                                    350 درهم للاشتراك اليومي، 2 دينار للاشتراك الأسبوعي
                                @else
                                    0.25 LYD daily Libyana, 1.5 LYD weekly Almadar
                                @endif
                            </p>
                        </div>

                        <!-- Login Buttons -->
                        <div class="space-y-4">
                            <!-- Web Login Button -->
                            <button id="web-login-btn"
                                    class="cptpl_subscribe ebtekar-btn ebtekar-btn-primary w-full text-lg font-semibold"
                                    onclick="openLoginModal()">
                                @if(($locale ?? 'en') === 'ar')
                                    تابع للاشتراك
                                @else
                                    Continue to subscribe
                                @endif
                            </button>

                            <!-- Mobile App Login Button (if applicable) -->
                            @if(isset($showMobileLogin) && $showMobileLogin)
                                <button id="mobile-login-btn"
                                        class="ebtekar-btn ebtekar-btn-outline w-full"
                                        onclick="openMobileLogin()">
                                    @if(($locale ?? 'en') === 'ar')
                                        الدخول عبر التطبيق
                                    @else
                                        Login via App
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Features List -->
                <div class="ebtekar-card fade-in mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                            @if(($locale ?? 'en') === 'ar')
                                مميزات الخدمة
                            @else
                                Service Features
                            @endif
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-center {{ ($locale ?? 'en') === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                <div class="w-2 h-2 bg-ebtekar-600 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'mr-3' : 'ml-3' }}"></div>
                                <span class="text-gray-700">
                                    @if(($locale ?? 'en') === 'ar')
                                        محتوى حصري ومتميز
                                    @else
                                        Exclusive premium content
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center {{ ($locale ?? 'en') === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                <div class="w-2 h-2 bg-ebtekar-600 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'mr-3' : 'ml-3' }}"></div>
                                <span class="text-gray-700">
                                    @if(($locale ?? 'en') === 'ar')
                                        اشتراك مرن يومي أو أسبوعي
                                    @else
                                        Flexible daily or weekly subscription
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center {{ ($locale ?? 'en') === 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                                <div class="w-2 h-2 bg-ebtekar-600 rounded-full {{ ($locale ?? 'en') === 'ar' ? 'mr-3' : 'ml-3' }}"></div>
                                <span class="text-gray-700">
                                    @if(($locale ?? 'en') === 'ar')
                                        دعم فني متواصل
                                    @else
                                        24/7 technical support
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto">
                <div class="text-center">
                    <p class="price_text cptpl_terms text-sm text-white/80 mb-4">
                        @if(($locale ?? 'en') === 'ar')
                            بالاشتراك، فإنك توافق على شروط وأحكام الخدمة. يمكنك إلغاء الاشتراك في أي وقت.
                        @else
                            By subscribing, you agree to our terms and conditions. You can cancel your subscription at any time.
                        @endif
                    </p>

                    <div class="flex justify-center space-x-4 {{ ($locale ?? 'en') === 'ar' ? 'space-x-reverse' : '' }}">
                        <a href="{{ route('ebtekardcb.terms') }}" class="text-white/80 hover:text-white text-sm underline">
                            @if(($locale ?? 'en') === 'ar')
                                الشروط والأحكام
                            @else
                                Terms & Conditions
                            @endif
                        </a>
                        <a href="{{ route('ebtekardcb.privacy') }}" class="text-white/80 hover:text-white text-sm underline">
                            @if(($locale ?? 'en') === 'ar')
                                سياسة الخصوصية
                            @else
                                Privacy Policy
                            @endif
                        </a>
                        <a href="{{ route('ebtekardcb.contact') }}" class="text-white/80 hover:text-white text-sm underline">
                            @if(($locale ?? 'en') === 'ar')
                                اتصل بنا
                            @else
                                Contact Us
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Login Modal -->
@include('ebtekardcb::components.login-modal')

@endsection

@push('scripts')
<script>
    // Anti-fraud integration variables
    let transactionIdentify = null;
    let dcbProtectScript = null;

    // Initialize anti-fraud protection on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeAntiFraud();
    });

    function initializeAntiFraud() {
        const vars = {
            targeted_element: '#web-login-btn'
        };

        // Call the protected script API
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
                // Store transaction identify in localStorage
                transactionIdentify = data.transaction_identify;
                localStorage.setItem('transaction_identify', transactionIdentify);

                // Inject the DCB protect script
                dcbProtectScript = data.dcbprotect;
                const script = document.createElement('script');
                script.type = 'text/javascript';
                script.text = dcbProtectScript;
                document.body.appendChild(script);

                // Dispatch custom event
                const event = new Event('DCBProtectRun');
                document.dispatchEvent(event);
            } else {
                console.error('Failed to initialize anti-fraud protection:', data.message);
                if (window.EbtekarDCB.locale === 'ar') {
                    showToast('فشل في تهيئة نظام الحماية من الاحتيال', 'error');
                } else {
                    showToast('Failed to initialize fraud protection system', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Anti-fraud initialization error:', error);
            if (window.EbtekarDCB.locale === 'ar') {
                showToast('خطأ في تهيئة نظام الحماية من الاحتيال', 'error');
            } else {
                showToast('Fraud protection system initialization error', 'error');
            }
        });
    }

    // Listen for gateway load event to hide loader
    document.addEventListener('gateway-load', function() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.add('hidden_loader');
        }
    });

    // Login modal functions
    function openLoginModal() {
        document.getElementById('login-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLoginModal() {
        document.getElementById('login-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Mobile login redirection
    function openMobileLogin() {
        const locale = window.EbtekarDCB.locale;
        window.location.href = `{{ route('ebtekardcb.mobile.login') }}?locale=${locale}`;
    }

    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('ebtekar-modal-backdrop')) {
            closeLoginModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLoginModal();
        }
    });
</script>
@endpush