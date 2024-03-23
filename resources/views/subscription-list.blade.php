@extends('ebtekardcb::app')
@section('content')
    <div class="container mx-auto">
        <div class="bg-[#E5E5E5] min-h-screen p-6 mx-4 md:p-0 space-y-8">

            <div class="flex flex-col items-center justify-center text-center space-y-8">
                <img src="{{ asset('images/logo.svg') }}" alt="Your Logo" class="w-30 h-30">
                <div class="font-semibold text-3xl">
                    <p>الإشتراكات</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($subscriptions as $subscription)
                    <div class="bg-[#F4F5FA] rounded-xl shadow-[0px 1px 2px #E1E3E5] border border-[#E1E3E5]">
                        <div class="p-4">
                            <div>
                                <p class="text-[#00153B] text-base font-bold mb-2">
                                    {{ $subscription['name'] }}
                                    @if($subscription['name'] === $current_subscription)
                                        <span class="text-green-500"> (الإشتراك الحالي) </span>
                                    @endif
                                </p>
                                <p class="text-[#00153B] text-lg font-bold mb-4">
                                    {{ $subscription['product']['cost']  . ' دينار'}}
                                </p>
                                @if($subscription['name'] === $current_subscription)
                                    <span class="text-black"> تاريخ انتهاء الإشتراك :  </span>
                                    <span class="text-black"> {{ \Carbon\Carbon::createFromDate($expiration_date)->format('H:i:s') }} </span>
                                    <span class="text-black"> {{ \Carbon\Carbon::createFromDate($expiration_date)->format('y-m-d') }} </span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <form method="POST">
                                        @csrf
                                        <input type="hidden" name="msisdn" value="{{ $msisdn }}">
                                        <input type="hidden" name="uuid" value="{{ $subscription['uuid'] }}">
                                        <button type="submit"
                                                onclick="subscribe(event)"
                                                {{ $subscription['name'] === $current_subscription ? 'disabled' : ''}}
                                                class="rounded-[5px] py-2 px-4 text-[#fff] text-sm md:text-base font-semibold {{ $subscription['name'] === $current_subscription ? 'bg-gray-400 cursor-not-allowed' : 'bg-black' }}">
                                            اشتراك
                                        </button>
                                    </form>
                                </div>

                                @if($subscription['name'] === $current_subscription && $current_subscription_status !== 'active')
                                    <div>
                                        <form method="POST">
                                            @csrf
                                            <input type="hidden" name="msisdn" value="{{ $msisdn }}">
                                            <button type="submit"
                                                    onclick="subscriptionActivation(event)"
                                                    class="bg-black rounded-[5px] py-2 px-4 text-[#fff] text-sm md:text-base font-semibold">
                                                تفعيل الإشتراك
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        function subscribe(event) {
            event.preventDefault();

            var msisdn = event.target.form.querySelector('input[name="msisdn"]').value;
            var uuid = event.target.form.querySelector('input[name="uuid"]').value;

            var vars = {
                msisdn: msisdn,
                uuid: uuid,
            };

           $.ajax({
                url: @json($url),
                type: "POST",
                data: vars,
                dataType: "json",

                success: function (data) {
                    window.location.href = '/confirm-upgrade/' + msisdn + '/' + uuid;
                },
                error: function (xhr, status, error) {

                },
            })
        }

        function subscriptionActivation(event) {
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
                    window.location.href = '/subscription-activation-confirm/' + msisdn;
                },
                error: function (xhr, status, error) {

                },
            })
        }

    </script>

@endpush
