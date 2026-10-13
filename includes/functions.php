<?php
declare(strict_types=1);

require_once __DIR__ . '/data.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function path_url(string $path = ''): string
{
    return SITE_URL . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}

function service_url(string $slug): string
{
    return '/' . $slug;
}

function icon(string $name, string $class = ''): string
{
    $paths = [
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92z"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'drop' => '<path d="M12 2S5 9.4 5 15a7 7 0 0 0 14 0c0-5.6-7-13-7-13z"/><path d="M9 16a3 3 0 0 0 3 3"/>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
        'meter' => '<path d="M4 19a8 8 0 1 1 16 0"/><path d="m12 15 3-5M8 19h8"/>',
        'document' => '<path d="M6 2h8l4 4v16H6z"/><path d="M14 2v5h5M9 13h6M9 17h6"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'x' => '<path d="m6 6 12 12M18 6 6 18"/>',
        'pin' => '<path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0z"/><circle cx="12" cy="10" r="2.5"/>',
        'maple' => '<path d="m12 2 1.7 3.7 3.4-1.1-.9 3.8 3.3 1.3-3 2.5 1.5 2.7-4.7-.8.3 5.9h-3.2l.3-5.9-4.7.8 1.5-2.7-3-2.5 3.3-1.3-.9-3.8 3.4 1.1z"/>',
    ];
    $body = $paths[$name] ?? $paths['check'];
    return '<svg class="icon ' . e($class) . '" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>';
}

function render_header(array $meta, string $currentPath, array $extraSchema = []): void
{
    global $services;
    $metaTitle = $meta['title'] . ' | (604) 800-3900';
    $canonical = path_url($currentPath);
    $socialImage = path_url($meta['image'] ?? 'assets/images/emergency-water-damage-living-room.webp');
    $robots = $meta['robots'] ?? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
    $pageType = $meta['schema_type'] ?? 'WebPage';
    $schemas = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => SITE_URL . '/#organization',
            'name' => SITE_NAME,
            'url' => SITE_URL . '/',
            'telephone' => PHONE_LINK,
            'description' => '24/7 water damage restoration, water extraction, flood cleanup and structural drying for West Vancouver homes and businesses.',
            'image' => path_url('assets/images/emergency-water-damage-living-room.webp'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => path_url('assets/images/favicon.svg'),
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'West Vancouver',
                'sameAs' => 'https://en.wikipedia.org/wiki/West_Vancouver',
            ],
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'telephone' => PHONE_LINK,
                'contactType' => 'emergency service',
                'areaServed' => 'CA-BC',
                'availableLanguage' => ['English'],
            ]],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => SITE_URL . '/#website',
            'url' => SITE_URL . '/',
            'name' => SITE_NAME,
            'inLanguage' => 'en-CA',
            'publisher' => ['@id' => SITE_URL . '/#organization'],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => $pageType,
            '@id' => $canonical . '#webpage',
            'url' => $canonical,
            'name' => $metaTitle,
            'description' => $meta['description'],
            'inLanguage' => 'en-CA',
            'isPartOf' => ['@id' => SITE_URL . '/#website'],
            'about' => ['@id' => SITE_URL . '/#organization'],
            'primaryImageOfPage' => [
                '@type' => 'ImageObject',
                'url' => $socialImage,
            ],
            'dateModified' => '2026-10-01',
        ],
    ];
    foreach ($extraSchema as $schema) {
        $schemas[] = $schema;
    }
    ?>
<!doctype html>
<html lang="en-CA">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($meta['description']) ?>">
    <meta name="robots" content="<?= e($robots) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:locale" content="en_CA">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($meta['description']) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($socialImage) ?>">
    <meta property="og:image:alt" content="Professional water damage restoration work in West Vancouver">
    <meta property="og:image:type" content="image/webp">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTitle) ?>">
    <meta name="twitter:description" content="<?= e($meta['description']) ?>">
    <meta name="twitter:image" content="<?= e($socialImage) ?>">
    <meta name="twitter:image:alt" content="Professional water damage restoration work in West Vancouver">
    <meta name="theme-color" content="#071f2e">
    <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="preload" href="/assets/css/site.css" as="style">
    <link rel="stylesheet" href="/assets/css/site.css">
    <?php foreach ($schemas as $schema): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endforeach; ?>
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>
<div class="emergency-bar">
    <div class="container emergency-bar__inner">
        <span><?= icon('clock') ?> Proudly Canadian · 24/7 emergency response across West Vancouver</span>
        <a href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> <?= e(PHONE_DISPLAY) ?></a>
    </div>
