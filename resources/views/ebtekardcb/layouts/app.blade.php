<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}" dir="{{ ($locale ?? 'en') === 'ar' ? 'rtl' : 'ltr' }}" class="{{ ($locale ?? 'en') === 'ar' ? 'rtl' : 'ltr' }} cptpl_page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('EbtekarDCB Service'))</title>

    <!-- Required anti-fraud template input -->
    <input type="hidden" value="20230925" name="cptpl_template" class="test cptpl_template" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- EbtekarDCB CSS -->
    <link href="{{ asset('css/ebtekardcb-simple.css') }}" rel="stylesheet">

    <!-- Tailwind CSS CDN for additional utilities -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <style>
        .ebtekar-gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
        }

        .ebtekar-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #dc2626;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Arabic text improvements */
        .arabic-content {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            line-height: 1.8;
        }

        .english-content {
            font-family: 'Inter', 'Roboto', sans-serif;
            line-height: 1.6;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 {{ ($locale ?? 'en') === 'ar' ? 'arabic-content' : 'english-content' }}">
    <!-- Loading overlay -->
    <div id="loader" class="fixed inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="loader mx-auto mb-4"></div>
            <p class="text-gray-600">{{ __('Loading...') }}</p>
        </div>
    </div>

    <!-- Main content -->
    <div class="min-h-screen">
        @yield('content')
    </div>

    <!-- Language switcher (if not mobile webview) -->
    @if(!isset($isMobileWebview) || !$isMobileWebview)
        <div class="fixed top-4 {{ ($locale ?? 'en') === 'ar' ? 'left-4' : 'right-4' }} z-40">
            <div class="bg-white rounded-lg shadow-lg p-2">
                <button onclick="switchLanguage()" class="flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors">
                    <span class="text-sm font-medium">
                        {{ ($locale ?? 'en') === 'ar' ? 'English' : 'العربية' }}
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- Toast notifications container -->
    <div id="toast-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 space-y-2"></div>

    <!-- Scripts -->
    <script>
        // Global configuration
        window.EbtekarDCB = {
            locale: '{{ $locale ?? "en" }}',
            csrfToken: '{{ csrf_token() }}',
            apiBaseUrl: '{{ config("ebtekardcb.base_url", "/api/ebtekardcb") }}',
            isMobileWebview: {{ isset($isMobileWebview) && $isMobileWebview ? 'true' : 'false' }},
            isRTL: {{ ($locale ?? 'en') === 'ar' ? 'true' : 'false' }}
        };

        // Language switching function
        function switchLanguage() {
            const currentLang = window.EbtekarDCB.locale;
            const newLang = currentLang === 'ar' ? 'en' : 'ar';

            // Update URL with new language
            const url = new URL(window.location);
            url.searchParams.set('locale', newLang);
            window.location.href = url.toString();
        }

        // Toast notification system
        function showToast(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-y-0 opacity-100`;
            toast.textContent = message;

            container.appendChild(toast);

            // Auto remove after duration
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }, duration);
        }

        // Global error handler
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
            if (window.EbtekarDCB.locale === 'ar') {
                showToast('حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.', 'error');
            } else {
                showToast('An unexpected error occurred. Please try again.', 'error');
            }
        });

        // Utility functions
        function formatPhoneNumber(phone) {
            // Remove all non-digits
            const cleaned = phone.replace(/\D/g, '');

            // Libyan format: 2189XXXXXXXX
            if (cleaned.length === 12 && cleaned.startsWith('2189')) {
                return cleaned.replace(/(\d{4})(\d{2})(\d{3})(\d{3})/, '$1 $2 $3 $4');
            }

            return phone;
        }

        function validateLibyanPhone(phone) {
            const cleaned = phone.replace(/\D/g, '');
            return /^2189[1-6][0-9]{7}$/.test(cleaned);
        }

        function validateOTP(otp) {
            return /^[0-9]{4}$/.test(otp);
        }

        // Mobile webview communication
        if (window.EbtekarDCB.isMobileWebview) {
            function sendToApp(action, data = {}) {
                if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.ebtekarApp) {
                    // iOS
                    window.webkit.messageHandlers.ebtekarApp.postMessage({
                        action: action,
                        data: data
                    });
                } else if (window.Android && window.Android.onWebViewMessage) {
                    // Android
                    window.Android.onWebViewMessage(JSON.stringify({
                        action: action,
                        data: data
                    }));
                }
            }

            // Export to global scope
            window.sendToApp = sendToApp;
        }
    </script>

    @stack('scripts')
</body>
</html>