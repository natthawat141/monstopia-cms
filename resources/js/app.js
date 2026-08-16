const root = document.querySelector('#app-main');
const apiBase = root?.dataset.apiBase || '/api';
const csrf = root?.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '';

const modules = {
    projects: { endpoint: 'projects', label: 'Projects', singular: 'Project', fields: ['title', 'slug', 'description', 'client_name', 'project_url', 'image', 'status', 'published_at', 'category_id'] },
    articles: { endpoint: 'articles', label: 'Articles', singular: 'Article', fields: ['title', 'slug', 'summary', 'content', 'cover_image', 'status', 'published_at', 'category_id'] },
    services: { endpoint: 'services', label: 'Services', singular: 'Service', fields: ['name', 'description', 'icon', 'status', 'sort_order'] },
    team: { endpoint: 'team-members', label: 'Team Members', singular: 'Team member', fields: ['name', 'position', 'bio', 'profile_image', 'email', 'linkedin_url', 'status'] },
};

const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[char]));
const formatDate = (value) => value ? new Intl.DateTimeFormat('th-TH', { dateStyle: 'medium' }).format(new Date(value)) : '—';
const statusBadge = (status) => `<span class="badge badge-sm ${status === 'published' || status === 'active' ? 'badge-success' : status === 'archived' || status === 'inactive' ? 'badge-ghost' : 'badge-warning'}">${escapeHtml(status || 'draft')}</span>`;
const resourceUrl = (module, id = '') => `${apiBase}/${modules[module].endpoint}${id ? `/${id}` : ''}`;
const adminUrl = (module, id = '') => `/admin/${module}${id ? `/${id}` : ''}`;

async function request(url, options = {}) {
    const response = await fetch(url, {
        credentials: 'same-origin',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, ...(options.headers || {}) },
        ...options,
    });
    const payload = await response.json().catch(() => ({}));
    if (!response.ok) {
        const error = new Error(payload.message || 'Request failed');
        error.status = response.status;
        error.payload = payload;
        throw error;
    }
    return payload;
}

