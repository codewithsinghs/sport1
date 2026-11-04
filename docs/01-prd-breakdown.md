# 01 — PRD Breakdown

Source: `MP_Targetball_Association_Complete_PRD` v1.0 (4 Nov 2026).
This document does not replace the PRD. It turns it into a build map: modules, dependencies, surfaces and delivery order.

## 1. Product guardrails

| Guardrail | Source | Consequence for delivery |
| --- | --- | --- |
| One association per installation | PRD §1.5, §21.9 | No tenant switcher, no SaaS onboarding, no subscription UI. Branding and settings are per installation. |
| Reusable, modular product | PRD §1.5, §21.9 | Modules stay domain-based; sport-specific rules live in configuration or dedicated services, not generic CRUD. |
| Secure by default | PRD §3.2, §18 | Every list, export, download and action is authorized server-side. The UI only reflects decisions the server already enforces. |
| Do not invent policies | PRD §2.6, §25 | Unconfirmed fees, eligibility, governance and branding are placeholders or omitted, never fabricated as official. |
| Mobile-responsive, non-technical users | PRD §1.6, §19 | Large touch targets, clear labels, visible validation, forgiving forms. |
| Boring, maintainable technology | PRD §21.1, §21.8 | Modular monolith, one database, no microservices or queue infrastructure unless justified. |

## 2. Module map

Dependencies flow downward. A module may not depend on a later phase.

### M0 — Platform foundation
- **Purpose:** authentication, users, roles, authorization, association settings, audit foundation, base layouts.
- **Roles:** all.
- **Entities:** users, roles/permissions, association settings, audit logs.
- **Surfaces:** login, password reset, admin shell, public shell.
- **Depends on:** nothing.
- **PRD refs:** §3, §4, §6.5, §18, Phase 1.
- **Status:** Live (auth, roles, association settings) / Designed (audit UI, password reset).

### M1 — Association and governance
- **Purpose:** official profile, office bearers, committees, governance records.
- **Roles:** Association Admin, President, Secretary, Public Visitor (published parts only).
- **Entities:** association profile/settings, office bearers, committees, committee members.
- **Surfaces:** public About and Governance pages; admin profile, office-bearers and committee screens.
- **Depends on:** M0.
- **PRD refs:** §6, Phase 2.
- **Status:** Profile/branding Live; office bearers and governance Designed.

### M2 — Geography and districts
- **Purpose:** state/district master, district profiles, district administrators and scope.
- **Roles:** Association Admin, District Admin.
- **Entities:** states, districts, user_districts.
- **Surfaces:** public district directory and district detail; admin district master and district dashboard.
- **Depends on:** M0.
- **PRD refs:** §7, Phase 2.
- **Status:** Master data and admin listing Live; district profiles and district admin scope Designed.

### M3 — Sports configuration
- **Purpose:** reusable sports, disciplines, competition categories and association enablement.
- **Roles:** Association Admin (enable/configure), Super Admin (master).
- **Entities:** sports, association_sports, disciplines, categories.
- **Surfaces:** public sport pages; admin sport/discipline/category screens.
- **Depends on:** M0.
- **PRD refs:** §4, §11.2, Phase 2.
- **Status:** Live.

### M4 — Organizations and affiliation
- **Purpose:** affiliated units, application review, affiliation numbers, renewal and public directory.
- **Roles:** Organization Admin, District Admin, Association Admin, Public Visitor.
- **Entities:** organizations, affiliations, affiliation history.
- **Surfaces:** public directory and detail; organization portal; admin review queue.
- **Depends on:** M2, M3, M10, M8 (fees).
- **PRD refs:** §8, Phase 3.
- **Status:** Designed.

### M5 — Players
- **Purpose:** player profiles, registration, verification, duplicate prevention, registration numbers and privacy.
- **Roles:** Player/Member, Organization Admin, District Admin, Association Admin.
- **Entities:** players, player documents, registration sequence, duplicate flags.
- **Surfaces:** public registration entry; member profile; admin/organization verification queue.
- **Depends on:** M2, M4, M10, M8 (fees).
- **PRD refs:** §9, Phase 4.
- **Status:** Designed.

