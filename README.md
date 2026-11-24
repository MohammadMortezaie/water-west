# VR Plus Restoration — West Vancouver Water Damage

Server-rendered PHP website for `waterdamagewestvancouver.ca`.

## Local development

```sh
php -S 127.0.0.1:8010 index.php
```

## SEO regression test

With the local server running:

```sh
php tests/seo-smoke.php http://127.0.0.1:8010
```

The smoke test crawls every sitemap URL and checks HTTP status, title suffixes, canonical URLs, meta descriptions, H1 usage, JSON-LD syntax and types, image accessibility, duplicate IDs, internal links, and 404 directives.

## Deployment notes

- Increment `ASSET_VERSION` in `includes/data.php` whenever CSS, JavaScript, or the favicon changes.
- Keep every HTML, Open Graph, and Twitter title ending with ` | (604) 800-3900`.
- Keep `sitemap.xml` `lastmod` dates accurate; do not update them without a meaningful page change.
- The production server is nginx. Mirror the cache and redirect policies in `.htaccess` in the nginx configuration because nginx does not read `.htaccess`.
