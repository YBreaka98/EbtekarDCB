@extends('ebtekardcb::app')
@section('content')
    <div class="container mx-auto p-8" dir="rtl">
        <div class="flex justify-start py-6 pt-10 ">
            <button onclick="location.href='{{ route('login') }}'" class="flex items-center bg-transparent text-black font-bold rounded">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
        <h1 class="text-2xl font-bold mb-4">البنود والشروط</h1>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-black">تطبيق ChefTech</h2>
            <p class="mb-4">ChefTech هو تطبيق للبحث عن وصفات الأكل باستخدام أحدث تقنيات الذكاء الإصطناعي إعتمادا على
                المكونات المتوفرة لديك بالفعل، يعمل التطبيق على فهم متطلبات المستخدم من خلال تجميع البيانات ومن تم يقوم
                بإنشاء مجموعة متميزة من أشهر الوصفات العالمية. يتيح التطبيق للمستخدم تحديد أنواع المأكولات المفضلة وأيضا
                تحديد المطبخ الذي يرد معرفة الوصفة منه، على سبيل المثال (المطبخ الشرقي أو الآسيوي أو حتى تحديد بلد
                معين).</p>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-black">مزود الخدمة</h2>
            <p class="mb-4">شركة تكنو للحلول الذكية .</p>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-black">محتوى الخدمة</h2>
            <ul class="list-disc pl-6 mb-4">
                <li class="text-sm mb-2">يُولد وصفات بناءً على المكونات: أخبره بما لديك في المطبخ، وسيقترح عليك وصفات
                    شهية تستفيد من هذه المكونات.
                </li>
                <li class="text-sm mb-2">توصيات الوصفات المخصصة: كلما استخدمت ChefTech أكثر، كلما تمكنت من فهم تفضيلاتك
                    بشكل أفضل. يمكنك إخباره عن القيود الغذائية لديك، والحساسية، والمأكولات المفضلة، ومهارات الطبخ،
                    وسيوصي بالوصفات التي من المحتمل أن تستمتع بها.
                </li>
                <li class="text-sm mb-2">محرك بحث قوي: يمكنك البحث عن الوصفات حسب المكونات أو الكلمات الرئيسية.</li>
                <li class="text-sm mb-2">صندوق الوصفات الرقمي: يمكنك حفظ وصفاتك المفضلة في صندوق وصفات ChefTech الخاص
                    بك، حتى تتمكن من الوصول إليها بسهولة لاحقًا.
                </li>
            </ul>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-black">زمن الاستخدام</h2>
            <p class="mb-4">سيتم تحديد عدد معين من المحاولات يوميا وفقا لسماحية المزود.</p>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-black">الشروط العامة للخدمة</h2>
            <p class="mb-4">يمكن الخروج من الخدمة في أي وقت عبر الضغط على زر الخروج وهناك سيتم إلغاء البيانات في
                المفضلة.</p>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-black">للتواصل معنا</h2>
            <p class="mb-4">عبر ارسال رسالة الى البريد info@cheftech.ly</p>
        </div>

        <div>
            <p class="text-sm text-gray-600">تنويه عام: شركة أبل ومتجر بلاي غير مسؤولين أو داخلين في علاقة بالتطبيق.</p>
        </div>
    </div>
@endsection
