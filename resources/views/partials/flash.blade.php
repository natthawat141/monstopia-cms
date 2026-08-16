@if (session('success'))
    <div class="alert alert-success mb-6 shadow-sm" role="status"><span class="text-lg">✓</span><span>{{ session('success') }}</span></div>
@endif

@if ($errors->any())
    <div class="alert alert-error mb-6 items-start shadow-sm" role="alert"><span class="text-lg">!</span><div><div class="font-semibold">ตรวจสอบข้อมูลอีกครั้ง</div><ul class="mt-1 list-inside list-disc text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
@endif
