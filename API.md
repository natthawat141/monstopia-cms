# MONSTOPIA CMS API Documentation

Base URL: `http://localhost:8000/api`

All endpoints return JSON. Public GET endpoints can be consumed by the company website. Mutation endpoints use the Laravel session created by `/login`, require `Accept: application/json`, and require the CSRF token in `X-CSRF-TOKEN` when called from the Blade admin shell.

## Response envelope

A successful collection response includes pagination metadata:

```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 10
  }
}
```

A successful mutation response is:

```json
{
  "success": true,
  "message": "Project created successfully",
  "data": { "id": 10 }
}
```

Validation and domain errors use:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

The application uses `200` for successful reads/updates/deletes, `201` for creates, `401` for missing authentication, `403` for insufficient role, `404` for missing resources, `419` for a missing or invalid CSRF token, and `422` for validation errors.

## Authentication

Open `POST /login` from the web application with `email` and `password`. The server sets an authenticated session cookie. Browser requests from Blade use `credentials: 'same-origin'` and the CSRF token from the page meta tag. The seeded development accounts are `admin@monstopia.co.th` and `editor@monstopia.co.th`, both with password `password`; change them before production use.

## Dashboard

### `GET /api/dashboard/stats`

Authentication: required.

Returns counts for the four CMS modules.

```json
{
  "success": true,
  "message": "Dashboard stats retrieved successfully",
  "data": {
    "projects": 10,
    "news": 10,
    "services": 5,
    "team_members": 5,
    "published_projects": 10,
    "published_articles": 2,
    "active_services": 5,
    "active_team_members": 5
  }
}
```

## Projects

### `GET /api/projects`

Authentication: public read. Query parameters: `search`, `status`, `category_id`, `page`, `per_page`.

### `GET /api/projects/{project}`

Authentication: public read. Returns the project and a nested `category` object.

### `POST /api/projects`

Authentication: admin/editor. JSON body:

```json
{
  "category_id": 4,
  "title": "MONSTOPIA AI Platform",
  "slug": "monstopia-ai-platform",
  "description": "AI platform for business",
  "client_name": "MONSTOPIA",
  "project_url": "https://example.com",
  "image": "https://cdn.example.com/project.jpg",
  "status": "published",
  "published_at": "2026-08-16T10:30:00+07:00"
}
```

Required fields are `title`, `slug`, `description`, and `status`. Status must be `draft`, `published`, or `archived`. Slug is unique. A published project gets a publication timestamp when one is not supplied.

### `PUT/PATCH /api/projects/{project}`

Authentication and body: same rules as create; the unique slug ignores the current project.

### `DELETE /api/projects/{project}`

Authentication: admin/editor. Returns a success envelope with `data: null`.

## Articles

### `GET /api/articles`

Authentication: public read. Query parameters: `search`, `status`, `category_id`, `page`, `per_page`.

### `GET /api/articles/{article}`

Authentication: public read. Returns the article and nested category object.

### `POST /api/articles`

Authentication: admin/editor. JSON body:

```json
{
  "category_id": 5,
  "title": "Monstopia launches new digital learning project",
  "slug": "monstopia-launches-new-digital-learning-project",
  "summary": "Short article summary",
  "content": "Long article content",
  "cover_image": "https://cdn.example.com/article.jpg",
  "status": "draft",
  "published_at": null
}
```

Required fields are `title`, `slug`, `content`, and `status`. Status must be `draft`, `published`, or `archived`.

### `PUT/PATCH /api/articles/{article}`

Authentication and body: same rules as create.

### `DELETE /api/articles/{article}`

Authentication: admin/editor.

## Services

### `GET /api/services`

Authentication: public read. Query parameters: `search`, `status`, `page`, `per_page`. Results are ordered by `sort_order`.

### `GET /api/services/{service}`

Authentication: public read.

### `POST /api/services`

Authentication: admin/editor. JSON body:

```json
{
  "name": "Web Development",
  "description": "Design and develop web applications",
  "icon": "code-2",
  "status": "active",
  "sort_order": 1
}
```

Required fields are `name`, `description`, `status`, and `sort_order`. Status must be `active` or `inactive`.

### `PUT/PATCH /api/services/{service}`

Authentication and body: same rules as create.

### `DELETE /api/services/{service}`

Authentication: admin/editor.

## Team members

### `GET /api/team-members`

Authentication: public read. Query parameters: `search`, `status`, `page`, `per_page`.

### `GET /api/team-members/{team_member}`

Authentication: public read.

### `POST /api/team-members`

Authentication: admin/editor. JSON body:

```json
{
  "name": "Jane Doe",
  "position": "Chief Executive Officer",
  "bio": "Short biography",
  "profile_image": "https://cdn.example.com/jane.jpg",
  "email": "jane@example.com",
  "linkedin_url": "https://www.linkedin.com/in/janedoe",
  "status": "active"
}
```

Required fields are `name`, `position`, and `status`. Status must be `active` or `inactive`.

### `PUT/PATCH /api/team-members/{team_member}`

Authentication and body: same rules as create.

### `DELETE /api/team-members/{team_member}`

Authentication: admin/editor.

## Categories

`GET /api/categories` and `GET /api/categories/{category}` are public read endpoints. `POST`, `PUT/PATCH`, and `DELETE` require admin/editor authentication. Category fields are `name`, unique `slug`, optional `description`, and `status` (`active` or `inactive`). Projects and Articles can reference a category through `category_id`.

## Company profile

The optional profile endpoint uses the existing `companies` table and is kept for the Monstopia company profile. It follows the same CRUD pattern under `/api/companies`, with public reads and authenticated mutations.

## Browser call example

```javascript
const csrf = document.querySelector('meta[name="csrf-token"]').content;

const response = await fetch('/api/projects', {
  method: 'POST',
  credentials: 'same-origin',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': csrf
  },
  body: JSON.stringify({
    title: 'New Project',
    slug: 'new-project',
    description: 'Project description',
    status: 'draft'
  })
});

const payload = await response.json();
console.log(payload.success, payload.data);
```
