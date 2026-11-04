# 04 — Public Site Blueprint

The public site is the association's official face. It must be credible on first load, useful on a phone, and honest about what is not yet published.

## 1. Page inventory

| Priority | Page | Status | Data source | Notes |
| --- | --- | --- | --- | --- |
| P0 | Home | Live | Association, enabled sports, district count, membership categories, `PublicContent` | Ten-section editorial build |
| P0 | Login | Live | Association branding | Administrative access |
| P1 | About | Live | Association profile/about setting + milestones | Official appointments still pending OD-01 |
| P1 | Governance | Live | Structure copy + district/sport counts | Office bearers marked awaiting confirmation |
| P1 | District directory | Live | Districts (M2) | 55 validated districts, client-side search |
| P1 | District detail | Live | State-scoped districts | 404 outside the association state |
| P1 | Sport — Targetball | Live | Sports/disciplines/categories (M3) | Approved sport facts only |
| P2 | Events | Live | `PublicContent` + association facts | Championship and milestones |
| P2 | Results | Live | `PublicContent` | Status-first; no invented results |
| P2 | News and notices | Live | `PublicContent` championship story | One verified update + notice board |
| P2 | Gallery | Live | 2025 championship photography | Native `<dialog>` lightbox |
| P2 | Downloads | Live | Static document list | Items marked “on request” |
| P3 | Register | Live | Membership policy + categories | Office-led pathway, no fake form |
| P3 | Contact | Live | Association contact + email topics | Pre-addressed mailto, no unchecked form |
| P3 | Privacy / Terms | Live | Portal policy copy | Association approval still recommended |

No public page may render private player data, internal notes, application documents or financial details (PRD §5.3).

## 2. Homepage specification

### 2.1 Utility strip
- Left: association short name and state/coverage, e.g. “MPTA · Madhya Pradesh”.
- Right: “Administrative login”.
- Purpose: immediate identity and a discreet staff entry point.
- Mobile: hide the right link below `sm`; keep identity.

### 2.2 Header
- Logo lockup: association logo or initials tile, full name on two lines.
- Section navigation: The association, Sport, Membership, Contact (anchors today; real routes when pages ship).
- Primary action: “Member login”.
- Sticky, translucent paper background with blur and a fine bottom border.
- Mobile: menu trigger opens an off-canvas panel with the same links and a full-width login action.
- `aria-current` for the active section; visible focus.

### 2.3 Hero
- Dark ink/court gradient with a large target-ring illustration on the right; subtle court grid.
- Eyebrow: dynamic coverage line, e.g. “Official state association · 55 districts”.
- H1: association name (display XL).
- Supporting paragraph: association “about” setting, or a neutral fallback that describes the association's role.
- Actions:
  1. Primary light button — “Explore the sport” → `#sport`.
  2. Outline button — “Member login” → `/login`.
- Stats row (3): districts, sports, membership types, each with a display number and micro label.
- Optional small note: “Official portal · Madhya Pradesh” using real data only.
- Background art is decorative (`aria-hidden`), no text baked into images.
- Facts: numbers come from the database; nothing is hard-coded except the layout.

### 2.4 Moving ribbon
- A slim paper strip repeating real, approved values: association short name, state, sport, “Membership”, “District network”, “Governance”, separated by accent dots.
- Duplicated track for a seamless loop; the whole ribbon is decorative and `aria-hidden`.
- Disabled under `prefers-reduced-motion`.

### 2.5 “The association” intro + three pillars
- Eyebrow: “The association”.
- H2: “Built for every district. Focused on the game.” (placeholder copy, not policy).
- Short paragraph grounded in the association role.
- Three numbered cards:
  1. **District network** — coordination across the state's districts.
  2. **Membership** — categories, records and renewals.
  3. **Competition** — events, fixtures and published results.
- Copy must not claim live capabilities that are not built; use forward-looking wording such as “published here as they are confirmed” where needed.
- Cards use target/number motifs, not generic icons.

### 2.6 Sport programme
- Eyebrow: “Sports programme”.
- H2: “The game we govern.”
- Featured card for the first enabled sport (Targetball today), with any additional sports in a secondary card grid:
  - Sport name, description from the master record.
  - “Enabled for MPTA” badge.
  - Discipline/category counts and district reach.
  - Abstract target illustration, no invented action photography.
