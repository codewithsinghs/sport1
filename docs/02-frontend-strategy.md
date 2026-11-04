# 02 — Frontend Strategy

## 1. Experience goals

The public site should feel like a state sports association, not a database front end. The admin should feel calm, fast and unambiguous for office staff who use it daily.

| Goal | What it means in the interface |
| --- | --- |
| Credible | Official name, association mark, real district coverage and clear contact paths. |
| Energetic | Target/court geometry, disciplined accent colour, confident display type, purposeful motion. |
| Clear | One primary action per area, plain-language labels, status always visible. |
| Fast | Server-rendered pages, self-hosted fonts, minimal JavaScript, no layout shift. |
| Safe | Private data never rendered publicly; actions disappear when the server would deny them. |
| Inclusive | WCAG AA contrast, keyboard operation, reduced-motion support, mobile-first layouts. |

## 2. Surfaces and layouts

| Surface | Layout file | Navigation model | Status |
| --- | --- | --- | --- |
| Public website | `resources/views/layouts/public.blade.php` | Sticky top bar, section links, admin login | Live |
| Authentication | `resources/views/layouts/auth.blade.php` | Split brand panel + form panel | Live |
| Admin workspace | `resources/views/layouts/admin.blade.php` | Persistent sidebar, mobile drawer, top bar | Live |
| District workspace | Planned reuse of admin shell | District-scoped navigation and copy | Designed |
| Organization portal | Planned `layouts/portal.blade.php` | Organization-scoped navigation | Designed |
| Member portal | Planned `layouts/portal.blade.php` | Profile, applications, payments, documents | Designed |

One application, distinct shells. Portals must not fork the design system; they reuse the same tokens and components with different navigation.

## 3. Route and page map

### Live today

| Route | Name | Surface | Notes |
| --- | --- | --- | --- |
| `/` | `home` | Public | Association-aware homepage |
| `/login` | `login` | Auth | Guest only, rate limited |
| `POST /logout` | `logout` | Auth | Authenticated |
| `/admin` | `admin.dashboard` | Admin | Association-scoped counts |
| `/admin/association` | `admin.association.profile` | Admin | Profile, branding, domains |
| `/admin/districts` | `admin.districts.index` | Admin | Master data, super-admin writes |
| `/admin/sports` | `admin.sports.index` | Admin | Enablement + master |
| `/admin/disciplines` | `admin.disciplines.index` | Admin | Association-scoped |
| `/admin/categories` | `admin.categories.index` | Admin | Association-scoped |
| `/admin/membership-categories` | `admin.membership-categories.index` | Admin | Association-scoped |
| `/about` | `about` | Public | Association profile and milestones |
| `/governance` | `governance` | Public | Structure, publication status, process |
| `/districts` | `districts.index` | Public | State-scoped district directory + search |
| `/districts/{district}` | `districts.show` | Public | Scoped district page, 404 outside the state |
| `/sports` | `sports.index` | Public | Enabled sports, facts and categories |
| `/sports/{sport}` | `sports.show` | Public | Enabled sport only, scoped 404 |
| `/membership` | `membership` | Public | Categories, fees, approval rules |
| `/contact` | `contact` | Public | Office details and pre-addressed email topics |
| `/events` | `events.index` | Public | Championship programme and milestones |
| `/results` | `results.index` | Public | Publication status and categories (no invented results) |
| `/news` | `news.index` | Public | Verified championship update and notice board |
| `/gallery` | `gallery.index` | Public | 10-frame championship gallery with lightbox |
| `/downloads` | `downloads.index` | Public | Document library marked “on request” |
| `/register` | `register` | Public | Four-step registration pathway |
| `/privacy` | `privacy` | Public | Portal privacy policy |
| `/terms` | `terms` | Public | Portal terms of use |

### Planned expansions (public)

| Route | Page | Depends on |
| --- | --- | --- |
| `/organizations` | Affiliated organization directory | M4 |
| `/organizations/{slug}` | Organization detail (public fields only) | M4 |
| `/events/{slug}` | Event detail and entry status | M7 |
| `/notices/{slug}` | Notice detail | M11 |
| `/downloads/{document}` | Authorized public file delivery | M11 |

Public navigation only links to routes that exist. The header, footer and mobile menu share one navigation map; new pages must be added to that map and covered by a route test before release.