</div>
<header class="site-header" data-header>
    <div class="container nav-wrap">
        <a class="brand" href="/" aria-label="<?= e(SITE_NAME) ?> home">
            <span class="brand__mark"><?= icon('drop') ?></span>
            <span><strong>West Vancouver</strong><small>Water Damage</small></span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav" data-nav-toggle>
            <span class="sr-only">Open menu</span><?= icon('menu', 'menu-open') ?><?= icon('x', 'menu-close') ?>
        </button>
        <nav class="main-nav" id="main-nav" aria-label="Primary navigation" data-nav>
            <a class="<?= $currentPath === '' ? 'is-active' : '' ?>" href="/">Home</a>
            <div class="nav-dropdown">
                <button type="button" aria-expanded="false" data-services-toggle>Services <span aria-hidden="true">⌄</span></button>
                <div class="nav-dropdown__panel">
                    <a href="/services"><strong>View all services</strong><span>Complete water damage help</span></a>
                    <?php foreach ($services as $slug => $service): ?>
                        <a href="/<?= e($slug) ?>"><?= e($service['nav']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <a class="<?= $currentPath === 'areas-we-serve' ? 'is-active' : '' ?>" href="/areas-we-serve">Areas We Serve</a>
            <a class="<?= $currentPath === 'about' ? 'is-active' : '' ?>" href="/about">About</a>
            <a class="<?= $currentPath === 'contact' ? 'is-active' : '' ?>" href="/contact">Contact</a>
            <a class="button button--small button--nav" href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> Call Now</a>
        </nav>
    </div>
</header>
<main id="main-content">
<?php
}

function render_quick_form(string $prefix, bool $compact = false): void
{
    ?>
    <form class="quick-form<?= $compact ? ' quick-form--compact' : '' ?>" action="/contact-submit.php" method="post">
        <div class="field"><label for="<?= e($prefix) ?>-name">Full Name <span aria-hidden="true">*</span></label><input id="<?= e($prefix) ?>-name" name="name" type="text" autocomplete="name" required></div>
        <div class="field"><label for="<?= e($prefix) ?>-phone">Phone Number <span aria-hidden="true">*</span></label><input id="<?= e($prefix) ?>-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required></div>
        <div class="field"><label for="<?= e($prefix) ?>-email">Email <span aria-hidden="true">*</span></label><input id="<?= e($prefix) ?>-email" name="email" type="email" autocomplete="email" required></div>
        <div class="field"><label for="<?= e($prefix) ?>-message">Message <span aria-hidden="true">*</span></label><textarea id="<?= e($prefix) ?>-message" name="message" rows="4" required></textarea></div>
        <button class="button button--primary" type="submit">Get My Free Inspection <?= icon('arrow') ?></button>
    </form>
    <?php
}

function render_hero_form(string $prefix): void
{
    ?>
    <aside class="hero-form" id="free-inspection" aria-labelledby="<?= e($prefix) ?>-form-title">
        <span class="eyebrow">Request a callback</span>
        <h2 id="<?= e($prefix) ?>-form-title">Get your free inspection</h2>
        <p>Send your contact details and we’ll follow up about your West Vancouver property.</p>
        <?php render_quick_form($prefix); ?>
        <small>Free inspection · No obligation</small>
    </aside>
    <?php
}

function render_canadian_badge(string $context = 'Serving West Vancouver'): void
{
    ?>
    <div class="canadian-badge"><?= icon('maple') ?><span><strong>Proudly Canadian</strong> · <?= e($context) ?></span></div>
    <?php
}

function render_page_photo(string $image, string $alt, string $caption, string $class = ''): void
{
    $dimensions = [
        'emergency-water-damage-living-room.webp' => [1920, 1080],
        'basement-flood-cleanup.webp' => [1920, 1080],
        'ceiling-water-damage.webp' => [1920, 1080],
        'living-room-structural-drying.webp' => [1600, 1200],
        'bathroom-structural-drying.webp' => [1200, 1510],
        'restoration-drying-equipment.webp' => [1373, 1146],
        'water-damage-wall-restoration.webp' => [1200, 1600],
    ];
    [$width, $height] = $dimensions[$image] ?? [1600, 1000];
    ?>
    <figure class="page-photo<?= $class !== '' ? ' ' . e($class) : '' ?>">
        <img src="/assets/images/<?= e($image) ?>" width="<?= $width ?>" height="<?= $height ?>" loading="lazy" alt="<?= e($alt) ?>">
        <figcaption><?= e($caption) ?></figcaption>
    </figure>
    <?php
}

