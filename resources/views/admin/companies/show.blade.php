@extends('layouts.app')

@section('title', $company->name . ' · Monstopia CMS')
@section('section', 'COMPANIES / DETAIL')

@section('content')
    <section class="page-intro">
        <div>
            <span class="page-kicker">02 / STRUCTURE / DETAIL</span>
            <h1 class="display-title"><em>{{ $company->name }}</em></h1>
            <p class="page-lede">{{ $company->legal_name ?: 'Company profile' }} · {{ $company->province ?: 'Location not set' }}</p>
        </div>
        <div class="header-actions">
            <a class="button button-secondary" href="{{ route('admin.companies.edit', $company) }}">แก้ไขข้อมูล</a>
            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" data-confirm-delete="บริษัทนี้">
                @csrf @method('DELETE')
                <button class="button button-danger" type="submit">ลบบริษัท</button>
            </form>
        </div>
    </section>

    <div class="detail-grid">
        <section class="detail-card">
            <span class="status-badge {{ $company->published ? 'status-published' : 'status-draft' }}">{{ $company->published ? 'Published to API' : 'Hidden from API' }}</span>
            <h2 class="detail-title">{{ $company->legal_name ?: $company->name }}</h2>
            <p class="detail-description">{{ $company->description ?: 'ยังไม่มีคำอธิบายบริษัท' }}</p>
            <div class="detail-meta-grid">
                <div class="detail-item"><span class="detail-label">Registered</span><span class="detail-value">{{ optional($company->registered_at)->format('d M Y') ?: '—' }}</span></div>
                <div class="detail-item"><span class="detail-label">Province</span><span class="detail-value">{{ $company->province ?: '—' }}</span></div>
                <div class="detail-item"><span class="detail-label">Business type</span><span class="detail-value">{{ $company->business_type ?: '—' }}</span></div>
                <div class="detail-item"><span class="detail-label">Registration no.</span><span class="detail-value">{{ $company->registration_number ?: '—' }}</span></div>
            </div>
            @if ($company->website_url)
                <div class="detail-links"><a class="detail-link" href="{{ $company->website_url }}" target="_blank" rel="noreferrer"><span>Website / profile</span><span>↗</span></a></div>
            @endif
        </section>

        <aside class="form-aside">
            <div class="aside-card">
                <span class="eyebrow">API RESOURCE</span>
                <h3>Live JSON endpoint</h3>
                <p>ข้อมูลชุดนี้พร้อมให้ consumer เรียกใช้จาก REST API โดยตรง</p>
                <div class="json-box">GET
/api/companies/{{ $company->id }}

projects_count: {{ $company->projects->count() }}
status: {{ $company->published ? 'published' : 'hidden' }}</div>
                <a class="button button-primary button-small" href="{{ url('/api/companies/' . $company->id) }}" target="_blank" rel="noreferrer">เปิด JSON ↗</a>
            </div>
            <div class="aside-card light">
                <span class="eyebrow">CONNECTED PROJECTS</span>
                <h3>{{ $company->projects->count() }} โครงการ</h3>
                <p>โครงการที่ระบุบริษัทนี้เป็น owner ในฐานข้อมูล</p>
                <a class="button button-dark button-small" href="{{ route('admin.projects.create', ['company_id' => $company->id]) }}">เพิ่มโครงการ →</a>
            </div>
        </aside>
    </div>

    <section class="panel" style="margin-top: 12px">
        <div class="section-heading"><div><h2 class="section-title">โครงการที่เชื่อมโยง</h2><p class="section-note">รายการที่อยู่ภายใต้ company profile นี้</p></div></div>
        @if ($company->projects->isEmpty())
            <div class="empty-state"><strong>ยังไม่มีโครงการ</strong><p>เพิ่มโครงการเพื่อทำให้ profile นี้มีเนื้อหามากขึ้น</p></div>
        @else
            <div class="table-wrap"><table class="data-table"><thead><tr><th>Project</th><th>Category</th><th>Status</th><th>Launch</th><th></th></tr></thead><tbody>
                @foreach ($company->projects as $project)
                    <tr><td><a class="row-link" href="{{ route('admin.projects.show', $project) }}"><strong>{{ $project->name }}</strong><small>{{ $project->slug }}</small></a></td><td>{{ $project->category ?: '—' }}</td><td><span class="status-badge status-{{ $project->status }}">{{ ucfirst($project->status) }}</span></td><td>{{ optional($project->launch_start)->format('d M Y') ?: '—' }}</td><td><a class="button button-plain" href="{{ route('admin.projects.edit', $project) }}">แก้ไข</a></td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
@endsection