function showToast(message, type = 'success') {
    const stack = document.querySelector('#toast-stack');
    if (!stack) return;
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} w-auto max-w-sm shadow-lg`;
    alert.innerHTML = `<span>${type === 'success' ? '✓' : '!'}</span><span>${escapeHtml(message)}</span>`;
    stack.appendChild(alert);
    setTimeout(() => alert.remove(), 4500);
}

function renderError(target, error) {
    if (!target) return;
    const errors = Object.values(error.payload?.errors || {}).flat();
    target.innerHTML = `<div class="alert alert-error api-error"><span>!</span><div><div class="font-semibold">${escapeHtml(error.message)}</div>${errors.length ? `<ul class="mt-1 list-inside list-disc text-sm">${errors.map((item) => `<li>${escapeHtml(item)}</li>`).join('')}</ul>` : ''}</div></div>`;
    target.classList.remove('hidden');
}

function tableHeaders(module) {
    const heads = {
        projects: ['Project', 'Client', 'Category', 'Status', 'Published', ''],
        articles: ['Article', 'Category', 'Status', 'Published', 'Updated', ''],
        services: ['Service', 'Description', 'Status', 'Order', 'Updated', ''],
        team: ['Name', 'Position', 'Email', 'Status', 'Updated', ''],
    };
    return (heads[module] || []).map((head) => `<th>${head}</th>`).join('');
}

function renderRow(module, item) {
    const category = item.category?.name || '—';
    const action = `<div class="join justify-end"><a class="btn btn-ghost btn-xs join-item" href="${adminUrl(module, item.id)}">ดู</a><a class="btn btn-ghost btn-xs join-item" href="${adminUrl(module, item.id)}/edit">แก้ไข</a><button class="btn btn-ghost btn-xs text-error join-item" data-delete-module="${module}" data-id="${item.id}" data-label="${item.title || item.name}">ลบ</button></div>`;
    if (module === 'projects') return `<tr><td><a class="font-semibold hover:text-primary" href="${adminUrl(module, item.id)}">${escapeHtml(item.title)}</a><div class="font-mono text-xs text-base-content/45">${escapeHtml(item.slug)}</div></td><td>${escapeHtml(item.client_name || '—')}</td><td>${escapeHtml(category)}</td><td>${statusBadge(item.status)}</td><td>${formatDate(item.published_at)}</td><td>${action}</td></tr>`;
    if (module === 'articles') return `<tr><td><a class="font-semibold hover:text-primary" href="${adminUrl(module, item.id)}">${escapeHtml(item.title)}</a><div class="font-mono text-xs text-base-content/45">${escapeHtml(item.slug)}</div></td><td>${escapeHtml(category)}</td><td>${statusBadge(item.status)}</td><td>${formatDate(item.published_at)}</td><td>${formatDate(item.updated_at)}</td><td>${action}</td></tr>`;
    if (module === 'services') return `<tr><td><a class="font-semibold hover:text-primary" href="${adminUrl(module, item.id)}">${escapeHtml(item.name)}</a><div class="font-mono text-xs text-base-content/45">${escapeHtml(item.icon || 'no-icon')}</div></td><td class="max-w-xs truncate">${escapeHtml(item.description)}</td><td>${statusBadge(item.status)}</td><td>${item.sort_order ?? 0}</td><td>${formatDate(item.updated_at)}</td><td>${action}</td></tr>`;
    return `<tr><td><a class="font-semibold hover:text-primary" href="${adminUrl(module, item.id)}">${escapeHtml(item.name)}</a></td><td>${escapeHtml(item.position)}</td><td>${escapeHtml(item.email || '—')}</td><td>${statusBadge(item.status)}</td><td>${formatDate(item.updated_at)}</td><td>${action}</td></tr>`;
}

function emptyState(module) {
    return `<div class="empty-state rounded-box px-6 py-16 text-center"><div class="mx-auto mb-4 grid h-12 w-12 place-items-center rounded-full bg-base-200 text-xl text-base-content/50">＋</div><h3 class="text-lg font-semibold">ยังไม่มีข้อมูล ${escapeHtml(modules[module].label)}</h3><p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">สร้างรายการแรกเพื่อเริ่มจัดการ content ผ่าน API-driven CMS</p><a class="btn btn-primary mt-5" href="${adminUrl(module)}/create">เพิ่ม${escapeHtml(modules[module].singular)}</a></div>`;
}

async function loadList() {
    const table = document.querySelector('[data-content-table]');
    if (!table) return;
    const module = table.dataset.module;
    const search = document.querySelector('#list-search')?.value || '';
    const status = document.querySelector('#list-status')?.value || '';
    const page = table.dataset.page || '1';
    const params = new URLSearchParams({ page, per_page: '10' });
    if (search) params.set('search', search);
    if (status) params.set('status', status);
    const body = table.querySelector('tbody');
    const empty = document.querySelector('#list-empty');
    const errorBox = document.querySelector('#list-error');
    body.innerHTML = '<tr><td colspan="6"><div class="flex items-center gap-3 py-8"><span class="loading loading-spinner text-primary"></span>กำลังโหลดข้อมูลจาก API...</div></td></tr>';
    empty?.classList.add('hidden'); errorBox?.classList.add('hidden');
    try {
        const response = await request(`${resourceUrl(module)}?${params}`);
        const items = response.data || [];
        table.querySelector('thead tr').innerHTML = tableHeaders(module);
        if (!items.length) { table.classList.add('hidden'); empty?.classList.remove('hidden'); }
        else { table.classList.remove('hidden'); body.innerHTML = items.map((item) => renderRow(module, item)).join(''); }
        const meta = response.meta || {};
        document.querySelector('#list-meta').textContent = `หน้า ${meta.current_page || 1} / ${meta.last_page || 1} · ทั้งหมด ${meta.total || 0} รายการ`;
        renderPagination(module, meta);
    } catch (error) {
        table.classList.add('hidden');
        if (errorBox) { errorBox.classList.remove('hidden'); renderError(errorBox, error); }
    }
}

