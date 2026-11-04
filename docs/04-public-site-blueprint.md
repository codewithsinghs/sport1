# 04 — Public Site Blueprint

The public site is the association's official face. It must be credible on first load, useful on a phone, and honest about what is not yet published.

## 1. Page inventory

| Priority | Page | Status | Data source | Notes |
| --- | --- | --- | --- | --- |
| P0 | Home | Live | Association, enabled sports, district count, membership categories | Sections below |
| P0 | Login | Live | Association branding | Administrative access |
| P1 | About | Designed | Association profile/about setting | Official copy pending OD-01 |
| P1 | Governance | Designed | Office bearers/committees (M1) | Historical terms retained |
| P1 | District directory | Designed | Districts (M2) | 55 validated districts |
| P1 | Sport — Targetball | Designed | Sports/disciplines/categories (M3) | Approved sport copy only |
| P2 | Organizations | Designed | Approved public organizations (M4) | Public fields only |
| P2 | Events | Designed | Published events (M7) | Calendar + detail |
| P2 | Results | Designed | Approved results (M9) | Searchable |
| P2 | News and notices | Designed | Published CMS items (M11) | RSS optional later |
| P2 | Downloads | Designed | Public documents (M11) | Authorized public files |
| P3 | Register | Designed | Player/membership policy (OD-03/05/06) | Entry point, status tracking |
| P3 | Contact | Designed | Association contact + form | Spam/rate protection |
| P3 | Privacy / Terms | Designed | Legal copy | Association approval required |

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
