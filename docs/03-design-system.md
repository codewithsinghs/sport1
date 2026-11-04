# 03 — Design System: “Target Court”

A modern sports identity for MPTA/Targetball: court geometry, target rings, deep ink surfaces and a disciplined teal-and-amber palette. The system is association-configurable; the current colours are development seeds pending official confirmation (OD-01, OD-11).

## 1. Design principles

1. **Court, not clip-art.** Geometry comes from court lines, target rings and trajectories — not generic sports stock imagery.
2. **One loud accent.** Teal carries the brand; amber marks action, achievement and focus. Everything else is neutral.
3. **Depth with restraint.** Soft layered shadows, fine borders and dark gradients; no glassmorphism everywhere.
4. **Type does the talking.** Large Space Grotesk headings and numeric displays; readable Plus Jakarta Sans body.
5. **Motion with intent.** Short, eased movements that confirm hierarchy or interaction; never decorative loops that fight reading.

## 2. Brand vs theme tokens

| Layer | Source | Rule |
| --- | --- | --- |
| Association brand | `associations` + settings (`primary`, `secondary`, logo) | Injected as `--brand` / `--brand-accent` at render time. Never hard-coded into a module. |
| Product theme | Design tokens in `resources/css/app.css` | Neutrals, typography, radii, shadows, motion. Shared by every association. |
| Semantic status | Success / warning / danger / info | Consistent meaning across public and admin. |

The theme must remain attractive with any reasonable association colour: surfaces are neutral, brand appears in actions, highlights, rings and gradients.

## 3. Colour

### Neutrals

| Token | Hex | Use |
| --- | --- | --- |
| `ink-950` | `#050b14` | Deepest hero/footer background |
| `ink-900` | `#0a1220` | Sidebar, dark surfaces |
| `ink-800` | `#111c2f` | Raised dark panels |
| `ink-700` | `#1b2a41` | Dark borders/hover |
| `paper` | `#f6f7f2` | Page background (warm, not clinical) |
| `line` | `#e4e8e2` | Light borders |
| `white` | `#ffffff` | Cards, forms |

Body text uses Tailwind `slate-900/800/600/500`, which meets AA on paper and white at normal sizes.

### Association brand (defaults)

| Token | Hex | Use |
| --- | --- | --- |
| `--brand` | `#0f766e` (seed) | Primary actions, links, active nav, ring core |
| `--brand-deep` | `#063b38` | Gradient anchor on dark surfaces |
| `--brand-soft` | `color-mix(brand 10%, white)` | Tinted panels, selected rows |
| `--brand-ring` | `color-mix(brand 28%, transparent)` | Focus glow, ring strokes |

### Accent and semantic

| Token | Hex | Use |
| --- | --- | --- |
| `--brand-accent` | `#f59e0b` (seed) | Target centre, eyebrow marks, featured accents |
| success | `#059669` | Approved, active, enabled |
| warning | `#d97706` | Pending, approval required |
| danger | `#e11d48` | Rejected, destructive, overdue |
| info | `#2563eb` | Informational badges (rare) |

Contrast note: white text is never placed on amber; amber is used for fills with ink text or for decorative strokes.

## 4. Typography

| Role | Family | Weights | Notes |
| --- | --- | --- | --- |
| Display | Space Grotesk Variable | 500–700 | Headings, hero, stats, section labels |
| Body | Plus Jakarta Sans Variable | 400–700 | Paragraphs, forms, tables, navigation |

Scale (desktop; clamps on smaller screens):

| Token | Size / line-height | Use |
| --- | --- | --- |
| `display-xl` | `clamp(2.75rem, 6vw, 5rem)` / 0.98 | Hero headline |
| `display-lg` | `clamp(2rem, 4vw, 3.25rem)` / 1.05 | Page/section hero |
| `heading-1` | `2rem` / 1.15 | Page title |
| `heading-2` | `1.5rem` / 1.25 | Section title |
| `heading-3` | `1.125rem` / 1.4 | Card title |
| `body` | `1rem` / 1.65 | Default |
| `small` | `0.875rem` / 1.55 | Secondary |
| `micro` | `0.75rem` / 1.4 | Labels, badges |

Eyebrow: `0.6875rem`, uppercase, `0.18em` tracking, 700 weight. Headings use tight tracking (`-0.02em` to `-0.035em`).

## 5. Space, radius, elevation

- Base unit 4px; common rhythm 8 / 12 / 16 / 24 / 32 / 48 / 64 / 96.
- Section padding: 64px mobile, 96–112px desktop.
- Container: 1200px public, 1320–1440px admin.
- Radius: `sm 10px`, `md 14px`, `lg 20px`, `xl 28px`, `pill 999px`.
- Elevation:
  - `shadow-soft`: `0 1px 2px rgb(5 11 20 / 0.06), 0 18px 40px -28px rgb(5 11 20 / 0.25)`
  - `shadow-lift`: `0 24px 60px -32px rgb(5 11 20 / 0.35)`
  - Dark surfaces rely on borders (`white/10`) and gradient, not heavy shadows.

## 6. Motifs

