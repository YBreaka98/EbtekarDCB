@extends('ebtekardcb::app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet"/>

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
                        <p>أدخل رقم الهاتف</p>
                    </div>
                </div>

                <div>


                    <form method="POST" id="loginForm" class="max-w-sm mx-auto" onsubmit="return validateForm()">
                        <!-- Form fields -->
                        <div class="flex items-center">
                            <label for="msisdn" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">رقم
                                الهاتف:</label>
                            <div class="relative w-full">
                                <input type="text" id="msisdn" name="msisdn" aria-describedby="helper-text-explanation"
                                       dir="ltr"
                                       class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-e-0 border border-gray-300 focus:ring-black focus:border-black dark:bg-gray-700 dark:border-e-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-black text-left"
                                       pattern="9[1-4][0-9]{7}" maxlength="9" placeholder="9xxxxxxxx"
                                       inputmode="numeric" onkeydown="return onlyNumberKey(event)" required/>
                            </div>
                            <button id="dropdown-phone-button" data-dropdown-toggle="dropdown-phone"
                                    class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-e-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
                                    type="button">
                                +218
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <div id="dropdown-phone"
                                 class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-52 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdown-phone-button">
                                    <li>
                                        <button type="button"
                                                class="inline-flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white"
                                                role="menuitem">
                                            <div class="inline-flex items-center">
                                                ليبيا (+218)
                                            </div>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p id="helper-text-explanation" class="mt-2 mb-4 text-sm text-gray-500 dark:text-gray-400">سيتم
                            إرسال رسالة تأكيد لهاتفك.</p>
                        <button type="submit" id="cta_button"
                                class="flex flex-row items-center justify-center text-center w-full border rounded-xl outline-none py-5 border-none text-white text-2xl shadow-sm"
                                style="background-color: #000000">تسجيل
                        </button>

                        <div class="flex items-center mt-4 mb-4">
                            <input id="terms" aria-describedby="terms" type="checkbox"
                                   class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                                   required="">
                            <label for="terms" class="text-gray-500 dark:text-gray-300 ms-2 text-sm"><a
                                    class="font-medium text-black hover:underline dark:text-blue-500"
                                    href="{{ route('terms') }}">لقد قرأت
                                    وأوافق على شروط الخدمة عند النقر على تسجيل</a></label>
                        </div>
                    </form>
                    <div id="errorMessage" class="text-red-600 text-lg mt-2 max-w-sm mx-auto"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>

        function onlyNumberKey(evt) {
            const keyPressed = evt.key;
            const currentInput = evt.target;

            if (!isNaN(parseInt(keyPressed)) || keyPressed === 'Backspace' || keyPressed === 'Delete' || keyPressed === 'ArrowLeft' || keyPressed === 'ArrowRight') {
                return true;
            }

            evt.preventDefault();
        }

        function showLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        function hideLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function showErrorMessage(message) {
            document.getElementById('errorMessage').innerText = message;
        }

        function showSuccessMessage(message) {
            document.getElementById('successMessage').innerText = message;
        }


        function login(event) {
            event.preventDefault();
            showLoadingOverlay();
            let transaction_identify = localStorage.getItem('transaction_identify');
            let token = localStorage.getItem('token');

            // Check if token is available before making the request
            if (!token) {
                console.error("Token is missing.");
                return;
            }

            let msisdn = '218' + document.getElementById('msisdn').value;
            var vars = {
                msisdn: msisdn,
                device_type: 'android',
                transaction_identify: transaction_identify,
                otp_signature: "",
                token: token
            };
            $.ajax({
                url: @json($authUrl),
                type: "POST",
                data: vars,
                dataType: "json",
                success: function (data) {
                    hideLoadingOverlay()

                    window.location.href = '/web-confirm-login/' + msisdn;

                },
                error: function (xhr, status, error) {
                    hideLoadingOverlay()
                    if (JSON.parse(xhr.responseJSON?.error).messageCode == '300') {
                        Critical.postMessage(JSON.stringify({"error": true}));
                    } else {
                        Errors.postMessage(JSON.stringify({"message": 'حدث خطأ أثناء تسجيل الدخول. الرجاء المحاولة مرة أخرى'}));
                    }
                },
            })
        }

        function validateForm() {
            var checkBox = document.getElementById("terms");
            if (!checkBox.checked) {
                alert("يجب الموافقة على شروط الخدمة");
                return false;
            }
            return true;
        }
    </script>

    <script>
        $(document).ready(function () {
            showLoadingOverlay()
            var vars = {
                targeted_element: "#cta_button",
            };
            $.ajax({
                url: @json($projectUrl),
                type: "GET",
                data: vars,
                dataType: "json",
                success: function (data) {
                    hideLoadingOverlay()
                    localStorage.setItem('token', data['token']);
                    localStorage.setItem('transaction_identify', data['protect_data']['success']['transaction_identify']);

                    let dcbprotect = data['protect_data']['success']['dcbprotect'];
                    var script = document.createElement("script");
                    script.type = "text/javascript";
                    script.text = dcbprotect;
                    document.body.appendChild(script);
                    var ev = new Event('DCBProtectRun');
                    document.dispatchEvent(ev);

                },
                error: function (xhr, status, error) {
                    hideLoadingOverlay()
                    Loading.postMessage(JSON.stringify({"status": false}));
                }
            });
        })
        document.getElementById('loginForm').addEventListener('submit', login);
    </script>
@endpush


