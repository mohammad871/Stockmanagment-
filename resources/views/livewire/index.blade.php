@section('title', "اصلی")
<div>
    <h1 class="text-center py-4 ">
        سلام (
        {{ Auth::user()->name }}
        ) به داشبورد (گدام / انبار) خوش آمدید
    </h1>
    <div class="w-95 overflow-hidden border mx-auto min-vh-75" style="border-radius: 15px; background-image: url({{ asset('images/bg.jpg') }}); background-size: cover; background-position: center; background-repeat: no-repeat">

    </div>
</div>
