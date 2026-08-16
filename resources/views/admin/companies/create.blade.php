@extends('layouts.app')

@section('title', 'เพิ่มบริษัท · Monstopia CMS')
@section('section', 'COMPANIES / NEW')

@section('content')
    <section class="page-intro">
        <div>
            <span class="page-kicker">02 / STRUCTURE / NEW</span>
            <h1 class="display-title">เพิ่ม <em>บริษัท</em></h1>
            <p class="page-lede">สร้าง profile ใหม่ให้พร้อมเชื่อมโยงกับโครงการและเสิร์ฟผ่าน JSON API</p>
        </div>
    </section>

    <div class="form-layout">
        <section class="panel">
            @include('admin.companies._form')
        </section>
        <aside class="form-aside">
            <div class="aside-card">
                <span class="eyebrow">RESPONSE SHAPE</span>
                <h3>ข้อมูลที่ชัดเจน</h3>
                <p>ทุกฟิลด์ในฟอร์มนี้จะกลายเป็นข้อมูล structured ที่ทีมอื่นนำไปใช้ต่อได้</p>
                <div class="json-box">{
  "data": {
    "name": "Monstopia",
    "slug": "monstopia",
    "published": true
  },
  "message": "..."
}</div>
            </div>
        </aside>
    </div>
@endsection
