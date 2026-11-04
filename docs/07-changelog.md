# 07 — Changelog

## 2026-11-04 — v0.2 real photography, editorial luxe theme and expanded public site

### Identity and imagery
- Replaced generated mock imagery with real 2025 National Targetball Championship photography sourced from `targetballindia.com` and the MPTA Instagram profile.
- Added responsive WebP sets (1600/1400/1200 px + `-sm` variants) for the stadium, contingents, ceremony, officials, international teams and a 12-frame gallery.
- Confirmed sport facts used across the site: six players a side, 30 × 20 m court, four diamond zones, founded 25 May 2012 in Mathura, first national event October 2013, 2025 nationals at Tatya Tope Stadium, Bhopal.
- Footer credit: “Photography from the 2025 National Targetball Championship, Bhopal”.

### Design system
- Introduced an editorial “luxe sport” layer on top of Target Court: ink-green `#06110e`, warm paper `#f5f2ea`, cream `#ece4d3` and brass gold `#c1a26a`.
- Added self-hosted Bodoni Moda variable serif for display headings, paired with Space Grotesk and Plus Jakarta Sans.
- Added `display-serif*`, `eyebrow-luxe`, `rule-gold`, `btn-gold`, `btn-outline-light`, `luxe-card`, `luxe-panel`, `photo-veil`, `photo-grain`, `gallery-tile`, `kicker-number` and `nav-panel` styles.
- Rebuilt `page-hero`, `section-heading` and `cta-band` around full-bleed photography, serif headlines, gold hairlines and dark editorial panels.
- Preserved existing admin classes and behaviour; the luxe layer is additive and public-facing.

### Public site
- Rebuilt the public layout with a dark two-tier header, primary navigation, an “Association” dropdown, gold membership action, full mobile menu and a four-column dark footer with privacy/terms and photo credit.
- Rebuilt the homepage with ten sections: photographic hero and event card, facts ticker, the game, championship spotlight, pathway, district network, membership, gallery mosaic, news feature with official channels, and a closing CTA.
- Added live pages: `/events`, `/results`, `/news`, `/gallery`, `/downloads`, `/register`, `/privacy`, `/terms`.
- Refreshed existing pages (`about`, `governance`, `membership`, `contact`, district index and detail, sports index and detail) onto the new system with authentic photography.
- Added a native `<dialog>` gallery lightbox with captions and Escape/backdrop close.
- Results and downloads are deliberately honest: no invented winners, documents or policies; unverified items are marked “pending” or “on request”.

### Association settings
- Added editable `instagram_url` and `national_body_url` settings, seeded for MPTA and surfaced in the public header, footer, contact page and homepage channels panel.
- `PublicContent` now centralises championship facts, milestone dates, gallery captions and the championship story so official copy has one edit point.

### Verification
- `php artisan view:cache` — pass; all Blade templates compile.
- `npm run build` — pass; CSS 122.82 kB (21.72 kB gzip), JS 59.10 kB (22.24 kB gzip).
- `php artisan test` — pass; 30 tests, 102 assertions, including five new `PublicPagesTest` cases for page rendering, gallery content, district scoping, sport enablement and missing association context.
- HTTP smoke — all public routes (`/`, about, governance, districts, district detail, sports, sport detail, membership, contact, events, results, news, gallery, downloads, register, privacy, terms, login) return 200.
- Browser structural check — mobile menu toggles with correct `aria-expanded`, gallery dialog opens with the correct image/caption, no console errors, no horizontal overflow, and lazy images load below the fold.
- Screenshot capture remains unavailable in the in-app browser (`NATIVE_BROWSER_VIEWPORT_UNAVAILABLE`); verification used accessibility-tree snapshots, DOM checks and HTTP responses.

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
