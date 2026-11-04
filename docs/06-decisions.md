# 06 — Decisions (ADR log)

Format: context → decision → consequences. Statuses: **Accepted**, **Provisional**, **Superseded**.

---

## ADR-001 — Frontend stack: Blade + Livewire + Tailwind

**Status:** Accepted · 2026-11-04

**Context.** PRD §19.2 and §21.2 prefer Bootstrap 5 with jQuery/AJAX, DataTables and SweetAlert2. The application foundation was previously approved and built on Laravel 12, Livewire 3, Blade and Tailwind CSS 4. Switching now would discard working, tested UI and add a second JavaScript philosophy.

**Decision.** Keep Blade + Livewire + Tailwind. Use Livewire for interactive screens, native CSS for motion, server-rendered HTML for public pages.

**Consequences.**
- Divergence from PRD §19.2/§21.2 recorded here and in [01-prd-breakdown.md](01-prd-breakdown.md).
- No jQuery, Bootstrap, DataTables or SweetAlert2 dependencies.
- Confirmations use native dialogs or a small Livewire/Alpine pattern as needed.
- Lists use server-side pagination and Livewire search/filtering.
- If a future association mandates Bootstrap, the product can be re-themed, but that is a separate port, not a fork of business logic.

---

## ADR-002 — Visual direction: “Target Court”

**Status:** Accepted for design · branding **Pending confirmation** (OD-01, OD-11)

**Context.** The product needs a credible, modern sports identity that works for Targetball but remains reusable for other sports associations. No official brand guide, photography or logo usage rules have been supplied.

**Decision.** Adopt a court-and-target visual language: deep ink surfaces, association-configurable teal/amber defaults, Space Grotesk display type, Plus Jakarta Sans body, target-ring and court-grid motifs, restrained motion.

**Consequences.**
- Seed colours (`#0f766e`, `#f59e0b`) and the initial-based logo tile are placeholders, not official identity.
- All official branding remains editable through association settings.
- No stock photography, invented slogans, awards or statistics are used.
- When OD-01/OD-11 are confirmed, replace logo, colours and copy without changing layout.

---

## ADR-003 — Association isolation: scoped schema now, single-installation model

**Status:** Provisional

**Context.** PRD §17.1 states one independent database per association and explicitly says not to require `association_id` on every table. The implemented foundation uses an `association_id` column plus a global scope and trusted association context on association-owned tables. This is a deliberate safety net for a reusable codebase and supports internal testing.

**Decision.** Keep the current association-context safety layer for the MPTA build. Treat the installation as single-association for product behaviour: no switcher, no onboarding, no cross-association UI.

**Consequences.**
- The public site and admin never expose association selection.
- Cross-association access is denied by scoped queries and policies.
- Before a second association installation is produced, review whether to keep or strip the scoping columns; either way, the installation remains isolated.
- Document this divergence from PRD §17.1 in module reviews.

---

## ADR-004 — Self-hosted variable fonts

**Status:** Accepted · 2026-11-04

**Context.** State-office networks may be slow or restrict external CDNs. Font loading also affects layout stability.

**Decision.** Ship Space Grotesk and Plus Jakarta Sans as self-hosted Fontsource variable packages through Vite.

**Consequences.**
- No Google Fonts/Bunny request at runtime.
- Slightly larger build output, cached once.
- Fallback stacks remain in the token definitions for first paint.

---

## ADR-005 — Motion and accessibility policy

**Status:** Accepted · 2026-11-04

**Context.** A sporty interface can become distracting or inaccessible if motion is overused.

**Decision.** Use CSS-only transform/opacity motion, 150–520ms, one-time reveals and a single marquee ribbon. Disable all non-essential motion under `prefers-reduced-motion`. Maintain visible focus, keyboard paths and AA contrast.

**Consequences.**
- No animation library or scroll-jacking.
- Content is always visible without JavaScript for public pages.
- Motion is treated as enhancement, not information.

---

## ADR-006 — Roles: enum + policies now, permission bundles later

**Status:** Provisional

**Context.** PRD §3.1 lists ten roles; PRD §21.2 names Spatie Permission. The foundation implements `super_admin`, `association_admin` and `staff` with Laravel policies and an enum cast.

**Decision.** Ship the foundation with enum roles and policies. Introduce configurable permission bundles only when district, organization and member portals require them.

**Consequences.**
- Role checks are explicit and testable today.
- New roles (President, Secretary, Treasurer, District Admin, Organization Admin, Event Coordinator, Player/Member) will require policy updates and navigation visibility rules.
- The UI reads role labels from the enum, so additions do not require copy changes across screens.
- If Spatie Permission is adopted, policies remain the enforcement point.

---

## ADR-007 — Inline SVG icons, no icon package

**Status:** Accepted · 2026-11-04

**Decision.** Use a small set of inline stroke SVG icons in layouts and components.

**Consequences.**
- No runtime icon dependency or font.
- Icons inherit `currentColor` and remain crisp.
- Reuse is manual; extract a Blade component if the set grows beyond a dozen icons.

---

## ADR-008 — Placeholder content policy

**Status:** Accepted · 2026-11-04

**Decision.** Public headings and supporting lines may be written as placeholders, but official facts and policies must come from configured data or be omitted. Development seeds (`info@mpta.test`, seeded fees, initial colours) are never presented as confirmed.

**Consequences.**
- Copy that could be mistaken for policy carries a “configured by the association” qualifier.
- The Open Decisions Register (PRD §25) governs when placeholders can become official.
- Design review checks that no unpublished capability is claimed as live.