### M6 — Membership
- **Purpose:** configurable categories, applications, approvals, validity periods, renewals and reports.
- **Roles:** Player/Member, Association Admin, Treasurer.
- **Entities:** membership categories, memberships, renewal history.
- **Surfaces:** public membership information and application; member status; admin category and membership screens.
- **Depends on:** M5, M10, M8.
- **PRD refs:** §10, Phase 4.
- **Status:** Category master Live; memberships Designed.

### M7 — Events and entries
- **Purpose:** event setup, categories, individual/team entries, eligibility review, participant lists.
- **Roles:** Event Coordinator, District Admin, Organization Admin, Association Admin.
- **Entities:** events, event_categories, entries, teams, team_members.
- **Surfaces:** public event calendar and detail; entry forms; admin event workspace and participant lists.
- **Depends on:** M2, M4, M5, M10, M8.
- **PRD refs:** §11, Phase 5.
- **Status:** Designed.

### M8 — Payments and receipts
- **Purpose:** server-side orders, gateway attempts, verification, idempotency, receipts, reconciliation.
- **Roles:** Treasurer, Association Admin, applicants (own payments).
- **Entities:** payment_orders, payment_transactions, receipts, refunds.
- **Surfaces:** checkout/status pages; member receipts; admin reconciliation.
- **Depends on:** M10 and the payable module.
- **PRD refs:** §13, Phase 6.
- **Status:** Designed. Gateway pending confirmation (OD-08).

### M9 — Fixtures, results and achievements
- **Purpose:** manual fixtures, approved results, corrections with audit and public results.
- **Roles:** Event Coordinator, Association Admin, Public Visitor.
- **Entities:** fixtures, results.
- **Surfaces:** public event schedule/results; admin result entry and approval.
- **Depends on:** M7.
- **PRD refs:** §14, Phase 7.
- **Status:** Designed.

### M10 — Applications and approval workflow
- **Purpose:** shared lifecycle for registration, membership, affiliation and entries.
- **Roles:** applicants, reviewers, approvers.
- **Entities:** applications, reviews/approval history.
- **Surfaces:** applicant tracking, reviewer queue, application timeline.
- **Depends on:** M0.
- **PRD refs:** §12, Phases 3–5 and 8.
- **Status:** Designed. This is cross-cutting, not a standalone user-facing module.

### M11 — CMS, notices and documents
- **Purpose:** pages, news, notices, circulars, gallery, public downloads and private document handling.
- **Roles:** Association Admin (author/publish), Public Visitor (published only).
- **Entities:** pages, news/notices, galleries, documents.
- **Surfaces:** public news/notices/downloads; admin content editor and media library.
- **Depends on:** M0, M1.
- **PRD refs:** §15, Phase 8.
- **Status:** Designed.

### M12 — Dashboards, reports and exports
- **Purpose:** role-based dashboards, filtered reports, permission-scoped exports.
- **Roles:** Association leadership, District Admin, Organization Admin, Treasurer.
- **Entities:** read models/aggregates; no new source-of-truth tables.
- **Surfaces:** admin dashboard, district dashboard, organization dashboard, report screens.
- **Depends on:** all operational modules.
- **PRD refs:** §16, Phase 9.
- **Status:** Association dashboard Live (master-data counts); operational/reporting dashboard Designed.

### M13 — Migration, QA and launch
- **Purpose:** historical import, security review, UAT, deployment and handover.
- **Surfaces:** import staging/review screens (admin).
- **PRD refs:** §20, §22, Phase 10.
- **Status:** Planned.

## 3. Cross-cutting capabilities

| Capability | Requirements | Where it appears in UI |
| --- | --- | --- |
| Search and filtering | FR-007, §16.5, §19.6 | Every list screen: search, filters, sort, pagination, permission-aware row actions. |
| Workflow states | FR-005, §12.3 | Status badges, timeline, reviewer actions, applicant tracking. |
| Audit | FR-006, §18.6–18.7 | Read-only timeline/detail panel; never expose secrets. |
| Notifications | FR-008 | Flash messages now; email/SMS/WhatsApp when configured. |
| Error handling | FR-009 | Inline validation, human-readable errors, no stack traces. |
| Responsive UI | FR-010, §19.7 | Mobile-first layouts, touch targets, responsive tables. |
| Privacy | §5.3, §9.8, §15.4 | Public views never render private fields; documents use authorized routes. |
| Money | §13, §17.5 | DECIMAL storage, INR display, no float arithmetic. |

## 4. Frontend surface map

