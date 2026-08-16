@extends('layouts.app')

@section('title', 'โครงการ · Monstopia CMS')
@section('section', 'PROJECTS')

@section('content')
    <section class="page-intro">
        <div>
            <span class="page-kicker">03 / STORY</span>
            <h1 class="display-title">โครงการ <em>เด่น</em></h1>
            <p class="page-lede">จัดการผลงาน โครงการร่วมพัฒนา และข้อมูลอ้างอิงที่ทำให้เรื่องราวของ Monstopia ตรวจสอบได้</p>
        </div>
        <div class="header-actions"><a class="button button-primary" href="{{ route('admin.projects.create') }}">เพิ่มโครงการ <span>＋</span></a></div>
    </section>

    <section class="panel">
        <div class="filter-bar">
            <form class="search-form" action="{{ route('admin.projects.index') }}" method="GET">
                <input class="input search-input" type="search" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อโครงการ หมวดหมู่ หรือคำอธิบาย..." aria-label="ค้นหาโครงการ">
                <select class="select" name="status" aria-label="กรองสถานะ"><option value="">ทุกสถานะ</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option><option value="archived" @selected(request('status') === 'archived')>Archived</option></select>
                <button class="button button-dark" type="submit">กรอง</button>
            </form>
            @if (request('search') || request('status'))<a class="button button-plain" href="{{ route('admin.projects.index') }}">ล้างตัวกรอง</a>@endif
        </div>

        @if ($projects->isEmpty())
            <div class="empty-state"><strong>{{ request('search') || request('status') ? 'ไม่พบโครงการที่ตรงกัน' : 'ยังไม่มีข้อมูลโครงการ' }}</strong><p>{{ request('search') || request('status') ? 'ลองเปลี่ยนเงื่อนไขแล้วค้นหาอีกครั้ง' : 'เพิ่ม BullMoonJR หรือโครงการอื่นเพื่อเริ่มจัดการเนื้อหา' }}</p>@if (!request('search') && !request('status'))<a class="button button-primary" href="{{ route('admin.projects.create') }}">เพิ่มโครงการ</a>@endif</div>
        @else
            <div class="table-wrap"><table class="data-table"><thead><tr><th>Project</th><th>Owner</th><th>Category</th><th>Status</th><th>Featured</th><th>Updated</th><th></th></tr></thead><tbody>
                @foreach ($projects as $project)
                    <tr><td><a class="row-link" href="{{ route('admin.projects.show', $project) }}"><strong>{{ $project->name }}</strong><small>{{ $project->slug }}</small></a></td><td>{{ $project->company?->name ?: '—' }}</td><td>{{ $project->category ?: '—' }}</td><td><span class="status-badge status-{{ $project->status }}">{{ ucfirst($project->status) }}</span></td><td>@if ($project->featured)<span class="tag-chip">Featured</span>@else<span style="color:#a0a7a0">—</span>@endif</td><td>{{ $project->updated_at->format('d M Y') }}</td><td><a class="button button-plain" href="{{ route('admin.projects.edit', $project) }}">แก้ไข</a></td></tr>
                @endforeach
            </tbody></table></div>
            @include('partials.pagination', ['paginator' => $projects])
        @endif
    </section>
@endsection
