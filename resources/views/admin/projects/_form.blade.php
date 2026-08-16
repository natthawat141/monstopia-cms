@php($isEdit = $project->exists)
@php($partners = old('partners', $project->partners ?: ['']))
@php($tags = old('tags', $project->tags ?: ['']))
@php($sources = old('sources', $project->sources ?: [['label' => '', 'url' => '']]))
<form action="{{ $isEdit ? route('admin.projects.update', $project) : route('admin.projects.store') }}" method="POST">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="form-grid">
        <div class="form-field full"><label for="name">ชื่อโครงการ <span class="required">*</span></label><input class="input" id="name" name="name" type="text" required value="{{ old('name', $project->name) }}" placeholder="เช่น BullMoonJR NFT"></div>
        <div class="form-field"><label for="slug">Slug <span class="required">*</span></label><input class="input" id="slug" name="slug" type="text" required value="{{ old('slug', $project->slug) }}" placeholder="bullmoonjr-nft"><span class="form-help">identifier ที่ไม่ซ้ำกัน</span></div>
        <div class="form-field"><label for="company_id">บริษัทเจ้าของโครงการ <span class="required">*</span></label><select class="select" id="company_id" name="company_id" required><option value="">เลือกบริษัท</option>@foreach ($companies as $companyOption)<option value="{{ $companyOption->id }}" @selected((string) old('company_id', $project->company_id) === (string) $companyOption->id)>{{ $companyOption->name }}</option>@endforeach</select></div>
        <div class="form-field"><label for="category">หมวดหมู่</label><input class="input" id="category" name="category" type="text" value="{{ old('category', $project->category) }}" placeholder="NFT / Education / Blockchain"></div>
        <div class="form-field"><label for="status">สถานะ <span class="required">*</span></label><select class="select" id="status" name="status" required><option value="published" @selected(old('status', $project->status) === 'published')>Published</option><option value="draft" @selected(old('status', $project->status) === 'draft')>Draft</option><option value="archived" @selected(old('status', $project->status) === 'archived')>Archived</option></select></div>
        <div class="form-field"><label class="check-field" for="featured"><input id="featured" name="featured" type="checkbox" value="1" @checked(old('featured', $project->featured))><span>ทำเครื่องหมายเป็นโครงการเด่น</span></label><span class="form-help">ใช้เพื่อยกขึ้นเป็น featured signal ใน dashboard</span></div>
        <div class="form-field full"><label for="summary">สรุปสั้น</label><textarea class="textarea" style="min-height:90px" id="summary" name="summary" maxlength="1000" placeholder="อธิบายโครงการใน 1–3 ประโยค">{{ old('summary', $project->summary) }}</textarea></div>
        <div class="form-field full"><label for="description">รายละเอียดโครงการ</label><textarea class="textarea" id="description" name="description" placeholder="เล่าแนวคิด ความร่วมมือ บทบาทของแต่ละฝ่าย และบริบทที่จำเป็น...">{{ old('description', $project->description) }}</textarea></div>
        <div class="form-field"><label for="launch_start">วันเริ่มเปิดตัว</label><input class="input" id="launch_start" name="launch_start" type="date" value="{{ old('launch_start', optional($project->launch_start)->format('Y-m-d')) }}"></div>
        <div class="form-field"><label for="launch_end">วันสิ้นสุดช่วงเปิดตัว</label><input class="input" id="launch_end" name="launch_end" type="date" value="{{ old('launch_end', optional($project->launch_end)->format('Y-m-d')) }}"></div>

        <div class="form-field full array-field">
            <div class="field-heading"><label>พาร์ตเนอร์ร่วมโครงการ</label><button class="button button-plain" type="button" data-add-array="partners">＋ เพิ่มพาร์ตเนอร์</button></div>
            <div class="array-list" data-array-list="partners">
                @foreach ($partners as $index => $partner)
                    <div class="array-row" data-array-row><input class="input" name="partners[{{ $index }}]" type="text" value="{{ $partner }}" placeholder="ชื่อบริษัทหรือองค์กร"><button class="button button-secondary" data-remove-row type="button" aria-label="ลบพาร์ตเนอร์">×</button></div>
                @endforeach
                <template data-array-template><div class="array-row" data-array-row><input class="input" name="partners[]" type="text" placeholder="ชื่อบริษัทหรือองค์กร"><button class="button button-secondary" data-remove-row type="button" aria-label="ลบพาร์ตเนอร์">×</button></div></template>
            </div>
        </div>

        <div class="form-field full array-field">
            <div class="field-heading"><label>Tags</label><button class="button button-plain" type="button" data-add-array="tags">＋ เพิ่ม tag</button></div>
            <div class="array-list" data-array-list="tags">
                @foreach ($tags as $index => $tag)
                    <div class="array-row" data-array-row><input class="input" name="tags[{{ $index }}]" type="text" value="{{ $tag }}" placeholder="เช่น NFT, education, blockchain"><button class="button button-secondary" data-remove-row type="button" aria-label="ลบ tag">×</button></div>
                @endforeach
                <template data-array-template><div class="array-row" data-array-row><input class="input" name="tags[]" type="text" placeholder="เช่น NFT, education, blockchain"><button class="button button-secondary" data-remove-row type="button" aria-label="ลบ tag">×</button></div></template>
            </div>
        </div>

        <div class="form-field full array-field">
            <div class="field-heading"><label>แหล่งข้อมูลอ้างอิง</label><button class="button button-plain" type="button" data-add-array="sources">＋ เพิ่ม source</button></div>
            <span class="form-help">URL ต้องขึ้นต้นด้วย https:// หรือ http:// และถูกส่งออกเป็น array ใน JSON</span>
            <div class="array-list" data-array-list="sources">
                @foreach ($sources as $index => $source)
                    <div class="source-row" data-array-row><div class="form-field"><label>Label</label><input class="input" name="sources[{{ $index }}][label]" type="text" value="{{ $source['label'] ?? '' }}" placeholder="เช่น Ryt9"></div><div class="form-field"><label>URL</label><input class="input" name="sources[{{ $index }}][url]" type="url" value="{{ $source['url'] ?? '' }}" placeholder="https://example.com/article"></div><button class="button button-secondary" data-remove-row type="button" aria-label="ลบ source">×</button></div>
                @endforeach
                <template data-array-template><div class="source-row" data-array-row><div class="form-field"><label>Label</label><input class="input" name="sources[NEW_INDEX][label]" type="text" placeholder="เช่น Ryt9"></div><div class="form-field"><label>URL</label><input class="input" name="sources[NEW_INDEX][url]" type="url" placeholder="https://example.com/article"></div><button class="button button-secondary" data-remove-row type="button" aria-label="ลบ source">×</button></div></template>
            </div>
        </div>
    </div>

    <div class="form-actions"><span class="form-help">ข้อมูลนี้จะพร้อมใช้งานที่ <code>/api/projects</code></span><div class="form-actions-right"><a class="button button-secondary" href="{{ $isEdit ? route('admin.projects.show', $project) : route('admin.projects.index') }}">ยกเลิก</a><button class="button button-primary" type="submit">{{ $isEdit ? 'บันทึกการแก้ไข' : 'สร้างข้อมูลโครงการ' }} <span>→</span></button></div></div>
</form>