function renderPagination(module, meta) {
    const target = document.querySelector('#list-pagination');
    if (!target) return;
    const current = Number(meta.current_page || 1); const last = Number(meta.last_page || 1);
    target.innerHTML = '';
    for (let page = 1; page <= last; page += 1) {
        if (last > 7 && page > 2 && page < last - 1 && Math.abs(page - current) > 1) { if (!target.querySelector('[data-ellipsis]')) target.insertAdjacentHTML('beforeend', '<span class="join-item btn btn-sm btn-disabled">…</span>'); continue; }
        const button = document.createElement('button'); button.className = `join-item btn btn-sm ${page === current ? 'btn-primary' : 'btn-ghost'}`; button.textContent = page; button.dataset.page = page; button.addEventListener('click', () => { const table = document.querySelector('[data-content-table]'); table.dataset.page = page; loadList(); }); target.appendChild(button);
    }
}

async function loadDashboard() {
    const dashboard = document.querySelector('[data-dashboard]');
    if (!dashboard) return;
    try {
        const response = await request(`${apiBase}/dashboard/stats`);
        Object.entries(response.data || {}).forEach(([key, value]) => document.querySelector(`[data-stat="${key}"]`)?.replaceChildren(document.createTextNode(value)));
    } catch (error) { renderError(document.querySelector('#dashboard-error'), error); }
}

async function loadCategories() {
    const select = document.querySelector('[data-category-select]');
    if (!select) return;
    try {
        const response = await request(`${apiBase}/categories?per_page=100`);
        select.innerHTML = '<option value="">ไม่ระบุหมวดหมู่</option>' + (response.data || []).map((item) => `<option value="${item.id}">${escapeHtml(item.name)}</option>`).join('');
    } catch (error) { showToast('ไม่สามารถโหลดหมวดหมู่ได้', 'error'); }
}

function setFormValue(name, value) {
    const input = document.querySelector(`[data-content-form] [name="${name}"]`);
    if (!input) return;
    if (input.type === 'checkbox') input.checked = Boolean(value);
    else if (input.type === 'datetime-local' && value) input.value = String(value).slice(0, 16);
    else input.value = value ?? '';
}

async function loadForm() {
    const form = document.querySelector('[data-content-form]');
    if (!form) return;
    const module = form.dataset.module; const id = form.dataset.id;
    if (module === 'projects' || module === 'articles') await loadCategories();
    if (!id) return;
    try {
        const response = await request(resourceUrl(module, id));
        const data = response.data || {};
        const fields = modules[module].fields;
        fields.forEach((field) => setFormValue(field, field === 'category_id' ? (data.category_id ?? data.category?.id) : data[field]));
        document.querySelector('[data-form-loading]')?.remove();
    } catch (error) { renderError(document.querySelector('#form-errors'), error); }
}

function formPayload(form) {
    const payload = {};
    new FormData(form).forEach((value, key) => {
        if (key === '_token') return;
        const input = form.elements[key];
        if (input?.type === 'checkbox') payload[key] = input.checked;
        else if (['category_id', 'sort_order'].includes(key)) payload[key] = value === '' ? null : Number(value);
        else payload[key] = value === '' ? null : value;
    });
    form.querySelectorAll('input[type="checkbox"]').forEach((input) => { payload[input.name] = input.checked; });
    return payload;
}

async function submitForm(form) {
    const module = form.dataset.module; const id = form.dataset.id; const button = form.querySelector('[type="submit"]');
    button.disabled = true; button.classList.add('loading');
    try {
        const response = await request(resourceUrl(module, id), { method: id ? 'PUT' : 'POST', body: JSON.stringify(formPayload(form)) });
        showToast(response.message || 'บันทึกข้อมูลสำเร็จ');
        const saved = response.data;
        setTimeout(() => { if (saved?.id) window.location.assign(adminUrl(module, saved.id)); else window.location.assign(adminUrl(module)); }, 500);
    } catch (error) { renderError(document.querySelector('#form-errors'), error); button.disabled = false; button.classList.remove('loading'); }
}