function render_footer(): void
{
    global $services, $neighbourhoods;
    ?>
</main>
<section class="final-cta">
    <div class="container final-cta__inner">
        <div>
            <span class="eyebrow eyebrow--light">Proudly Canadian · Available 24 hours</span>
            <h2>Water is moving. Your response should too.</h2>
            <p>Speak with a West Vancouver water damage team, get clear next steps and arrange a free inspection.</p>
        </div>
        <div class="final-cta__actions">
            <a class="button button--white" href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> <?= e(PHONE_DISPLAY) ?></a>
            <a class="button button--outline-light" href="/contact">Get a free inspection</a>
        </div>
    </div>
</section>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a class="brand brand--footer" href="/">
                <span class="brand__mark"><?= icon('drop') ?></span>
                <span><strong>West Vancouver</strong><small>Water Damage</small></span>
            </a>
            <p>Proudly Canadian and locally focused on West Vancouver. Emergency water removal, professional drying and practical restoration support for homes and businesses.</p>
            <a class="footer-phone" href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> <?= e(PHONE_DISPLAY) ?></a>
        </div>
        <div>
            <h2 class="footer-heading">Services</h2>
            <ul class="footer-links">
                <?php foreach (array_slice($services, 0, 5, true) as $slug => $service): ?>
                    <li><a href="/<?= e($slug) ?>"><?= e($service['nav']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h2 class="footer-heading">More help</h2>
            <ul class="footer-links">
                <?php foreach (array_slice($services, 5, null, true) as $slug => $service): ?>
                    <li><a href="/<?= e($slug) ?>"><?= e($service['nav']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h2 class="footer-heading">Local coverage</h2>
            <p><?= e(implode(' · ', $neighbourhoods)) ?></p>
            <a class="text-link text-link--light" href="/areas-we-serve">Explore our service area <?= icon('arrow') ?></a>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved by <a href="https://vrrestoration.ca/en">VR Restoration</a>. Design and develop by <a href="https://webpulse.ca/">webpulse.ca</a></p>
        <div><a href="/privacy">Privacy</a><a href="/sitemap.xml">Sitemap</a><a href="/llms.txt">AI Site Guide</a></div>
    </div>
</footer>
<a class="mobile-call" href="tel:<?= e(PHONE_LINK) ?>"><?= icon('phone') ?> Call 24/7: <?= e(PHONE_DISPLAY) ?></a>
<script src="/assets/js/site.js" defer></script>
</body>
</html>
<?php
}

function breadcrumb_schema(array $items): array
{
    $list = [];
    foreach ($items as $index => $item) {
        $list[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item[0],
            'item' => path_url($item[1]),
        ];
    }
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
}

function faq_schema(array $faqs): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(static fn(array $faq): array => [
            '@type' => 'Question',
            'name' => $faq[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq[1]],
        ], $faqs),
    ];
}

function service_list_schema(array $services): array
{
    $items = [];
    $position = 1;
    foreach ($services as $slug => $service) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $service['nav'],
            'url' => path_url($slug),
        ];
    }
    return [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Water damage services in West Vancouver',
        'itemListElement' => $items,
    ];
}

function render_breadcrumbs(array $items): void
{
    echo '<nav class="breadcrumbs container" aria-label="Breadcrumb"><ol>';
    foreach ($items as $index => $item) {
        $last = $index === array_key_last($items);
        echo '<li>';
        if ($last) {
            echo '<span aria-current="page">' . e($item[0]) . '</span>';
        } else {
            echo '<a href="/' . e(ltrim($item[1], '/')) . '">' . e($item[0]) . '</a><span aria-hidden="true">/</span>';
        }
        echo '</li>';
    }
    echo '</ol></nav>';
}

function render_faqs(array $faqs, string $heading = 'Frequently asked questions'): void
{
    ?>
    <section class="section section--mist" id="faqs">
        <div class="container split-heading">
            <div>
                <span class="eyebrow">Clear answers</span>
                <h2><?= e($heading) ?></h2>
            </div>
            <p>These general answers help you plan the first steps. Site conditions and insurance policies vary.</p>
        </div>
        <div class="container faq-list">
            <?php foreach ($faqs as $index => $faq): ?>
                <details <?= $index === 0 ? 'open' : '' ?>>
                    <summary><?= e($faq[0]) ?><span aria-hidden="true">+</span></summary>
                    <div><p><?= e($faq[1]) ?></p></div>
                </details>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

function render_service_cards(array $slugs, string $heading = ''): void
{
    global $services;
    if ($heading !== '') {
        echo '<h2>' . e($heading) . '</h2>';
    }
    echo '<div class="service-grid">';
    foreach ($slugs as $slug) {
        $service = $services[$slug];
        echo '<article class="service-card"><span class="service-card__icon">' . icon('drop') . '</span><h3>' . e($service['nav']) . '</h3><p>' . e($service['description']) . '</p><a class="text-link" href="/' . e($slug) . '">Learn about ' . e(strtolower($service['nav'])) . ' ' . icon('arrow') . '</a></article>';
    }
    echo '</div>';
}