| Surface | Audience | Entry point | Current state |
| --- | --- | --- | --- |
| Public website | Visitors, players, organizations | `/` | Live (home); remaining pages Designed |
| Auth | All staff users | `/login` | Live |
| Admin workspace | Super Admin, Association Admin, Staff | `/admin` | Live (foundation + masters) |
| District workspace | District Admin | `/admin` scoped | Designed |
| Organization workspace | Organization Admin | separate area | Designed |
| Member portal | Player/Member | separate area | Designed |
| Public application forms | Applicants | public routes | Designed |

The current release intentionally ships one coherent public surface plus the association admin workspace. Other surfaces are specified in [02-frontend-strategy.md](02-frontend-strategy.md) and [05-admin-blueprint.md](05-admin-blueprint.md).

## 5. Delivery phases mapped to PRD

| Phase | PRD phase | Platform modules | Frontend outcome |
| --- | --- | --- | --- |
| 0 | Discovery/setup | — | Environment, decisions, docs |
| 1 | Foundation | M0 | Auth, shells, design system |
| 2 | Association + masters | M1, M2, M3 | Public home, admin masters, branding |
| 3 | District + organizations | M2, M4, M10 | Organization portal, affiliation workflow |
| 4 | Players + membership | M5, M6 | Public registration, member area |
| 5 | Events | M7 | Event calendar, entry forms |
| 6 | Payments | M8 | Checkout, receipts, reconciliation |
| 7 | Fixtures + results | M9 | Public results, result approval |
| 8 | Applications + CMS | M10, M11 | Notices, pages, documents, queues |
| 9 | Dashboards + reports | M12 | Role dashboards, exports |
| 10 | Migration, QA, launch | M13 | Import review, UAT, production |

Delivery rule from PRD §24 is preserved: do not start the next phase until the current exit gate is reviewed and accepted.

## 6. Open decisions with frontend impact

| ID | Decision | Frontend impact |
| --- | --- | --- |
| OD-01 | Official registration details, address, logo, office bearers | Hero copy, logo, governance page, footer. Current values are seeds. |
| OD-02 | Eligible organization types | Directory filters, affiliation form options. |
| OD-03 | Who initiates player registration | Public form vs organization-assisted flow. |
| OD-04 | District administration structure | District dashboard and delegation UI. |
| OD-05 | Membership categories, fees, validity | Public membership cards and renewal rules. Current values are seeds. |
| OD-06 | Player eligibility, documents, public fields | Registration form and public profile visibility. |
| OD-07 | Event formats, categories, fees, approval | Event entry UI and category selectors. |
| OD-08 | Payment gateway, settlement, refunds, receipts | Checkout and receipt design. |
| OD-09 | Approval authorities and delegation | Reviewer queues and action visibility. |
| OD-10 | Historical data sources | Import staging UI quality and validation messaging. |
| OD-11 | Public content, branding, domain | Final copy, navigation breadth, social preview images. |
| OD-12 | Meetings, SMS/WhatsApp, certificates | Navigation entries and future module surfaces. |

## 7. Terminology used across docs and code

- **Association** — one official body (MPTA). **Installation** — one deployment + database for an association.
- **Sport** — reusable master (Targetball). **Discipline** — a competition discipline under a sport. **Category** — age/gender/competition grouping.
- **Membership category** — configurable entitlement and fee. **Membership** — one person's period-specific entitlement.
- **Player** — the person. **Member** — a player or official holding a membership.
- **Affiliation** — approval of an organization to operate under the association.
- **Application** — any submittable request moving through the shared workflow.
- **Public** — published by an authorized user and safe to expose without login.

## 8. Known divergences and gaps

1. **UI stack.** PRD §19.2/§21.2 names Bootstrap 5 + jQuery. The implementation uses Blade + Livewire + Tailwind because the foundation was approved on that stack. See ADR-001.
2. **Data isolation.** PRD §17.1 says not to require `association_id` on every table. The current schema uses `association_id` plus trusted association context as a safety net for a reusable codebase. See ADR-003.
3. **Roles.** PRD §3.1 lists ten roles. The current build implements `super_admin`, `association_admin`, `staff`; the remaining roles are Designed with permission bundles to follow. See ADR-006.
4. **Public pages.** PRD §5.1 lists 13 public page types. Only Home is Live; the rest are specified but not built.
5. **Branding.** Seed logo/colours/contact are placeholders. No official identity is claimed.