async function loadDetail() {
    const detail = document.querySelector('[data-content-detail]');
    if (!detail) return;
    const module = detail.dataset.module; const id = detail.dataset.id;
    try {
        const response = await request(resourceUrl(module, id));
        const item = response.data || {};
        const title = item.title || item.name || '—';
        document.querySelector('#detail-title').textContent = title;
        document.querySelector('#detail-subtitle').textContent = item.position || item.client_name || item.slug || '';
        document.querySelector('#detail-status').innerHTML = statusBadge(item.status);
        const category = item.category?.name || 'ไม่ระบุ';
        const pairs = module === 'projects' ? [['Description', item.description], ['Client', item.client_name], ['Project URL', item.project_url], ['Category', category], ['Published', formatDate(item.published_at)]] : module === 'articles' ? [['Summary', item.summary], ['Content', item.content], ['Category', category], ['Published', formatDate(item.published_at)]] : module === 'services' ? [['Description', item.description], ['Icon', item.icon], ['Sort order', item.sort_order], ['Status', item.status]] : [['Position', item.position], ['Bio', item.bio], ['Email', item.email], ['LinkedIn', item.linkedin_url]];
        document.querySelector('#detail-body').innerHTML = pairs.map(([label, value]) => `<div class="border-b border-base-300 py-4 last:border-b-0"><div class="text-xs font-semibold uppercase tracking-wider text-base-content/45">${label}</div><div class="prose-content mt-1 text-sm">${escapeHtml(value || '—')}</div></div>`).join('');
        document.querySelector('[data-detail-loading]')?.remove();
    } catch (error) { renderError(document.querySelector('#detail-error'), error); }
}

function confirmDelete(module, id, label) {
    const modal = document.querySelector('#confirm-modal');
    if (!modal) return window.confirm(`ยืนยันการลบ ${label || 'รายการนี้'} หรือไม่?`);
    document.querySelector('#confirm-title').textContent = `ลบ${modules[module].singular}`;
    document.querySelector('#confirm-message').textContent = `คุณกำลังจะลบ “${label || 'รายการนี้'}” การกระทำนี้ย้อนกลับไม่ได้`;
    modal.showModal();
    const submit = document.querySelector('[data-confirm-submit]');
    const cancel = document.querySelector('[data-confirm-cancel]');
    return new Promise((resolve) => {
        const cleanup = () => { submit.removeEventListener('click', yes); cancel.removeEventListener('click', no); modal.close(); };
        const yes = () => { cleanup(); resolve(true); };
        const no = () => { cleanup(); resolve(false); };
        submit.addEventListener('click', yes, { once: true }); cancel.addEventListener('click', no, { once: true });
    });
}

async function deleteResource(module, id, label) {
    const confirmed = await confirmDelete(module, id, label);
    if (!confirmed) return;
    try { const response = await request(resourceUrl(module, id), { method: 'DELETE' }); showToast(response.message || 'ลบข้อมูลสำเร็จ'); setTimeout(() => window.location.assign(adminUrl(module)), 450); }
    catch (error) { showToast(error.message || 'ลบข้อมูลไม่สำเร็จ', 'error'); }
}

document.addEventListener('DOMContentLoaded', () => {
    loadDashboard(); loadList(); loadForm(); loadDetail();
    document.querySelector('[data-list-filter]')?.addEventListener('submit', (event) => { event.preventDefault(); const table = document.querySelector('[data-content-table]'); if (table) table.dataset.page = 1; loadList(); });
    document.querySelector('[data-content-form]')?.addEventListener('submit', (event) => { event.preventDefault(); submitForm(event.currentTarget); });
    document.addEventListener('click', (event) => { const button = event.target.closest('[data-delete-module]'); if (button) deleteResource(button.dataset.deleteModule, button.dataset.id, button.dataset.label); });
});
