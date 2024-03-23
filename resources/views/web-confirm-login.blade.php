@extends('ebtekardcb::app')
@push('css')
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* semi-transparent white */
            z-index: 9999; /* ensure it's on top of other elements */
            display: none; /* hide initially */
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Add your loading spinner styles here */
        }
    </style>
@endpush
@section('content')
    <div class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray-50 py-12">
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner">
                <img src="{{ asset('images/logo.svg') }}" alt="Your Logo" class="w-30 h-30">
                الرجاء الإنتظار
            </div>
        </div>
        <div class="relative bg-white px-6 pt-10 pb-9 shadow-xl mx-auto w-full max-w-lg rounded-2xl">
            <div class="mx-auto flex w-full max-w-md flex-col space-y-16">
                <div class="flex flex-col items-center justify-center text-center space-y-8">
                    <img src="{{ asset('images/logo.svg') }}" alt="Your Logo" class="w-30 h-30">
                    <div class="font-semibold text-3xl">
                        <p>أدخل رمز التحقق</p>
                    </div>
                </div>

                <div>
                    <form id="confirmLoginForm" method="post">
                        <div class="flex flex-col space-y-16">
                            <div class="flex justify-center">
                                <div class="flex flex-row items-center justify-between max-w-xs" dir="ltr">
                                    <input
                                        class="otp-input w-16 h-16 text-center px-3 mx-2 outline-none rounded-3xl border border-gray-200 text-lg bg-white focus:border-bg-black focus:ring-2 focus:ring-gray-200"
                                        maxlength="1" type="text" pattern="[0-9]" inputmode="numeric"
                                        onkeydown="return onlyNumberKey(event)" name="otp1" id="otp1" required>
                                    <input
                                        class="otp-input w-16 h-16 text-center px-3 mx-2 outline-none rounded-3xl border border-gray-200 text-lg bg-white focus:border-bg-black focus:ring-2 focus:ring-gray-200"
                                        maxlength="1" type="text" pattern="[0-9]" inputmode="numeric"
                                        onkeydown="return onlyNumberKey(event)" name="otp2" id="otp2" required>
                                    <input
                                        class="otp-input w-16 h-16 text-center px-3 mx-2 outline-none rounded-3xl border border-gray-200 text-lg bg-white focus:border-bg-black focus:ring-2 focus:ring-gray-200"
                                        maxlength="1" type="text" pattern="[0-9]" inputmode="numeric"
                                        onkeydown="return onlyNumberKey(event)" name="otp3" id="otp3" required>
                                    <input
                                        class="otp-input w-16 h-16 text-center px-3 mx-2 outline-none rounded-3xl border border-gray-200 text-lg bg-white focus:border-bg-black focus:ring-2 focus:ring-gray-200"
                                        maxlength="1" type="text" pattern="[0-9]" inputmode="numeric"
                                        onkeydown="return onlyNumberKey(event)" name="otp4" id="otp4" required>
                                </div>
                            </div>

                            <div class="flex flex-col space-y-5">
                                <div>
                                    <button
                                        class="flex justify-center items-center w-full border rounded-xl outline-none py-5 border-none text-white text-2xl shadow-sm"
                                        style="background-color: #000000; color: white">
                                        تأكيد
                                    </button>
                                </div>
                                <div class="mt-1 text-center">
                                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:text-blue-700 focus:outline-none focus:underline">العودة إلى صفحة تسجيل الدخول</a>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div id="errorMessage" class="text-red-600 text-lg mt-2"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>

        function showLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        function hideLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function showErrorMessage(message) {
            document.getElementById('errorMessage').innerText = message;
        }

        function onlyNumberKey(evt) {
            const keyPressed = evt.key;
            const currentInput = evt.target;
            if (keyPressed === 'Backspace' || keyPressed === 'Delete') {

                if (currentInput.value === '') {
                    const prevInput = currentInput.previousElementSibling;
                    if (prevInput !== null) {
                        prevInput.focus();
                        prevInput.value = '';
                    }
                }
                return true;
            }

            if (!isNaN(parseInt(keyPressed))) {
                return true;
            }

            evt.preventDefault();
        }

        function confirm(event) {
            event.preventDefault();
            showLoadingOverlay();
            let token = localStorage.getItem('token');

            if (!token) {
                console.error("Token is missing.");
                return;
            }

            let msisdn = window.location.pathname.split('/').pop();

            let otp = '';
            otp += document.getElementById('otp1').value;
            otp += document.getElementById('otp2').value;
            otp += document.getElementById('otp3').value;
            otp += document.getElementById('otp4').value;

            var vars = {
                msisdn: msisdn,
                device_type: 'android',
                otp: otp,
                token: token
            };
           $.ajax({
                url: @json($url),
                type: "POST",
                data: vars,
                dataType: "json",
                success: function (data) {
                    hideLoadingOverlay()
                    alert('تم الاشتراك بنجاح. يرجى تنزيل التطبيق الآن.');
                    window.location.href = "/";

                },
                error: function (xhr, status, error) {
                    hideLoadingOverlay()
                    if (JSON.parse(xhr.responseJSON?.error).messageCode == '01') {
                        showErrorMessage('رمز التحقق غير صحيح');

                    } else {
                        showErrorMessage('حدث خطأ أثناء تسجيل الدخول. الرجاء المحاولة مرة أخرى');
                    }
                }
            });
        }
    </script>

    <script>

        document.getElementById('confirmLoginForm').addEventListener('submit', confirm);
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var inputs = document.getElementsByClassName('otp-input');
            for (var i = inputs.length - 1; i >= 0; i--) {
                inputs[i].addEventListener('input', function () {
                    if (this.value.length >= parseInt(this.getAttribute('maxlength'))) {
                        var prevInput = this.nextElementSibling;
                        if (prevInput !== null) {
                            prevInput.focus();
                        }
                    }
                });
            }
        });
    </script>
@endpush


