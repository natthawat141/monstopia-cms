@extends('layouts.app')

@section('title', 'บริษัท · Monstopia CMS')
@section('section', 'COMPANIES')

@section('content')
    <section class="page-intro">
        <div>
            <span class="page-kicker">02 / STRUCTURE</span>
            <h1 class="display-title">รายชื่อ <em>บริษัท</em></h1>
            <p class="page-lede">จัดการ profile บริษัทที่เป็นเจ้าของโครงการและข้อมูลที่เปิดให้ API เข้าถึง</p>
        </div>
        <div class="header-actions"><a class="button button-primary" href="{{ route('admin.companies.create') }}">เพิ่มบริษัท <span>＋</span></a></div>
    </section>

    <section class="panel">
        <div class="filter-bar">
            <form class="search-form" action="{{ route('admin.companies.index') }}" method="GET">
                <input class="input search-input" type="search" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อบริษัท จังหวัด หรือประเภทธุรกิจ..." aria-label="ค้นหาบริษัท">
                <button class="button button-dark" type="submit">ค้นหา</button>
            </form>
            @if (request('search'))
                <a class="button button-plain" href="{{ route('admin.companies.index') }}">ล้างตัวกรอง</a>
            @endif
        </div>

        @if ($companies->isEmpty())
            <div class="empty-state">
                <strong>{{ request('search') ? 'ไม่พบข้อมูลที่ตรงกัน' : 'ยังไม่มีข้อมูลบริษัท' }}</strong>
                <p>{{ request('search') ? 'ลองเปลี่ยนคำค้นแล้วค้นหาอีกครั้ง' : 'สร้าง company profile แรกเพื่อเชื่อมกับโครงการในระบบ' }}</p>
                @if (!request('search'))<a class="button button-primary" href="{{ route('admin.companies.create') }}">เพิ่มบริษัท</a>@endif
            </div>
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Company</th><th>Province</th><th>Business type</th><th>Projects</th><th>Visibility</th><th>Updated</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <td><a class="row-link" href="{{ route('admin.companies.show', $company) }}"><strong>{{ $company->name }}</strong><small>{{ $company->legal_name ?: $company->slug }}</small></a></td>
                                <td>{{ $company->province ?: '—' }}</td>
                                <td>{{ $company->business_type ?: '—' }}</td>
                                <td>{{ $company->projects_count }}</td>
                                <td><span class="status-badge {{ $company->published ? 'status-published' : 'status-draft' }}">{{ $company->published ? 'Published' : 'Hidden' }}</span></td>
                                <td>{{ $company->updated_at->format('d M Y') }}</td>
                                <td><a class="button button-plain" href="{{ route('admin.companies.edit', $company) }}">แก้ไข</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('partials.pagination', ['paginator' => $companies])
        @endif
    </section>
@endsection
