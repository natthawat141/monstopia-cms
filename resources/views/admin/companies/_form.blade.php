@php($isEdit = $company->exists)
<form action="{{ $isEdit ? route('admin.companies.update', $company) : route('admin.companies.store') }}" method="POST">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="form-grid">
        <div class="form-field full">
            <label for="name">ชื่อที่แสดง <span class="required">*</span></label>
            <input class="input" id="name" name="name" type="text" required value="{{ old('name', $company->name) }}" placeholder="เช่น Monstopia">
            <span class="form-help">ชื่อที่ใช้แสดงในหน้า CMS และ JSON API</span>
        </div>
        <div class="form-field">
            <label for="legal_name">ชื่อนิติบุคคล</label>
            <input class="input" id="legal_name" name="legal_name" type="text" value="{{ old('legal_name', $company->legal_name) }}" placeholder="เช่น Monstopia Company Limited">
        </div>
        <div class="form-field">
            <label for="slug">Slug <span class="required">*</span></label>
            <input class="input" id="slug" name="slug" type="text" required value="{{ old('slug', $company->slug) }}" placeholder="monstopia">
            <span class="form-help">ต้องไม่ซ้ำกัน ใช้เป็น identifier ในระบบ</span>
        </div>
        <div class="form-field">
            <label for="registration_number">เลขทะเบียนนิติบุคคล</label>
            <input class="input" id="registration_number" name="registration_number" type="text" value="{{ old('registration_number', $company->registration_number) }}" placeholder="ถ้ามี">
        </div>
        <div class="form-field">
            <label for="registered_at">วันที่จดทะเบียน</label>
            <input class="input" id="registered_at" name="registered_at" type="date" value="{{ old('registered_at', optional($company->registered_at)->format('Y-m-d')) }}">
        </div>
        <div class="form-field">
            <label for="province">จังหวัด</label>
            <input class="input" id="province" name="province" type="text" value="{{ old('province', $company->province) }}" placeholder="เช่น ปทุมธานี">
        </div>
        <div class="form-field">
            <label for="business_type">ประเภทธุรกิจ</label>
            <input class="input" id="business_type" name="business_type" type="text" value="{{ old('business_type', $company->business_type) }}" placeholder="กิจกรรมบริการเทคโนโลยีสารสนเทศ..."><span class="form-help">เขียนให้กระชับและค้นหาได้ง่าย</span>
        </div>
        <div class="form-field">
            <label for="website_url">เว็บไซต์หรือ profile URL</label>
            <input class="input" id="website_url" name="website_url" type="url" value="{{ old('website_url', $company->website_url) }}" placeholder="https://example.com">
        </div>
        <div class="form-field full">
            <label for="description">คำอธิบายบริษัท</label>
            <textarea class="textarea" id="description" name="description" placeholder="เล่าอย่างชัดเจนว่าบริษัททำอะไร...">{{ old('description', $company->description) }}</textarea>
        </div>
        <div class="form-field full">
            <label class="check-field" for="published">
                <input id="published" name="published" type="checkbox" value="1" @checked(old('published', $company->published))>
                <span>เผยแพร่ข้อมูลบริษัทผ่าน API</span>
            </label>
        </div>
    </div>

    <div class="form-actions">
        <span class="form-help">ฟิลด์ที่มีเครื่องหมาย <span class="required">*</span> จำเป็นต้องกรอก</span>
        <div class="form-actions-right">
            <a class="button button-secondary" href="{{ $isEdit ? route('admin.companies.show', $company) : route('admin.companies.index') }}">ยกเลิก</a>
            <button class="button button-primary" type="submit">{{ $isEdit ? 'บันทึกการแก้ไข' : 'สร้างข้อมูลบริษัท' }} <span>→</span></button>
        </div>
    </div>
</form>
