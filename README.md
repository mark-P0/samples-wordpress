# Maison Élan — WordPress portfolio sample

A bespoke WordPress site for a fictional premium beauty and aesthetics studio.

## Architecture

Maison Élan is split into two Git-managed WordPress extensions:

- `wp-content/plugins/maison-elan-content` owns reusable content/domain behavior: Services, Specialists, Packages, Testimonials, their editorial fields, demo records, routes, and shared business details.
- `wp-content/themes/maison-elan` owns presentation: homepage composition, archive/single templates, navigation, typography, responsive layout, CSS, and JavaScript.

This keeps business content available independently of the active theme while allowing the theme to decide how that content is presented.

## Local preview

Requirements: Docker + Docker Compose.

```bash
docker compose up -d
```

Open <http://localhost:8080>, complete the normal WordPress setup, then:

1. Go to **Plugins → Installed Plugins** and activate **Maison Élan Content**.
2. Go to **Appearance → Themes** and activate **Maison Élan**.
3. Open the site homepage.

Activating the content plugin initializes the sample content and rewrite rules on a clean install. Existing Service/Specialist records are preserved and reused rather than replaced.

The portfolio build uses remote Unsplash image URLs for demo photography, so an internet connection is needed for those images to load. Production/client photography can later be moved into the WordPress media library.

## Editable content

### Content plugin

WordPress admin exposes:

- **Services** — service copy, category, duration, price, best-for label, image, benefits, inclusions, and FAQs.
- **Specialists** — profile copy, role, portrait, specialties, experience, philosophy, and education/certifications.
- **Packages** — package copy, price, included features, and featured state.
- **Testimonials** — quote and client display name.
- **Settings → Maison Élan** — reusable address, phone, Instagram, and email.

The homepage and Pricing/Studio pages query these same records and settings, so changes are reflected anywhere the content is reused.

### Theme

Go to **Appearance → Customize → Maison Élan — Homepage** for presentation-specific homepage copy such as the hero, section headings, and about text.

The theme also provisions the lightweight WordPress page records required for the `/pricing/` and `/studio/` presentation templates on an existing active-theme installation.

## Routes

- `/` — Homepage
- `/services/` — Services archive
- `/services/{service}/` — Service details
- `/specialists/` — Specialists archive
- `/specialists/{specialist}/` — Specialist profile
- `/pricing/` — Service pricing and packages
- `/studio/` — Studio details and contact

## Deployment

The Docker image includes both the custom theme and the regular content plugin. On an existing WordPress database, the **Maison Élan Content** plugin must be activated once from WordPress admin if it is not already active.

The startup command also removes the legacy `maison-elan-specialists.php` must-use plugin used during an earlier Specialists implementation, preventing duplicate registrations on persistent production volumes.

## Current scope

Implemented:

- responsive homepage
- CMS-backed homepage Services, Specialists, Packages, and Testimonials
- Services archive and detail pages
- Specialists archive and profile pages
- Pricing page backed by Service and Package records
- Studio page backed by shared business details
- shared editable business details
- development and production Docker workflow

Not included:

- booking engine
- payments
- customer accounts
