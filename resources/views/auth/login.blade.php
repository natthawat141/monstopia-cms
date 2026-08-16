<!doctype html>
<html lang="th" data-theme="monstopia">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>เข้าสู่ระบบ · Monstopia CMS</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200">
    <div class="grid min-h-screen lg:grid-cols-[1.05fr_.95fr]">
        <section class="hidden bg-neutral p-12 text-neutral-content lg:flex lg:flex-col lg:justify-between xl:p-20">
            <div class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-lg bg-primary text-xl font-bold text-primary-content">M</span><div><div class="font-bold tracking-[0.2em]">MONSTOPIA</div><div class="font-mono text-[10px] uppercase tracking-widest text-neutral-content/50">Company CMS</div></div></div>
            <div class="max-w-xl"><div class="mb-5 text-sm font-semibold uppercase tracking-[0.18em] text-primary">Content management system</div><h1 class="text-5xl font-semibold leading-tight tracking-tight xl:text-6xl">ดูแลเนื้อหาบริษัทให้พร้อมใช้งานทุกช่องทาง</h1><p class="mt-6 max-w-lg text-lg leading-relaxed text-neutral-content/65">พื้นที่ทำงานสำหรับจัดการผลงาน ข่าวสาร บริการ และทีมงาน ผ่านระบบที่เชื่อมต่อกับ REST API โดยตรง</p></div>
            <div class="flex gap-8 text-xs text-neutral-content/45"><span>Laravel + Blade</span><span>REST API</span><span>MySQL ready</span></div>
        </section>
        <main class="flex items-center justify-center p-6 sm:p-10"><div class="w-full max-w-md">
            <div class="mb-10 lg:hidden"><div class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-lg bg-primary text-xl font-bold text-primary-content">M</span><span class="font-bold tracking-[0.2em]">MONSTOPIA</span></div></div>
            @if (session('success'))<div class="alert alert-success mb-5"><span>✓</span><span>{{ session('success') }}</span></div>@endif
            @if ($errors->any())<div class="alert alert-error mb-5 items-start"><span>!</span><div><div class="font-semibold">เข้าสู่ระบบไม่สำเร็จ</div><ul class="list-inside list-disc text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>@endif
            <div class="mb-8"><div class="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Admin access</div><h2 class="mt-3 text-3xl font-bold tracking-tight">ยินดีต้อนรับกลับ</h2><p class="mt-2 text-sm text-base-content/60">เข้าสู่ระบบเพื่อจัดการข้อมูลของ MONSTOPIA COMPANY LIMITED</p></div>
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5"><input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="form-control"><label class="label" for="email"><span class="label-text">อีเมล</span></label><input class="input input-bordered w-full" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="admin@monstopia.co.th" required autofocus></div><div class="form-control"><label class="label" for="password"><span class="label-text">รหัสผ่าน</span></label><input class="input input-bordered w-full" id="password" name="password" type="password" placeholder="••••••••" required></div><label class="label cursor-pointer justify-start gap-3 px-0"><input class="checkbox checkbox-primary checkbox-sm" name="remember" type="checkbox" value="1"><span class="label-text">จำการเข้าสู่ระบบ</span></label><button class="btn btn-primary w-full" type="submit">เข้าสู่ระบบ <span>→</span></button></form>
            <p class="mt-8 text-center text-xs text-base-content/45">ระบบสำหรับผู้ดูแลและผู้แก้ไขเนื้อหาเท่านั้น</p>
        </div></main>
    </div>
</body>
</html>
