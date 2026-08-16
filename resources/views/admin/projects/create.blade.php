@extends('layouts.app')

@section('title', 'เพิ่มโครงการ · Monstopia CMS')
@section('section', 'PROJECTS / NEW')

@section('content')
    <section class="page-intro"><div><span class="page-kicker">03 / STORY / NEW</span><h1 class="display-title">เพิ่ม <em>โครงการ</em></h1><p class="page-lede">บันทึกผลงานของบริษัทให้ครบทั้งเรื่องราว พาร์ตเนอร์ และ source ที่ตรวจสอบได้</p></div></section>
    <div class="form-layout"><section class="panel">@include('admin.projects._form')</section><aside class="form-aside"><div class="aside-card"><span class="eyebrow">PROJECT RESOURCE</span><h3>Structured story</h3><p>รายละเอียดไม่ได้ถูกเก็บแค่ในหน้าเว็บ แต่พร้อมส่งต่อเป็น JSON ให้ consumer นำไป render ได้หลายรูปแบบ</p><div class="json-box">{
  "name": "BullMoonJR NFT",
  "partners": [],
  "sources": [],
  "featured": true
}</div></div><div class="aside-card light"><span class="eyebrow">HARDEN</span><h3>ตรวจสอบก่อน publish</h3><p>เพิ่ม source URL และเลือกสถานะให้ชัด เพื่อให้ข้อมูลสาธารณะมีบริบทครบถ้วน</p></div></aside></div>
@endsection
