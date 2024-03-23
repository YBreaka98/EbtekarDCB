@extends('ebtekardcb::app')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white p-8 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-4">الصفحة الشخصية</h1>

            <p class="mb-2"><strong>رقم الهاتف:</strong> {{ $subscriber['success']['msisdn'] }}</p>

            <p class="mb-2"><strong>تاريخ انتهاء
                    الإشتراك:</strong> {{ $subscriber['success']['details']['expiration_date'] }}</p>

            <p class="mb-4"><strong>إسم الإشتراك:</strong> {{ $subscriber['success']['details']['subscription_name'] }}
            </p>

            @if ($subscriber['success']['details']['status'] === 'active')
                <form method="POST">
                    @csrf
                    <input type="hidden" name="msisdn" value="{{ $subscriber['success']['msisdn'] }}">
                    <button type="submit"
                            onclick="unsubscribe(event)"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        إلغاء الإشتراك
                    </button>
                </form>
            @else
                <p>الإشتراك غير مفعل</p>
            @endif

{{--            <form method="POST">--}}
{{--                @csrf--}}
{{--                <input type="hidden" name="msisdn" value="{{ $subscriber['success']['msisdn'] }}">--}}
{{--                <button type="submit"--}}
{{--                        onclick="unsubscribe(event)"--}}
{{--                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">--}}
{{--                    Unsubscribe--}}
{{--                </button>--}}
{{--            </form>--}}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        function unsubscribe(event) {
            event.preventDefault();

            var msisdn = event.target.form.querySelector('input[name="msisdn"]').value;

            var vars = {
                msisdn: msisdn,
            };
           $.ajax({
                url: @json($url),
                type: "POST",
                data: vars,
                dataType: "json",
                success: function (data) {
                    window.location.href = '/unsubscribe-confirm/' + msisdn;
                    Toaster.postMessage(JSON.stringify(data));
                },
                error: function (xhr, status, error) {
                    Toaster.postMessage(JSON.stringify(xhr.responseText));
                },
            })
        }

    </script>

@endpush
