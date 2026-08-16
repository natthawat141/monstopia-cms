@extends('layouts.app')

@section('title', 'แก้ไขโครงการ · Monstopia CMS')
@section('section', 'PROJECTS / EDIT')

@section('content')
    <section class="page-intro"><div><span class="page-kicker">03 / STORY / EDIT</span><h1 class="display-title">แก้ไข <em>{{ $project->name }}</em></h1><p class="page-lede">ปรับรายละเอียด story, partner และ source แล้วบันทึกให้ข้อมูล API เป็นชุดล่าสุด</p></div><div class="header-actions"><a class="button button-secondary" href="{{ route('admin.projects.show', $project) }}">ดูรายละเอียด →</a></div></section>
    <div class="form-layout"><section class="panel">@include('admin.projects._form')</section><aside class="form-aside"><div class="aside-card light"><span class="eyebrow">LIVE RESOURCE</span><h3>Project #{{ $project->id }}</h3><p>ข้อมูลนี้เชื่อมกับ {{ $project->company?->name ?: 'ยังไม่ระบุบริษัท' }} และจะถูกอัปเดตทันทีหลังบันทึก</p><a class="button button-dark button-small" href="{{ url('/api/projects/' . $project->id) }}" target="_blank" rel="noreferrer">เปิด JSON ↗</a></div></aside></div>
@endsection