### Designed next (authenticated)

| Route group | Audience | Notes |
| --- | --- | --- |
| `/district/*` | District Admin | Reuses admin components, hard district scope |
| `/organization/*` | Organization Admin | Own organization and submissions only |
| `/member/*` | Player/Member | Own profile, applications, payments, documents |

## 4. Layout anatomy

### Public shell
1. **Utility strip** — association short name, state/coverage, administrative login.
2. **Header** — logo lockup, section navigation, primary call to action; transparent over the hero and solid after scroll (future enhancement), off-canvas menu on mobile.
3. **Main** — page sections with a consistent max-width and rhythm.
4. **Footer** — brand statement, quick links, contact, admin/login, legal line.

### Auth shell
1. **Brand panel (desktop)** — dark court gradient, target motif, association name, one reassurance line.
2. **Form panel** — compact card, clear labels, inline errors, loading state, support note.
3. **Mobile** — brand panel collapses to a small lockup above the card.

### Admin shell
1. **Sidebar (≥1024px)** — brand lockup, grouped navigation, user block, sign out.
2. **Top bar** — mobile menu trigger, association context, role badge, page actions.
3. **Workspace** — page header, flash region, content. Max width 1280–1440px, data-dense but airy.

## 5. Responsive rules

- Breakpoints: `sm 640`, `md 768`, `lg 1024`, `xl 1280`.
- Mobile-first; no critical action only on hover.
- Admin sidebar off-canvas below `lg`; drawer traps attention with an overlay and closes on navigation/Escape.
- Tables scroll horizontally inside a labelled region; primary columns stay readable at 360px. Future lists should move to card rows on small screens where tables become unusable.
- Touch targets ≥ 40px, spacing ≥ 8px between adjacent actions.
- Hero type scales from 40px mobile to 72px desktop without horizontal overflow.

## 6. Content states

Every screen must define these states before it is considered done:

| State | Treatment |
| --- | --- |
| Loading | `wire:loading` labels, disabled submit buttons, skeleton only where latency is expected. |
| Empty | A specific explanation and the next useful action, not a blank table. |
| Validation error | Inline under the field, focus moves to the first invalid control. |
| Server error | Plain-language message, reference-free, no stack trace. |
| Success | Flash banner at the top of the workspace, dismissible, announced politely. |
| Forbidden | 403 page; hidden actions are not a substitute for authorization. |
| Not found | 404 page. |

## 7. Performance and assets

- Vite 7 + Tailwind CSS 4. No jQuery, no DataTables, no Bootstrap.
- Fonts self-hosted through Fontsource variable packages; one body family, one display family.
- Motion is CSS-only (transform/opacity); no animation library.
- Images: association logo uses `object-contain`; future photography must be responsive, lazy-loaded and have width/height attributes.
- No external font/script CDNs on public pages, so the site stays usable on constrained state-office networks.
- Avoid content shifts: reserve logo/hero geometry, preload only the fonts actually used above the fold (future optimization).

## 8. Frontend delivery phases

| Phase | Scope | Exit gate |
| --- | --- | --- |
| F1 — Theme foundation | Tokens, fonts, components, public home, auth, admin shell | Build passes, pages verified, docs updated. |
| F2 — Public core pages | About, governance, districts, sports | Every page uses published data only; responsive and accessible. |
| F3 — Registration entry | Membership and player entry points (policy-dependent) | Forms validate, track status, and never expose private data. |
| F4 — Events and results | Event calendar, detail, public results | Statuses and publication rules visible. |
| F5 — Dashboards and reports | Operational dashboards, report tables, exports | Totals reconcile; exports obey scope. |

**F1 status (2026-11-04):** Live for the public homepage, auth shell, admin shell and dashboard. Master-data screens inherit the new component language; page-specific polish continues as modules ship.

Frontend phases align with PRD delivery phases; they do not authorise backend scope early.

## 9. Definition of done for a frontend module

- Responsive at 360px, 768px, 1024px and 1440px.
- Keyboard-only path through the primary task.
- Visible focus and AA contrast in both brand and neutral areas.
- Empty, loading, validation and error states designed.
- Actions match server authorization.
- No placeholder text presented as official policy.
- Build and automated tests pass; screenshots or structural verification recorded in [07-changelog.md](07-changelog.md).
