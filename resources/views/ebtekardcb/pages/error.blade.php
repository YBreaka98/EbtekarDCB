@extends('ebtekardcb::layouts.app')

@section('title', __('Error - EbtekarDCB'))

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Error Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                @if($errorType === 'fraud')
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                @elseif($errorType === 'network')
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                    </svg>
                @elseif($errorType === 'validation')
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @elseif($errorType === 'subscription')
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @else
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @endif
            </div>

            <!-- Error Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                @if($errorType === 'fraud')
                    @if(($locale ?? 'en') === 'ar')
                        تم اكتشاف نشاط مشبوه
                    @else
                        Suspicious Activity Detected
                    @endif
                @elseif($errorType === 'network')
                    @if(($locale ?? 'en') === 'ar')
                        خطأ في الاتصال
                    @else
                        Connection Error
                    @endif
                @elseif($errorType === 'validation')
                    @if(($locale ?? 'en') === 'ar')
                        بيانات غير صحيحة
                    @else
                        Invalid Data
                    @endif
                @elseif($errorType === 'subscription')
                    @if(($locale ?? 'en') === 'ar')
                        خطأ في الاشتراك
                    @else
                        Subscription Error
                    @endif
                @else
                    @if(($locale ?? 'en') === 'ar')
                        حدث خطأ
                    @else
                        An Error Occurred
                    @endif
                @endif
            </h1>

            <!-- Error Message -->
            <p class="text-gray-600 mb-6 leading-relaxed">
                @if($errorType === 'fraud')
                    @if(($locale ?? 'en') === 'ar')
                        تم اكتشاف نشاط مشبوه مرتبط بحسابك. من أجل أمانك، تم تعليق العملية مؤقتاً. يرجى المحاولة مرة أخرى لاحقاً أو الاتصال بالدعم الفني.
                    @else
                        Suspicious activity has been detected associated with your account. For your security, the operation has been temporarily suspended. Please try again later or contact technical support.
                    @endif
                @elseif($errorType === 'network')
                    @if(($locale ?? 'en') === 'ar')
                        فشل في الاتصال بالخادم. يرجى التحقق من اتصال الإنترنت والمحاولة مرة أخرى.
                    @else
                        Failed to connect to the server. Please check your internet connection and try again.
                    @endif
                @elseif($errorType === 'validation')
                    @if(($locale ?? 'en') === 'ar')
                        البيانات المدخلة غير صحيحة أو غير مكتملة. يرجى التحقق من المعلومات والمحاولة مرة أخرى.
                    @else
                        The entered data is invalid or incomplete. Please check the information and try again.
                    @endif
                @elseif($errorType === 'subscription')
                    @if(($locale ?? 'en') === 'ar')
                        حدث خطأ في معالجة اشتراكك. يرجى المحاولة مرة أخرى أو الاتصال بالدعم الفني.
                    @else
                        An error occurred while processing your subscription. Please try again or contact technical support.
                    @endif
                @else
                    {{ $errorMessage ?? (($locale ?? 'en') === 'ar' ? 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.' : 'An unexpected error occurred. Please try again.') }}
                @endif
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($errorType === 'fraud')
                    <button onclick="contactSupport()"
                            class="w-full ebtekar-btn ebtekar-btn-primary">
                        @if(($locale ?? 'en') === 'ar')
                            اتصل بالدعم الفني
                        @else
                            Contact Support
                        @endif
                    </button>
                    <button onclick="goHome()"
                            class="w-full ebtekar-btn ebtekar-btn-outline">
                        @if(($locale ?? 'en') === 'ar')
                            العودة للرئيسية
                        @else
                            Go to Home
                        @endif
                    </button>
                @elseif($errorType === 'network')
                    <button onclick="retryAction()"
                            class="w-full ebtekar-btn ebtekar-btn-primary">
                        @if(($locale ?? 'en') === 'ar')
                            إعادة المحاولة
                        @else
                            Retry
                        @endif
                    </button>
                    <button onclick="goHome()"
                            class="w-full ebtekar-btn ebtekar-btn-outline">
                        @if(($locale ?? 'en') === 'ar')
                            العودة للرئيسية
                        @else
                            Go to Home
                        @endif
                    </button>
                @else
                    <button onclick="goBack()"
                            class="w-full ebtekar-btn ebtekar-btn-primary">
                        @if(($locale ?? 'en') === 'ar')
                            المحاولة مرة أخرى
                        @else
                            Try Again
                        @endif
                    </button>
                    <button onclick="goHome()"
                            class="w-full ebtekar-btn ebtekar-btn-outline">
                        @if(($locale ?? 'en') === 'ar')
                            العودة للرئيسية
                        @else
                            Go to Home
                        @endif
                    </button>
                @endif
            </div>

            <!-- Additional Info -->
            @if($errorCode ?? false)
                <div class="mt-6 p-3 bg-gray-100 rounded-lg">
                    <p class="text-xs text-gray-500">
                        @if(($locale ?? 'en') === 'ar')
                            رمز الخطأ:
                        @else
                            Error Code:
                        @endif
                        <span class="font-mono">{{ $errorCode }}</span>
                    </p>
                </div>
            @endif
        </div>

        <!-- Support Information -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>
                @if(($locale ?? 'en') === 'ar')
                    تحتاج مساعدة؟
                @else
                    Need help?
                @endif
                <a href="{{ route('ebtekardcb.contact') }}" class="text-ebtekar-600 hover:text-ebtekar-700 underline">
                    @if(($locale ?? 'en') === 'ar')
                        اتصل بنا
                    @else
                        Contact us
                    @endif
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Error page actions
    function retryAction() {
        // Get the original URL from referrer or localStorage
        const originalUrl = document.referrer || localStorage.getItem('lastUrl') || `{{ route('ebtekardcb.landing') }}`;

        // Clear any error states
        localStorage.removeItem('errorState');

        // Redirect back
        window.location.href = originalUrl;
    }

    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            goHome();
        }
    }

    function goHome() {
        const locale = window.EbtekarDCB.locale;
        window.location.href = `{{ route('ebtekardcb.landing') }}?locale=${locale}`;
    }

    function contactSupport() {
        // For mobile webview, send message to app
        if (window.EbtekarDCB.isMobileWebview) {
            sendToApp('contactSupport', {
                errorType: '{{ $errorType }}',
                errorCode: '{{ $errorCode ?? "" }}',
                errorMessage: '{{ $errorMessage ?? "" }}'
            });
        } else {
            // For web, open contact page
            window.location.href = `{{ route('ebtekardcb.contact') }}?locale=${window.EbtekarDCB.locale}`;
        }
    }

    // Log error for analytics (if available)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'error_page_view', {
            'error_type': '{{ $errorType }}',
            'error_code': '{{ $errorCode ?? "" }}',
            'page_location': window.location.href
        });
    }

    // Notify mobile app if in webview
    if (window.EbtekarDCB.isMobileWebview) {
        sendToApp('errorPageLoaded', {
            errorType: '{{ $errorType }}',
            errorCode: '{{ $errorCode ?? "" }}',
            errorMessage: '{{ $errorMessage ?? "" }}'
        });
    }
</script>
@endpush