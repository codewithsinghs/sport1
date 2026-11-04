# 05 — Admin Blueprint

The admin workspace is a working tool. The design target is calm density: obvious navigation, strong page titles, few competing accents, and actions that match the user's actual permissions.

## 1. Shell

### Sidebar (≥1024px)
- Width 288px; ink gradient with a subtle brand glow and court texture.
- Brand lockup at top: logo/initials, short name, full association name.
- Navigation groups with micro labels; 44px rows; inline stroke icons; active item uses brand fill.
- User block pinned to the bottom: initials, name, role, sign out.
- Scrolls independently on short screens; never hides sign out.

### Top bar
- Mobile menu trigger below `lg`.
- Association name + state; the current association is always visible.
- Role badge on the right; page actions live in the page header, not the top bar.
- Sticky with translucent paper background and fine border.

### Content area
- Max width 1440px; 24–32px gutters.
- Page header: display-face title, one-line purpose, right-aligned actions.
- Flash region directly under the header; dismissible success banner.
- Vertical rhythm of 24–32px between panels.

### Mobile
- Off-canvas drawer with overlay; closes on link selection and Escape.
- Top bar keeps the menu trigger and association context.
- Tables scroll horizontally inside a labelled container; forms go full width.

## 2. Navigation information architecture

### Live

| Group | Item | Visibility |
| --- | --- | --- |
| Overview | Dashboard | All active staff |
| Association | Profile & branding | All; editing restricted |
| Master data | Districts | All; writes super admin |
| Master data | Sports | All; enablement for managers, master writes super admin |
| Master data | Disciplines | All; writes managers |
| Master data | Categories | All; writes managers |
| Master data | Membership categories | All; writes managers |

### Target IA (as modules ship)

| Group | Items |
| --- | --- |
| Overview | Dashboard, My tasks |
| Association | Profile, Office bearers, Committees, Settings |
| People | Organizations, Players, Members, Memberships |
| Competitions | Events, Entries, Fixtures, Results |
| Finance | Payments, Receipts, Reconciliation |
| Content | Pages, News & notices, Gallery, Downloads |
| Reports | Association reports, District reports, Exports |
| System | Users, Roles & permissions, Audit log |

Only show a group when the user can reach at least one item inside it. District and organization portals reuse the shell with a narrower menu.

## 3. Dashboard

### Current composition (foundation + masters)
1. **Page header** — “Dashboard”, short purpose line, manager actions.
2. **Association hero** — dark brand gradient, logo, official name, state, enabled sport chips, about line.
3. **Stat cards** — districts, enabled sports, disciplines, categories, membership categories; icon, display number, label, link.
4. **Enabled sports panel** — sport name, description, status badge, manage link.
5. **Access panel** — account, role, isolation statement (the scope reassurance is intentional).

Rules:
- No invented trends, percentages or charts without real time-series data.
- Every number links to the screen that explains it.
- Counts are association-scoped by server context.
- Staff see the same facts without edit actions.

### Future composition
- Pending actions (applications awaiting review, expiring affiliations) only when those modules exist.
- Upcoming events and recent payments only when data is real.
- District users see district-scoped equivalents and no statewide totals they cannot access.

## 4. Lists and tables

Pattern for every index screen:

1. Page header with title, purpose, primary create action (permission-aware).
2. Toolbar: search input, filters, result count. Search is server-side; filters preserve query state in the URL where practical.
3. Table: identifier first, status badge, key facts, right-aligned actions.
4. Pagination: server-side with page size 15–25; show total.
5. Empty state: explains why the list is empty and what to do next.
6. Row actions: Edit, Delete (confirmed), or a “Read only” marker for staff.

Behaviour:
- Deleting asks for explicit confirmation and explains the consequence.
- Cross-association IDs return 404 via scoped queries, not a helpful “record exists elsewhere” message.
- No bulk actions until a real bulk workflow is specified.
- Destructive actions use danger colour only at the confirmation step.

## 5. Forms

- Create/edit opens a modal for small master records; full pages for multi-step workflows.
- Labels above inputs; hints below; required fields marked in the label.
- Validate server-side; keep entered values after validation failure.
- Submit button shows a loading label and is disabled during save.
- Modal closes only after a successful save; errors remain visible.
- Slug or business identifiers are generated or validated server-side, never trusted from the client.
- File uploads: accepted types and size limits stated before selection; progress/error states; authorized storage only.

## 6. Feedback

| Event | Treatment |
| --- | --- |
| Save success | Green flash banner with specific message, dismissible, `role="status"` |
| Validation | Inline messages; first invalid field focused |
| Authorization | 403 page; no misleading empty state |
| Not found | 404 page |
| Rate limit | Plain-language retry guidance |
| Network failure | Preserve form state; explain retry |

## 7. Role-aware interface rules

- The server is authoritative: policies and scoped queries decide access. UI hiding is only a convenience.
- `super_admin` may manage global masters; `association_admin` manages association-owned configuration; `staff` reads.
- Never render a control the current user cannot use; show a read-only state instead where useful.
- Role names come from the `UserRole` enum labels, not hard-coded strings.
- Isolation copy (“This workspace only exposes records belonging to …”) appears on the dashboard and remains accurate.

## 8. Accessibility and keyboard

- Skip link to main content.
- Sidebar navigation operable by keyboard; active item exposed with `aria-current`.
- Modal: focus moves inside, Escape closes, focus returns to the trigger.
- Tables have `<caption>` or an accessible heading; scroll containers are focusable and labelled.
- Status badges include text, never colour alone.
- Charts (future): accessible summary table or text alternative.

## 9. Density and future growth

- Master data screens: comfortable density (52–60px rows).
- Reporting screens: compact density (40–44px rows) with sticky headers.
- Filters collapse into a panel on mobile rather than wrapping into many rows.
- Avoid nesting cards more than two levels; use dividers and section labels instead.
