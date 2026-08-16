@extends('layouts.app')

@section('title', 'แก้ไขบริษัท · Monstopia CMS')
@section('section', 'COMPANIES / EDIT')

@section('content')
    <section class="page-intro">
        <div>
            <span class="page-kicker">02 / STRUCTURE / EDIT</span>
            <h1 class="display-title">แก้ไข <em>{{ $company->name }}</em></h1>
            <p class="page-lede">ปรับข้อมูลให้ตรงกับ source ล่าสุด แล้วบันทึกเพื่ออัปเดตทั้ง CMS และ API</p>
        </div>
        <div class="header-actions"><a class="button button-secondary" href="{{ route('admin.companies.show', $company) }}">ดูรายละเอียด →</a></div>
    </section>

    <div class="form-layout">
        <section class="panel">
            @include('admin.companies._form')
        </section>
        <aside class="form-aside">
            <div class="aside-card light">
                <span class="eyebrow">LAST UPDATED</span>
                <h3>{{ $company->updated_at->format('d M Y') }}</h3>
                <p>การเปลี่ยนแปลงครั้งนี้จะสะท้อนใน <code>/api/companies/{{ $company->id }}</code> ทันที</p>
            </div>
        </aside>
    </div>
@endsection
