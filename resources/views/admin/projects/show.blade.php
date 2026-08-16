@extends('layouts.app')

@section('title', $project->name . ' · Monstopia CMS')
@section('section', 'PROJECTS / DETAIL')

@section('content')
    <section class="page-intro"><div><span class="page-kicker">03 / STORY / DETAIL</span><h1 class="display-title"><em>{{ $project->name }}</em></h1><p class="page-lede">{{ $project->company?->name ?: 'Unassigned company' }} · {{ $project->category ?: 'Uncategorized' }}</p></div><div class="header-actions"><a class="button button-secondary" href="{{ route('admin.projects.edit', $project) }}">แก้ไขข้อมูล</a><form action="{{ route('admin.projects.destroy', $project) }}" method="POST" data-confirm-delete="โครงการนี้">@csrf @method('DELETE')<button class="button button-danger" type="submit">ลบโครงการ</button></form></div></section>

    <div class="detail-grid">
        <section class="detail-card">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap"><span class="status-badge status-{{ $project->status }}">{{ ucfirst($project->status) }}</span>@if ($project->featured)<span class="tag-chip">Featured signal</span>@endif</div>
            <h2 class="detail-title">{{ $project->summary ?: 'ยังไม่มีสรุปสั้นของโครงการ' }}</h2>
            <p class="detail-description">{{ $project->description ?: 'ยังไม่มีรายละเอียดโครงการ' }}</p>
            <div class="detail-meta-grid"><div class="detail-item"><span class="detail-label">Company</span><a class="detail-value" href="{{ $project->company ? route('admin.companies.show', $project->company) : '#' }}">{{ $project->company?->name ?: '—' }}</a></div><div class="detail-item"><span class="detail-label">Launch window</span><span class="detail-value">{{ optional($project->launch_start)->format('d M Y') ?: '—' }} @if($project->launch_end) — {{ $project->launch_end->format('d M Y') }} @endif</span></div><div class="detail-item"><span class="detail-label">Category</span><span class="detail-value">{{ $project->category ?: '—' }}</span></div><div class="detail-item"><span class="detail-label">Updated</span><span class="detail-value">{{ $project->updated_at->format('d M Y, H:i') }}</span></div></div>
            @if (count($project->tags ?? []))<div class="detail-links"><span class="detail-label">Tags</span><div style="display:flex;flex-wrap:wrap;gap:7px">@foreach ($project->tags as $tag)<span class="tag-chip">{{ $tag }}</span>@endforeach</div></div>@endif
        </section>

        <aside class="form-aside">
            <div class="aside-card"><span class="eyebrow">API RESOURCE</span><h3>Live JSON endpoint</h3><p>ข้อมูลโครงการนี้พร้อมใช้งานผ่าน REST API พร้อม company relation</p><div class="json-box">GET
/api/projects/{{ $project->id }}

status: {{ $project->status }}
partners: {{ count($project->partners ?? []) }}
sources: {{ count($project->sources ?? []) }}</div><a class="button button-primary button-small" href="{{ url('/api/projects/' . $project->id) }}" target="_blank" rel="noreferrer">เปิด JSON ↗</a></div>
            <div class="aside-card light"><span class="eyebrow">PARTNERS</span><h3>{{ count($project->partners ?? []) }} องค์กร</h3><div class="detail-links">@forelse ($project->partners ?? [] as $partner)<span class="detail-link"><span>{{ $partner }}</span><span>↗</span></span>@empty<span class="form-help">ยังไม่มีข้อมูล partner</span>@endforelse</div></div>
        </aside>
    </div>

    <section class="panel" style="margin-top:12px"><div class="section-heading"><div><h2 class="section-title">Source references</h2><p class="section-note">แหล่งข้อมูลที่บันทึกไว้ใน JSON ของโครงการ</p></div></div>@if (count($project->sources ?? []))<div class="detail-links">@foreach ($project->sources as $source)<a class="detail-link" href="{{ $source['url'] ?? '#' }}" target="_blank" rel="noreferrer"><span>{{ $source['label'] ?? 'Source' }}<small style="display:block;color:#929a93;font-family:var(--mono);font-size:10px;word-break:break-all">{{ $source['url'] ?? '' }}</small></span><span>↗</span></a>@endforeach</div>@else<div class="empty-state"><strong>ยังไม่มี source</strong><p>เพิ่ม URL อ้างอิงจากหน้าแก้ไขโครงการ</p></div>@endif</section>
@endsection
