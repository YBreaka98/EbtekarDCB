@extends('ebtekardcb::app')
@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-50 py-12">
        <div class="bg-white px-6 py-10 shadow-xl rounded-2xl max-w-lg">

            <div class="flex flex-col items-center space-y-8">
                <img src="{{ asset('images/logo.svg') }}" alt="Your Logo" class="w-30 h-30">
                <div class="font-semibold text-3xl">ChefTech</div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-lg">
                    ChefTech هو تطبيق للبحث عن وصفات الأكل باستخدام أحدث تقنيات الذكاء الاصطناعي، يعمل التطبيق على فهم متطلبات المستخدم من خلال تجميع البيانات ومن ثم يقوم بإنشاء مجموعة متميزة من أشهر الوصفات العالمية.
                </p>
            </div>

            <div class="flex flex-col items-center space-y-8 mt-4">
                <a href="{{ route('web-login') }}" style="background-color: #000000; color: white; padding: 12px 24px; font-family: Tajawal; border-radius: 12px; text-decoration: none; font-weight: bold; display: flex; justify-content: center; align-items: center; width: 180px; height: 50px;">
                    <p style="margin: 0;">للاشتراك</p>
                </a>
            </div>

            <div class="mt-8 text-center" style="font-family: Tajawal; font-size: 12px;">
                <p class="text-lg" style="font-size: 14px;">
                    للاشتراك اليومي: 350درهم
                </p>
                <p class="text-lg" style="font-size: 14px;">
                    للاشتراك الاسبوعي: 2دينار
                </p>
            </div>

            <div class="flex flex-col items-center space-y-8 mt-4">
                <p class="text-2xl">لتحميل التطبيق</p>
            </div>

            <div class="flex justify-center space-x-4 mt-8 gap-2">
                <a href="#">
                    <img src="{{ asset('images/App Store.svg') }}" alt="Apple" class="h-12 bounce-top-icons">
                </a>
                <a href="https://play.google.com/store/apps/details?id=com.ebtekar.checftech">
                    <img src="{{ asset('images/Play Store.svg') }}" alt="Android" class="h-12 bounce-top-icons">
                </a>
            </div>
        </div>
    </div>
@endsection
