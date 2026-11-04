# MPTA Platform — Documentation

Working documentation for the Madhya Pradesh Targetball Association platform.
The source of truth for product scope is the PRD (`MP_Targetball_Association_Complete_PRD`, v1.0, 4 Nov 2026). Everything in this folder translates that document into delivery decisions without silently changing scope.

## Read in this order

| Doc | Purpose |
| --- | --- |
| [01-prd-breakdown.md](01-prd-breakdown.md) | The PRD decomposed into modules, dependencies, phases and frontend surfaces. |
| [02-frontend-strategy.md](02-frontend-strategy.md) | Experience principles, page map, layouts, responsive rules and frontend phases. |
| [03-design-system.md](03-design-system.md) | Targetball visual direction, tokens, typography, components, motion and accessibility. |
| [04-public-site-blueprint.md](04-public-site-blueprint.md) | Public page inventory and section-by-section homepage specification. |
| [05-admin-blueprint.md](05-admin-blueprint.md) | Admin shell, dashboard, list/form patterns and role-aware UI rules. |
| [06-decisions.md](06-decisions.md) | ADR-style decisions, including where implementation intentionally differs from the PRD. |
| [07-changelog.md](07-changelog.md) | What changed, when, and how it was verified. |

## Current delivery focus

**Public site v0.2 and frontend foundation.** The backend foundation (association context, auth, roles, masters) is working. The public site now runs on the editorial “luxe sport” layer with real 2025 championship photography, ten homepage sections and the full page set (events, results, news, gallery, downloads, register, policies). Later modules plug into the same tokens and components.

## Documentation rules

- One concept lives in one document; link instead of duplicating.
- Anything not confirmed by the association is marked **Placeholder** or **Pending confirmation**.
- Official branding, fees, policies and governance details are never invented. The current logo, colours and contact values are development seeds.
- When implementation and PRD disagree, record the divergence in [06-decisions.md](06-decisions.md) rather than rewriting the PRD.
- Dates use `YYYY-MM-DD`. Currency is INR unless stated otherwise.

## Status vocabulary

- **Live** — implemented and verified in the current build.
- **Designed** — specified here, not yet implemented.
- **Planned** — accepted for a later phase.
- **Pending confirmation** — blocked on an association decision (see PRD §25).
