<!doctype html>
<html lang="th" data-theme="monstopia">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Monstopia CMS')</title>
    <meta name="description" content="MONSTOPIA COMPANY LIMITED content management system">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <div class="drawer lg:drawer-open">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle">
        <div class="drawer-content min-w-0">
            <div class="navbar sticky top-0 z-20 border-b border-base-300 bg-base-100/95 px-4 backdrop-blur lg:px-8">
                <div class="flex-none lg:hidden"><label for="admin-drawer" class="btn btn-square btn-ghost" aria-label="เปิดเมนู">☰</label></div>
                <div class="flex-1"><div class="breadcrumbs hidden text-sm text-base-content/60 md:block"><ul><li>MONSTOPIA</li><li>@yield('section', 'WORKSPACE')</li></ul></div><span class="font-semibold md:hidden">MONSTOPIA CMS</span></div>
                <div class="flex-none gap-2">
                    <div class="hidden items-center gap-2 text-xs text-base-content/55 sm:flex"><span class="h-2 w-2 rounded-full bg-success"></span>ระบบพร้อมใช้งาน</div>
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" class="btn btn-ghost btn-sm gap-2"><span class="hidden font-medium sm:inline">{{ auth()->user()->name }}</span><span class="grid h-8 w-8 place-items-center rounded-full bg-primary font-semibold text-primary-content">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span></button>
                        <ul tabindex="0" class="menu dropdown-content z-[1] mt-3 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                            <li class="menu-title"><span>{{ auth()->user()->email }}</span><span class="badge badge-ghost badge-sm mt-1">{{ ucfirst(auth()->user()->role) }}</span></li>
                            <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">ออกจากระบบ</button></form></li>
                        </ul>
                    </div>
                </div>
            </div>

            <main id="app-main" class="mx-auto min-h-[calc(100vh-4rem)] w-full max-w-[1440px] p-4 md:p-8" data-api-base="{{ url('/api') }}" data-csrf="{{ csrf_token() }}">
                @include('partials.flash')
                @yield('content')
            </main>

            <footer class="flex flex-col gap-2 border-t border-base-300 bg-base-100 px-4 py-5 text-xs text-base-content/50 md:flex-row md:items-center md:justify-between md:px-8">
                <span>MONSTOPIA COMPANY LIMITED · CONTENT MANAGEMENT SYSTEM</span><span>Laravel · Blade · REST API</span>
            </footer>
        </div>

        <div class="drawer-side z-30">
            <label for="admin-drawer" aria-label="ปิดเมนู" class="drawer-overlay"></label>
            <aside class="flex min-h-full w-72 flex-col border-r border-base-300 bg-base-100">
                <div class="flex h-16 items-center gap-3 border-b border-base-300 px-6">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary text-lg font-bold text-primary-content">M</span>
                    <div><div class="font-bold tracking-[0.18em] text-base-content">MONSTOPIA</div><div class="font-mono text-[10px] uppercase tracking-wider text-base-content/45">Company CMS / v1</div></div>
                </div>
                <div class="px-4 py-5"><p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-base-content/45">Workspace</p><ul class="menu gap-1 p-0">
                    <li><a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="text-base-content/50">▦</span>ภาพรวม</a></li>
                    <li><a class="{{ request()->is('admin/projects*') ? 'active' : '' }}" href="{{ route('admin.content.index', 'projects') }}"><span class="text-base-content/50">▧</span>ผลงาน / Projects</a></li>
                    <li><a class="{{ request()->is('admin/articles*') ? 'active' : '' }}" href="{{ route('admin.content.index', 'articles') }}"><span class="text-base-content/50">▤</span>ข่าวสาร / Articles</a></li>
                    <li><a class="{{ request()->is('admin/services*') ? 'active' : '' }}" href="{{ route('admin.content.index', 'services') }}"><span class="text-base-content/50">✦</span>บริการ / Services</a></li>
                    <li><a class="{{ request()->is('admin/team*') ? 'active' : '' }}" href="{{ route('admin.content.index', 'team') }}"><span class="text-base-content/50">♙</span>ทีมงาน / Team</a></li>
                </ul></div>
                <div class="mt-auto border-t border-base-300 p-4"><p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-base-content/45">API resources</p><div class="space-y-1 px-3 font-mono text-[11px] text-base-content/55"><a class="block hover:text-primary" href="{{ url('/api/projects') }}" target="_blank">GET /api/projects ↗</a><a class="block hover:text-primary" href="{{ url('/api/articles') }}" target="_blank">GET /api/articles ↗</a><a class="block hover:text-primary" href="{{ url('/api/services') }}" target="_blank">GET /api/services ↗</a><a class="block hover:text-primary" href="{{ url('/api/team-members') }}" target="_blank">GET /api/team-members ↗</a></div><div class="mt-5 px-3 text-xs text-base-content/40">{{ now()->format('d M Y') }}</div></div>
            </aside>
        </div>
    </div>

    <div id="toast-stack" class="toast toast-end toast-bottom z-50"></div>
    <dialog id="confirm-modal" class="modal">
        <div class="modal-box">
            <h3 id="confirm-title" class="text-lg font-bold">ยืนยันการลบ</h3>
            <p id="confirm-message" class="py-4 text-sm text-base-content/70"></p>
            <div class="modal-action"><button type="button" class="btn btn-ghost" data-confirm-cancel>ยกเลิก</button><button type="button" class="btn btn-error" data-confirm-submit>ลบข้อมูล</button></div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>ปิด</button></form>
    </dialog>
    @stack('scripts')
</body>
</html>
