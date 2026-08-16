# MONSTOPIA Content Management System

ระบบ CMS สำหรับ **MONSTOPIA COMPANY LIMITED** ที่ออกแบบเพื่อจัดการเนื้อหาบริษัทแบบ API-first ด้วย Laravel, PHP, Blade, Tailwind CSS, daisyUI และฐานข้อมูล MySQL โดยหน้า Blade ทำหน้าที่เป็น authenticated application shell แล้วเรียก REST API ด้วย `fetch()` เพื่อ render ตาราง, detail, form, pagination, modal และ toast จาก JSON จริง จึงไม่ใช่ landing page และไม่ส่ง collection จาก Eloquent model เข้า view โดยตรง

## Technology stack

| Layer | Technology |
| --- | --- |
| Application | Laravel 13 / PHP 8.3+ |
| Admin UI | Blade Template + Tailwind CSS 4 + daisyUI 5 |
| Client interaction | Native JavaScript `fetch()` with JSON request/response |
| Database | MySQL 8+ recommended; SQLite is available for local smoke tests |
| Asset build | Vite + pnpm/npm |
| Authentication | Laravel session auth with `admin` and `editor` roles |
| Testing | PHPUnit / Laravel feature tests |

The visual system is **official corporate minimal modern**: white and cool-gray surfaces, deep navy navigation, restrained blue actions, subtle borders, readable typography, and no neon accent or marketing-hero treatment. The component vocabulary follows daisyUI's Laravel guidance for Blade-friendly classes such as `btn`, `card`, `input`, `table`, and `modal` [1] [2].

## Implemented capabilities

ระบบมี login/logout และ protected `/admin/*` area สำหรับผู้ดูแลและผู้แก้ไขเนื้อหา มี dashboard ที่อ่านสถิติจาก `GET /api/dashboard/stats` และมี CRUD ครบสี่ modules ได้แก่ Projects, Articles, Services และ Team Members แต่ละ module รองรับ list, detail, create, edit, delete, search, status filter และ pagination ผ่าน REST API ผู้ใช้ยังมี Categories เป็น relationship สำหรับ Projects และ Articles เพื่อสาธิตฐานข้อมูลแบบ 1:N

ทุก mutation ผ่าน Form Request validation, CSRF-aware session authentication และ explicit JSON Resource serialization ข้อมูลผิดพลาดตอบกลับด้วย `success: false`, `message`, `errors` และ HTTP status ที่เหมาะสม เช่น `401`, `403`, `404`, `419`, `422` และ `500` ตามบริบท

## Installation

ติดตั้ง PHP 8.3+, Composer, Node.js 20+ และ MySQL 8+ จากนั้นรันคำสั่งต่อไปนี้:

```bash
git clone <your-private-repository-url> monstopia-cms
cd monstopia-cms
composer install
cp .env.example .env
php artisan key:generate
```

แก้ `.env` ให้ชี้ไปยัง MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monstopia_cms
DB_USERNAME=root
DB_PASSWORD=your_password
```

สร้าง database `monstopia_cms` ใน MySQL ก่อน จากนั้นรัน migration, seed และ build asset:

```bash
php artisan migrate --seed
pnpm install
pnpm run build
php artisan serve
```

เปิด `http://127.0.0.1:8000/login` แล้วเข้าสู่ระบบด้วย account ที่ seed ไว้ หากต้องการ reset ข้อมูล development ทั้งหมดให้ใช้:

```bash
php artisan migrate:fresh --seed
```

สำหรับ local smoke test ที่ยังไม่มี MySQL สามารถใช้ SQLite ได้โดยสร้าง `database/database.sqlite` และตั้ง `DB_CONNECTION=sqlite` ใน `.env` แต่ environment สำหรับ production ควรใช้ MySQL ตาม spec

## Default accounts

| Role | Email | Password | Permission |
| --- | --- | --- | --- |
| Admin | `admin@monstopia.co.th` | `password` | จัดการ content และเข้าถึง admin shell |
| Editor | `editor@monstopia.co.th` | `password` | จัดการ content modules |

เปลี่ยนรหัสผ่านและ secrets ทั้งหมดก่อนใช้งานจริง ห้ามใช้ default credentials ใน production

## Admin routes

| Method | URL | Purpose |
| --- | --- | --- |
| GET | `/login` | Login form |
| POST | `/login` | Create authenticated session |
| POST | `/logout` | Destroy session |
| GET | `/admin/dashboard` | Dashboard shell; stats are fetched from API |
| GET | `/admin/projects` | Projects list shell |
| GET | `/admin/articles` | Articles list shell |
| GET | `/admin/services` | Services list shell |
| GET | `/admin/team` | Team members list shell |
| GET | `/admin/{module}/create` | API-driven create form |
| GET | `/admin/{module}/{id}` | API-driven detail page |
| GET | `/admin/{module}/{id}/edit` | API-driven edit form |

ผู้ที่ไม่ได้ login จะถูก redirect จาก `/admin/*` ไป `/login`

## REST API

