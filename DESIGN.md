# MONSTOPIA CMS Design System

เอกสารนี้กำหนด visual system ของ MONSTOPIA CMS โดยประยุกต์หลักคิดจาก [Impeccable](https://impeccable.style/) ให้เหมาะกับระบบหลังบ้านของบริษัทจริง ไม่ใช่หน้า marketing landing page การออกแบบเน้นความสงบ เป็นทางการ อ่านง่าย และช่วยให้ editor ทำงานกับข้อมูลได้เร็ว

## Design direction

ระบบควรให้ความรู้สึก **official corporate, minimal, modern, trustworthy, and operational** ใช้พื้นขาวและ cool gray เป็นหลัก วาง navigation ด้วย deep navy ใช้ blue เป็น primary action และใช้ status colors เฉพาะความหมาย เช่น green สำหรับ active/published, amber สำหรับ draft และ neutral สำหรับ inactive/archived ห้ามใช้ neon lime, purple gradient, glassmorphism, decorative illustration หรือ headline ใหญ่แบบหน้า landing page

## Visual tokens

| Token | Value | Use |
| --- | --- | --- |
| `base-100` | `#FFFFFF` | Card, form, navbar, table surface |
| `base-200` | `#F5F7FA` | Main application background |
| `base-300` | `#E5E9EF` | Border, divider, table rhythm |
| `base-content` | `#172235` | Body and heading text |
| `neutral` | `#172235` | Sidebar, system-status panel |
| `primary` | `#1D4ED8` | Main action, active navigation, links |
| `secondary` | `#334155` | Secondary information |
| `accent` | `#0F766E` | Service/operational accent |
| `success` | `#15803D` | Published/active/healthy |
| `warning` | `#B45309` | Draft or needs attention |
| `error` | `#B91C1C` | Validation and destructive action |

These tokens are declared as a daisyUI `monstopia` theme in `resources/css/app.css`.

## Typography

Use IBM Plex Sans Thai and IBM Plex Sans for readable Thai/English interface text. Use a monospace fallback only for endpoint URLs, slugs, and metadata. Headings use bold sans-serif with compact tracking; body text uses comfortable line height. Avoid display serif or decorative italic type because this product is an operational CMS.

## Layout

The application uses a responsive daisyUI drawer with a fixed-width sidebar on desktop, a compact navbar, and a centered content area. Every page begins with a clear breadcrumb/section label, page title, one-sentence description, and one primary action. Cards use one-pixel borders and minimal shadow. Tables are the default representation for collections; mobile view allows horizontal scroll rather than hiding important fields.

## Component vocabulary

The UI uses daisyUI/Tailwind components and reusable patterns: `drawer`, `navbar`, `menu`, `breadcrumbs`, `card`, `stat`, `table`, `badge`, `input`, `select`, `textarea`, `btn`, `alert`, `toast`, `modal`, `loading`, and `join` pagination. Component state must be visible and accessible: loading, empty, validation error, API error, success, confirmation, and disabled states are all explicit.

## Interaction rules

Buttons describe their action in Thai, such as `เพิ่มผลงาน`, `บันทึกการแก้ไข`, `เปิด JSON`, and `ลบ`. List pages expose search, status filter, pagination metadata, detail, edit, and delete actions. Create/edit forms submit JSON through `fetch()` with the session CSRF token; they do not submit a normal HTML form to an Eloquent CRUD controller. Deleting data always opens a confirmation modal before issuing `DELETE`.

## Content hierarchy

Dashboard is a control surface with four API-driven stats, module shortcuts, and system status. Collection pages prioritize identity, category/contact metadata, status, dates, and actions. Detail pages show the resource data alongside its live API endpoint. Forms group fields by content purpose and display an API contract panel so the editor understands that the same record is available as JSON.

## Anti-patterns

Do not introduce a marketing hero, fake recent-content table rendered from Blade variables, neon accent, oversized decorative typography, vague `Submit` labels, hidden CRUD errors, or direct `Model::all()` data passed from admin controllers to views. The admin page shell must remain useful even when the API returns an empty collection or an error.