### Target rings
Concentric circles: outer stroke in brand, mid stroke in accent, solid centre dot. A dashed orbit and a small “ball” dot add motion without implying a specific scoring rule. Used in hero, auth panel, dashboard hero and footer watermark. Always `aria-hidden`.

### Court lines
A 48px grid at 6–8% opacity over ink gradients. Subtle enough to read as texture, not a diagram.

### Numbered markers
`01 / 02 / 03` in Space Grotesk with a short rule. Used for programme pillars and process steps.

## 7. Components

### Buttons
| Variant | Look | Use |
| --- | --- | --- |
| `btn-brand` | Brand fill, white text, soft brand shadow | Primary action |
| `btn-ink` | Ink fill, white text | Secondary emphasis on light |
| `btn-light` | White fill, ink text | On dark surfaces |
| `btn-secondary` | White with border | Neutral action |
| `btn-ghost` | Transparent, slate text | Tertiary |
| `btn-danger` | Rose fill | Destructive |
| sizes | default 44px, `btn-sm` 34px | — |

States: hover darkens; focus-visible ring; disabled 50% + not-allowed; loading keeps width and swaps label.

### Surfaces
- `.card` — white, 20px radius, fine border, soft shadow.
- `.surface-dark` — ink gradient, white/10 border, court grid, white text.
- `.surface-tint` — brand 8–10% tint for selected or informational areas.
- `.panel` — admin section container; same visual language, slightly tighter radius.

### Forms
- Labels above fields, required marked in text (not colour alone).
- Inputs 44px, 14px radius, 1px `line`; focus uses brand border + 3px brand ring.
- Errors sit directly below the field in danger colour, prefixed by an icon or clear text.
- Help/hint text uses `small` slate-500.
- Checkboxes and radios use brand accent when supported; always keyboard operable.

### Badges
Pill, 11–12px, medium weight, ring-inset. Tones: green (active/approved), amber (pending/approval), rose (rejected/overdue), slate (neutral), brand (current).

### Tables and lists
- Header: `micro`, uppercase, tracking-wide, warm paper background.
- Rows: 52–60px min height, divider `line`, hover brand-soft.
- Numeric columns right-aligned, tabular figures where supported.
- Row actions visible on focus and always visible on touch.
- Horizontal scroll with a labelled container; first column remains the identifier.

### Navigation
- Public: ink text on paper, animated 2px underline in brand, active page indicated by underline and `aria-current`.
- Admin: 44px rows, 12px radius, icon + label; active uses brand fill with white text and a soft glow.
- Both: visible focus; mobile drawer with overlay, Escape to close, focus returned to trigger.

## 8. Motion

| Token | Value | Use |
| --- | --- | --- |
| `--ease-out` | `cubic-bezier(0.22, 1, 0.36, 1)` | All UI transitions |
| fast | 150ms | Hover, focus |
| base | 240ms | Buttons, cards, nav |
| slow | 520ms | Section reveal |
| marquee | 32s linear | District/values ribbon |

Rules:
- Animate `transform` and `opacity` only.
- Entrance reveals are one-time, short and subtle (±16px, no scale bounce).
- No infinite spinning, pulsing or parallax in reading areas.
- `prefers-reduced-motion: reduce` disables marquee, reveals and transforms; content remains visible.

## 9. Accessibility

- Target WCAG 2.1 AA: body contrast ≥ 4.5:1, large text ≥ 3:1, non-text controls ≥ 3:1.
- Semantic landmarks: `header`, `nav` with label, `main`, `footer`; skip link first in tab order.
- Every control reachable and operable by keyboard; focus never removed, only restyled.
- Decorative SVG marked `aria-hidden`; meaningful icons have accessible names.
- Status messages use `role="status"` / `aria-live` where dynamic.
- Motion respects the OS preference.
- Forms: label association, `autocomplete`, error text linked with `aria-describedby`, focus to first error.

## 10. Implementation map

| Design element | Implementation |
| --- | --- |
| Fonts | `@fontsource-variable/space-grotesk`, `@fontsource-variable/plus-jakarta-sans` imported in `resources/css/app.css` |
| Tokens | Tailwind 4 `@theme` + `:root` brand variables |
| Dynamic brand | `--brand` / `--brand-accent` injected by layouts from association settings |
| Motif | Inline SVG partial/component using `currentColor` and brand vars |
| Buttons/cards/forms | Component classes in `resources/css/app.css` (`btn-*`, `card`, `input`, `badge-*`) |
| Motion | `@keyframes` + utility classes, guarded by reduced-motion media query |
| Icons | Inline SVG, 20/24px, 1.75 stroke, no icon dependency |

## 11. Do / don't

**Do**
- Let official association content lead; theme supports it.
- Use one primary action per screen area.
- Keep dark surfaces for storytelling and navigation, light surfaces for work.
- Mark placeholder copy in docs and code comments where it could be mistaken for policy.

**Don't**
- Invent official logos, colours, fees, office bearers or rule claims.
- Put white text on amber or low-contrast text on brand fills.
- Use stock sports photos as a substitute for approved association imagery.
- Add a second accent family, gradients on every card, or looping animations.