- Empty state: “Sports for this association are being configured.”
- No link until the sport page exists.

### 2.7 Membership
- Eyebrow: “Membership”.
- H2: “Find your place in the game.”
- Intro copy explaining categories are set by the association.
- Cards from `membership_categories`: name, description, fee, billing period, approval requirement.
- Fee display: large display number, currency and period clearly separated.
- Primary action per card: “Register interest” → contact section/mailto (no fake checkout).
- Note: “Fees and categories are confirmed by the association.” until OD-05 is approved.
- Empty state: “Membership categories are being configured.”

### 2.8 Contact band
- Two-column: statement on the left, contact list on the right (address, phone, email, website).
- Only render links for configured values; show “Not configured” quietly otherwise.
- Primary action: email the association.
- Include a subtle map-free layout; no false office details.

### 2.9 Footer
- Ink background with target-ring watermark.
- Column 1: logo + one-line association description + state.
- Column 2: Explore anchors.
- Column 3: Contact details.
- Column 4: Access — member login, administrative login.
- Bottom bar: copyright, “Official association portal”, privacy/terms placeholders (only when pages exist).

## 3. Copy and content rules

- **Official facts** (name, address, contact, fees, district count, sports) come from the database. Never duplicate them in static copy.
- **Placeholder copy** that shapes the brand is allowed (headings, supporting lines) but must remain policy-neutral and is listed in [06-decisions.md](06-decisions.md).
- **Pending** values (fees, eligibility, governance) are labelled “configured by the association” rather than asserted.
- No slogans, awards, affiliations or statistics that the association has not approved.
- Empty and partial states must read intentionally, not like broken pages.

## 4. SEO and sharing

- Per-page title and meta description via layout sections.
- One `h1` per page; headings in order.
- Canonical URL and Open Graph/Twitter metadata are emitted by the public layout.
- Live: `SportsOrganization` JSON-LD using the association name, URL, logo (when configured) and contact details. Verify official content before launch.
- Clean slugs; no query-string pages for public content.

## 5. Responsive behaviour

| Area | Mobile (<640) | Tablet (640–1023) | Desktop (≥1024) |
| --- | --- | --- | --- |
| Header | Logo + menu button | Logo + condensed links | Full nav + login |
| Hero | Single column, art below/behind, 40–48px H1 | 56px H1, art right | Two columns, 72–80px H1 |
| Stats | 3 compact columns or stacked | 3 columns | 3 columns integrated |
| Pillars | Stacked | 2+1 | 3 columns |
| Sport feature | Stacked art above copy | Side-by-side | 5/7 split |
| Membership | Stacked cards | 2 columns | 3–4 columns |
| Contact | Stacked | Stacked | 2 columns |
| Footer | Stacked accordions not required | 2 columns | 4 columns |

## 6. Analytics and privacy

- No third-party tracking or advertising pixels by default.
- If analytics are added, they must be privacy-respecting, documented and disclosed.
- Contact form data is never shown publicly and is retained per the association's policy.

## 7. As-built editorial refresh (v0.2)

The homepage specification above records the first Target Court build. The current public site keeps its information architecture but changes the visual treatment:

- **Header** — ink-green two-tier bar with Instagram/national-body links, primary navigation (The game, Districts, Events, Gallery, Membership, Contact), an “Association” dropdown for secondary pages, and a gold “Join the association” action. Mobile uses a full-width dark menu.
- **Hero** — full-bleed 2025 championship photography with an ink veil, Bodoni Moda headline, gold eyebrow, four association/sport facts and an event card linking to the championship page.
- **Sections** — cream editorial ticker; “The game” split with authentic lineup photography and four sport facts; dark championship spotlight with a floating date card; three-step pathway; district network with twelve linked districts; membership cards from the database; a five-image gallery mosaic; the championship story beside an official-channels panel; closing CTA.
- **Photography** — real championship frames only, responsive WebP with `-sm` variants, footer credit to the 2025 National Targetball Championship, Bhopal.
- **Honesty rules** — no invented results, office bearers, documents or fees. Results show “pending publication”; downloads show “on request”; governance shows “awaiting confirmation”.
- **Interaction** — vanilla JS only: mobile menu, district search, gallery lightbox. No Alpine or framework bundle on public pages.
