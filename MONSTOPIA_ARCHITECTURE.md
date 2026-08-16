# Monstopia CMS Architecture Contract

## Product boundary

This application is an authenticated admin CMS for MONSTOPIA COMPANY LIMITED. It manages four content modules: projects, articles, services, and team members. The application includes an API-first boundary: Blade opens the page shell, then browser JavaScript calls REST endpoints and renders JSON into tables, cards, forms, modals, alerts, and pagination controls.

## Request flow

```text
Browser
  -> authenticated Blade shell
  -> fetch('/api/...') with JSON
  -> API middleware / role check
  -> API controller
  -> Form Request validation
  -> Service layer
  -> Eloquent model / relationship
  -> MySQL or SQLite-compatible database
  -> JSON resource envelope
  -> client renderer
```

Blade controllers must not pass CRUD model collections into view data. They only return page shells and static bootstrap values when needed. CRUD data comes from JavaScript `fetch()` calls to the API.

## Authentication and roles

`/login` accepts email and password and creates a session. `auth` protects `/admin/*` and API write endpoints. Users have `admin` or `editor` role. Admin can manage all modules and users in the future. Editor can manage content modules but cannot manage users or change role assignments. Login, logout, invalid credentials, unauthenticated JSON response, and forbidden response are explicit states.

## Modules

| Module | Table | Core fields | Relationship |
| --- | --- | --- | --- |
| Projects | `projects` | title, slug, description, client_name, project_url, image, status | belongs to category |
| Articles | `articles` | title, slug, summary, content, cover_image, status, published_at | optional category |
| Services | `services` | name, description, icon, status, sort_order | standalone |
| Team | `team_members` | name, position, bio, profile_image, email, linkedin_url, status | standalone |
| Categories | `categories` | name, slug, description, status | has many projects/articles |
| Users | `users` | name, email, password, role | authenticated admin/editor |

## JSON contract

Every API response is JSON. Success collection response:

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

Success mutation response:

```json
{
  "success": true,
  "message": "Project created successfully",
  "data": { }
}
```

Validation response:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

Not found and forbidden responses are JSON with `success: false`, an actionable message, and the correct HTTP status (`404` or `403`).

## Endpoint contract

- `GET /api/dashboard/stats`
- `GET|POST /api/projects`
- `GET|PUT|DELETE /api/projects/{project}`
- `GET|POST /api/articles`
- `GET|PUT|DELETE /api/articles/{article}`
- `GET|POST /api/services`
- `GET|PUT|DELETE /api/services/{service}`
- `GET|POST /api/team-members`
- `GET|PUT|DELETE /api/team-members/{teamMember}`
- `GET /api/categories`
- `GET /api/me`

Collection endpoints support `search`, `status`, `page`, `per_page`, and module-specific filters. The project collection also supports `category_id`.

## UI contract

The admin shell contains sidebar, top navbar, authenticated user menu, breadcrumbs, content header, table/card list, search/filter/pagination controls, create/edit form, confirmation modal, toast/alert, loading state, empty state, and error state. The visual system is official corporate minimal: navy, white, slate, blue, and subtle borders; no neon green, no decorative landing-page hero, no oversized marketing copy.

## Seed contract

Seed at least one admin user, one editor user, ten projects, ten articles, five services, five team members, and category records. The Monstopia BullMoonJR content can be represented as one project record with its source URLs in structured metadata or a project description field.

## Delivery contract

Repository contains migrations, seeders, API docs, ER diagram, README, feature tests, screenshots, and the default development credentials documented in `.env.example` / README. Production credentials must never be committed.
