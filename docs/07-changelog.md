# 07 — Changelog

## 2026-11-04 — v0.1 documentation + Target Court frontend

### Documentation
- Added `docs/` with PRD breakdown, frontend strategy, design system, public/admin blueprints, ADR log and this changelog.
- Recorded three PRD divergences: UI stack (Tailwind/Livewire), association scoping, and role model.
- Marked official branding, fees and contact details as pending confirmation.

### Frontend
- Added self-hosted Space Grotesk and Plus Jakarta Sans variable fonts.
- Reworked Tailwind theme tokens, component classes and motion utilities.
- Rebuilt the public layout and homepage in the Target Court visual language: dark court hero, target motif, marquee, association pillars, sport programme, membership grid and contact panel.
- Refreshed the login shell (split brand/form layout) and admin shell (ink sidebar, grouped icon navigation, mobile drawer, public-site link, role badge).
- Rebuilt the admin dashboard around a workspace hero, linked stat cards, enabled-sports panel and access panel.
- Added accessible `--brand-on` foreground tokens to all shells so button/nav text stays readable for any association colour.
- Updated shared page-header and modal components; master-data screens inherit the new component language.
- Preserved all existing Blade/Livewire class names, routes and role-based behaviour (staff remain read-only).

### Verification
- `npm run build` — pass; 61 modules transformed, CSS 82.26 kB (15.53 kB gzip), JS 59.10 kB (22.24 kB gzip).
- `php artisan view:cache` — pass; every Blade template compiles.
- `php artisan test` — pass; 25 tests, 73 assertions.
- Public homepage structural smoke — pass; renders association name, 55 districts, 1 sport, 4 membership categories, sport counts, contact and footer.
- Admin dashboard structural smoke — pass; association-scoped counts, enabled sports, role/access panel and `aria-current` navigation render for the staff account.
- Master-data structural smoke — pass; `/admin/districts` renders 55 districts with read-only actions for the staff role.
- Login page HTTP check — pass; `GET /login` returns 200 and renders the new split layout copy and form.
- Mobile drawer — off-canvas sidebar is now `invisible` when closed below `lg` so its links are removed from keyboard/AT navigation; it becomes visible when opened and closes on Escape.
- Shared modal — verified on `/admin/sports` and `/admin/districts`: add/edit opens with the first form field focused, the dialog exposes `role="dialog"`/`aria-modal`, Escape and Cancel close it through Livewire, and focus returns to the invoking button.
  - Fix: the initial-focus selector used a quoted CSS attribute inside the `x-data` attribute, which terminated the HTML attribute and silently dropped Alpine's methods. It now uses the unquoted `[type=hidden]` form.
- Screenshot capture — unavailable in the in-app browser (`NATIVE_BROWSER_VIEWPORT_UNAVAILABLE`); verification used accessibility-tree snapshots and HTTP responses instead. Visual QA in a normal browser remains recommended before release.

<!-- Next entries: add date, summary, files touched, verification evidence. -->
