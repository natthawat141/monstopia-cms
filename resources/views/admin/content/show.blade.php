@extends('layouts.app')

@php
    $labels = ['projects' => ['label' => 'Project', 'thai' => 'ผลงาน'], 'articles' => ['label' => 'Article', 'thai' => 'ข่าวสาร'], 'services' => ['label' => 'Service', 'thai' => 'บริการ'], 'team' => ['label' => 'Team member', 'thai' => 'ทีมงาน']][$module];
@endphp

@section('title', $labels['label'] . ' detail · Monstopia CMS')
@section('section', strtoupper($labels['label']) . ' / DETAIL')

@section('content')
<div data-content-detail data-module="{{ $module }}" data-id="{{ $resourceId }}">
    <div class="mb-7 flex flex-col justify-between gap-5 md:flex-row md:items-end"><div><div class="mb-2 text-sm font-semibold uppercase tracking-[0.16em] text-primary">{{ $labels['label'] }} / detail</div><h1 id="detail-title" class="text-3xl font-bold tracking-tight md:text-4xl">กำลังโหลด...</h1><p id="detail-subtitle" class="mt-2 text-sm text-base-content/60"></p></div><div class="flex gap-2"><a class="btn btn-ghost" href="{{ route('admin.content.index', $module) }}">← กลับรายการ</a><a class="btn btn-outline" href="{{ route('admin.content.edit', [$module, $resourceId]) }}">แก้ไข</a><button class="btn btn-error" data-delete-module="{{ $module }}" data-id="{{ $resourceId }}" data-label="รายการนี้">ลบ</button></div></div>
    <div id="detail-error" class="mb-5 hidden"></div>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]"><section class="card bg-base-100"><div class="card-body"><div data-detail-loading class="alert alert-info text-sm"><span class="loading loading-spinner loading-sm"></span>กำลังโหลดข้อมูลจาก API...</div><div id="detail-status" class="mb-4"></div><div id="detail-body"></div></div></section><aside class="card h-fit bg-base-100"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-[0.16em] text-base-content/45">Resource endpoint</div><code class="mt-2 break-all text-sm text-primary">GET /api/{{ $module === 'team' ? 'team-members' : $module }}/{{ $resourceId }}</code><div class="divider"></div><p class="text-sm text-base-content/60">ข้อมูลหน้านี้ถูกดึงจาก JSON API แล้ว render ใน Blade shell ด้วย JavaScript</p><a class="btn btn-sm btn-outline mt-2" target="_blank" href="{{ url('/api/' . ($module === 'team' ? 'team-members' : $module) . '/' . $resourceId) }}">เปิด JSON ↗</a></div></aside></div>
</div>
@endsection
