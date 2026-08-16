@extends('layouts.app')

@php
    $config = [
        'projects' => ['label' => 'Projects', 'thai' => 'ผลงาน', 'description' => 'จัดการผลงานและ case study ของ MONSTOPIA', 'icon' => '▧'],
        'articles' => ['label' => 'Articles', 'thai' => 'ข่าวสาร', 'description' => 'จัดการข่าวสาร บทความ และเนื้อหาประชาสัมพันธ์', 'icon' => '▤'],
        'services' => ['label' => 'Services', 'thai' => 'บริการ', 'description' => 'จัดการบริการหลักของบริษัท', 'icon' => '✦'],
        'team' => ['label' => 'Team members', 'thai' => 'ทีมงาน', 'description' => 'จัดการข้อมูลบุคลากรและทีมงาน', 'icon' => '♙'],
    ][$module];
@endphp

@section('title', $config['label'] . ' · Monstopia CMS')
@section('section', strtoupper($config['label']))

@section('content')
<div>
    <div class="mb-7 flex flex-col justify-between gap-5 md:flex-row md:items-end"><div><div class="mb-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.16em] text-primary"><span>{{ $config['icon'] }}</span>{{ $config['label'] }}</div><h1 class="text-3xl font-bold tracking-tight md:text-4xl">{{ $config['thai'] }}</h1><p class="mt-2 text-sm text-base-content/60">{{ $config['description'] }}</p></div><a class="btn btn-primary" href="{{ route('admin.content.create', $module) }}">เพิ่ม{{ $config['thai'] }} <span>＋</span></a></div>
    <div id="list-error" class="mb-5 hidden"></div>
    <section class="card overflow-hidden bg-base-100">
        <div class="flex flex-col gap-3 border-b border-base-300 p-4 md:flex-row md:items-center md:justify-between"><form data-list-filter class="flex flex-1 flex-col gap-2 sm:flex-row"><label class="input input-bordered flex flex-1 items-center gap-2"><span class="text-base-content/45">⌕</span><input id="list-search" type="search" placeholder="ค้นหา{{ $config['thai'] }}..." class="grow" autocomplete="off"></label><select id="list-status" class="select select-bordered w-full sm:w-44"><option value="">ทุกสถานะ</option><option value="published">Published</option><option value="draft">Draft</option><option value="archived">Archived</option><option value="active">Active</option><option value="inactive">Inactive</option></select><button class="btn btn-neutral" type="submit">ค้นหา</button></form><span id="list-meta" class="text-xs text-base-content/55">กำลังโหลด...</span></div>
        <div id="list-empty" class="hidden p-5"></div>
        <div class="table-wrap"><table data-content-table data-module="{{ $module }}" data-page="1" class="table table-zebra"><thead><tr><th>Loading</th></tr></thead><tbody><tr><td><div class="flex items-center gap-3 py-8"><span class="loading loading-spinner text-primary"></span>กำลังโหลดข้อมูลจาก API...</div></td></tr></tbody></table></div>
        <div class="flex flex-col gap-3 border-t border-base-300 p-4 sm:flex-row sm:items-center sm:justify-between"><span class="text-xs text-base-content/55">แสดงข้อมูลจาก REST API · JSON response</span><div id="list-pagination" class="join"></div></div>
    </section>
</div>
@endsection
