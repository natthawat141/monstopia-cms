# MONSTOPIA CMS Product Brief

## Product purpose

MONSTOPIA COMPANY LIMITED needs a reliable internal content management system for maintaining company content that can be consumed by a public website or other integrations. The product is an authenticated admin workspace, not a marketing landing page.

## Users and permissions

Administrators sign in to manage content and system configuration. Editors sign in to create, edit, publish, archive, and remove content records. Both roles can work with Projects, Articles, Services, Team Members, and Categories; authentication and role middleware protect the admin surface and all write endpoints.

## Core workflows

An editor opens a module, searches or filters a collection, reads a detail page, creates a new record through a JSON form, receives explicit validation errors when the request is invalid, and sees the saved record after the API responds. Editing uses the same API contract. Deleting requires a confirmation modal and returns the user to the collection with a success toast.

## Content modules

Projects store company work and case studies with title, slug, description, client name, URL, image URL, status, publication date, and an optional category. Articles store news and editorial content with title, slug, summary, long content, cover image, status, publication date, and category. Services store the company's services with description, icon identifier, status, and sort order. Team Members store people with name, position, biography, image URL, email, LinkedIn URL, and visibility status.

## Product voice

Copy is Thai-first, concise, factual, and operational. English is retained for product names, endpoint paths, statuses, and technical identifiers where it improves precision. The interface avoids exaggerated marketing claims and separates source-backed Monstopia context from editor-entered content.

## Acceptance criteria

The product is considered complete when unauthenticated users are redirected from `/admin/*` to `/login`; admin and editor accounts can authenticate; dashboard stats come from `/api/dashboard/stats`; four content modules support list, detail, create, edit, delete, search/filter, and pagination; Blade uses `fetch()` to consume JSON rather than receiving CRUD model collections; Form Requests validate mutations; resources serialize explicit JSON envelopes; projects and articles have a category relationship; seed data includes the requested Monstopia/BullMoonJR context and the minimum record counts; error states cover 401, 403, 404, 419, and 422 contexts; and the responsive corporate UI remains usable on desktop and narrow screens.

## Production note

The repository is ready for development and staging. Before production, change default passwords, provide MySQL credentials, configure HTTPS and trusted proxy settings, set a real `APP_URL`, configure storage for uploaded images, add rate limiting and audit logs, and deploy behind a production PHP runtime.
