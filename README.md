# Maison Élan — WordPress portfolio sample

A bespoke WordPress homepage for a fictional premium beauty and aesthetics studio. The design is based on the Maison Élan concept created for this sample project.

## Local preview

Requirements: Docker + Docker Compose.

```bash
docker compose up -d
```

Open <http://localhost:8080>, complete the normal WordPress setup, then:

1. Go to **Appearance → Themes**.
2. Activate **Maison Élan**.
3. Open the site homepage.

The theme includes demo homepage content by default, so no imported database or plugin is required to see the design.

## Editable homepage copy

Go to **Appearance → Customize → Maison Élan — Homepage** to change the hero copy, section headings, about copy and contact details.

Custom post types for Services, Specialists, Packages and Testimonials are also registered in the admin as the foundation for the next implementation pass. The current homepage intentionally uses deterministic fallback demo data so the design works immediately on a clean install.

## Scope

This iteration implements **Home only**:

- responsive header/navigation
- hero
- services
- studio/about
- specialists
- pricing packages
- testimonials
- visit/contact CTA
- footer

No booking engine, payments, customer accounts or production deployment are included.