API collection GET และ detail GET เปิดสำหรับการอ่านข้อมูลเว็บไซต์ ส่วน mutation และ dashboard stats ต้องใช้ authenticated session จาก admin/editor และส่ง CSRF token ผ่าน `X-CSRF-TOKEN` ตามที่ client-side `fetch()` ทำให้โดยอัตโนมัติ

| Method | Endpoint | Auth | Purpose |
| --- | --- | --- | --- |
| GET | `/api/dashboard/stats` | Yes | Dashboard counts |
| GET/POST | `/api/projects` | GET public, POST auth | Project collection/create |
| GET/PUT/PATCH/DELETE | `/api/projects/{project}` | GET public, mutation auth | Project detail/mutation |
| GET/POST | `/api/articles` | GET public, POST auth | Article collection/create |
| GET/PUT/PATCH/DELETE | `/api/articles/{article}` | GET public, mutation auth | Article detail/mutation |
| GET/POST | `/api/services` | GET public, POST auth | Service collection/create |
| GET/PUT/PATCH/DELETE | `/api/services/{service}` | GET public, mutation auth | Service detail/mutation |
| GET/POST | `/api/team-members` | GET public, POST auth | Team collection/create |
| GET/PUT/PATCH/DELETE | `/api/team-members/{team_member}` | GET public, mutation auth | Team detail/mutation |
| GET/POST | `/api/categories` | GET public, POST auth | Category collection/create |
| GET/PUT/PATCH/DELETE | `/api/categories/{category}` | GET public, mutation auth | Category detail/mutation |
| GET | `/api/me` | Yes | Current authenticated user |
| GET/POST/PUT/PATCH/DELETE | `/api/companies` | GET public, mutation auth | Optional company profile module |

Collection query parameters include `search`, `status`, `page`, and `per_page`. Projects and Articles also accept `category_id`. For example:

```bash
curl -H "Accept: application/json" \
  "http://127.0.0.1:8000/api/projects?search=AI&status=published&page=1&per_page=10"
```

A successful collection uses this JSON envelope:

```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 0
  }
}
```

A successful mutation returns `201` for create and `200` for update/delete:

```json
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "id": 10,
    "title": "MONSTOPIA AI Platform"
  }
}
```

Validation returns `422`:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

## Database and seed data

Migration files create users, categories, companies, projects, articles, services, and team members. `projects.category_id` and `articles.category_id` reference `categories.id` with nullable delete behavior, providing the required 1:N relationship. The seeder creates two users, one Monstopia profile, five categories, ten projects including BullMoonJR NFT, ten articles, five services, and five team members. The BullMoonJR record reflects the company context supplied in the project brief and stores the partner context in the description/client fields.

## Project structure

```text
app/Http/Controllers/AuthController.php       login/logout
app/Http/Controllers/Admin/PageController.php page shells only; no CRUD model data passed to Blade
app/Http/Controllers/Api/                     JSON controllers
app/Http/Requests/                             validation and API error envelope
app/Http/Resources/                            explicit JSON serialization
app/Http/Middleware/RoleMiddleware.php         admin/editor authorization
app/Models/                                    Eloquent schema and relationships
resources/views/layouts/app.blade.php          corporate admin shell
resources/views/admin/content/                 generic list/form/detail Blade shells
resources/views/auth/login.blade.php           authentication UI
resources/css/app.css                           Tailwind + daisyUI theme
resources/js/app.js                             fetch client, renderers, modal, toast, pagination
database/migrations/                            MySQL/SQLite schema
database/seeders/DatabaseSeeder.php             development data
routes/web.php                                  auth and page-shell routes
routes/api.php                                  REST API routes
MONSTOPIA_ARCHITECTURE.md                        architecture and acceptance contract
DESIGN.md                                        visual system reference
PRODUCT.md                                       product brief
API.md                                           endpoint documentation
ER_DIAGRAM.md                                    rendered ER diagram source/reference
```

## Verification

The project has been verified with `php artisan test`, Blade cache compilation, Vite production build, curl API smoke tests, and browser preview smoke tests. The current feature suite passes **11 tests and 62 assertions**. Browser verification covered login, dashboard API stats, Projects list/create/detail/delete, Articles list, Services list, and Team list. Temporary QA records created during testing were removed again.

Useful commands:

```bash
php artisan test
php artisan view:cache
pnpm run build
php artisan route:list
```

## Design references

The UI component choice and Tailwind/daisyUI installation approach were checked against the official daisyUI Laravel documentation [1] [2]. The visual vocabulary was also informed by the user's requested use of Impeccable Style [3], but the implementation uses original corporate design tokens for MONSTOPIA rather than copying a site design.

## References

[1]: https://daisyui.com/laravel-component-library/?lang=en "daisyUI Laravel component library"
[2]: https://daisyui.com/docs/install/laravel/?lang=en "Install daisyUI for Laravel"
[3]: https://impeccable.style/ "Impeccable: The missing design vocabulary for agents"
[4]: https://laravel.com/docs "Laravel Documentation"
