<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

$baseUrl = rtrim($argv[1] ?? 'http://127.0.0.1:8010', '/');
$failures = [];
$checkedLinks = [];
$checkedAssets = [];

function fetch_url(string $url): array
{
    $context = stream_context_create([
        'http' => [
            'ignore_errors' => true,
            'follow_location' => 0,
            'timeout' => 10,
            'user_agent' => 'Local SEO smoke test',
        ],
    ]);
    $body = file_get_contents($url, false, $context);
    $headers = $http_response_header ?? [];
    preg_match('/\s(\d{3})\s/', $headers[0] ?? '', $match);

    return [(int) ($match[1] ?? 0), $body === false ? '' : $body, $headers];
}

function xpath_text(DOMXPath $xpath, string $query): string
{
    $node = $xpath->query($query)?->item(0);
    return $node instanceof DOMNode ? trim($node->textContent) : '';
}

function attribute_value(DOMXPath $xpath, string $query, string $attribute): string
{
    $node = $xpath->query($query)?->item(0);
    return $node instanceof DOMElement ? trim($node->getAttribute($attribute)) : '';
}

function fail(array &$failures, string $message): void
{
    $failures[] = $message;
}

$sitemap = simplexml_load_file(__DIR__ . '/../sitemap.xml');
if ($sitemap === false) {
    fwrite(STDERR, "Could not parse sitemap.xml\n");
    exit(1);
}

$paths = [];
foreach ($sitemap->url as $entry) {
    $path = parse_url((string) $entry->loc, PHP_URL_PATH) ?: '/';
    $paths[] = $path;
}

foreach ($paths as $path) {
    [$status, $html] = fetch_url($baseUrl . $path);
    if ($status !== 200) {
        fail($failures, "$path returned $status instead of 200");
        continue;
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);

    $title = xpath_text($xpath, '//title');
    if (!str_ends_with($title, TITLE_SUFFIX)) {
        fail($failures, "$path title does not end with the required phone suffix");
    }
    foreach (['og:title', 'twitter:title'] as $property) {
        $query = $property === 'og:title'
            ? '//meta[@property="og:title"]'
            : '//meta[@name="twitter:title"]';
        if (!str_ends_with(attribute_value($xpath, $query, 'content'), TITLE_SUFFIX)) {
            fail($failures, "$path $property does not end with the required phone suffix");
        }
    }

    $expectedCanonical = SITE_URL . ($path === '/' ? '/' : $path);
    $canonical = attribute_value($xpath, '//link[@rel="canonical"]', 'href');
    if ($canonical !== $expectedCanonical) {
        fail($failures, "$path canonical is '$canonical', expected '$expectedCanonical'");
    }
    if (attribute_value($xpath, '//meta[@name="description"]', 'content') === '') {
        fail($failures, "$path is missing a meta description");
    }
    if ($xpath->query('//h1')->length !== 1) {
        fail($failures, "$path must contain exactly one H1");
    }

    $ids = [];
    foreach ($xpath->query('//*[@id]') as $node) {
        $id = $node instanceof DOMElement ? $node->getAttribute('id') : '';
        if ($id !== '' && isset($ids[$id])) {
            fail($failures, "$path contains duplicate id '$id'");
        }
        $ids[$id] = true;
    }

    $schemaTypes = [];
    foreach ($xpath->query('//script[@type="application/ld+json"]') as $node) {
        $schema = json_decode($node->textContent, true);
        if (!is_array($schema)) {
            fail($failures, "$path contains invalid JSON-LD");
            continue;
        }
        $schemaTypes[] = $schema['@type'] ?? '';
    }
    if (!in_array('HomeAndConstructionBusiness', $schemaTypes, true)) {
        fail($failures, "$path is missing HomeAndConstructionBusiness schema");
    }
    if (in_array('FAQPage', $schemaTypes, true)) {
        fail($failures, "$path includes ineligible FAQPage rich-result markup");
    }

    foreach ($xpath->query('//img') as $image) {
        if (!$image instanceof DOMElement) {
            continue;
        }
        if ($image->getAttribute('alt') === '') {
            fail($failures, "$path contains an image without alt text");
        }
        if ($image->getAttribute('width') === '' || $image->getAttribute('height') === '') {
            fail($failures, "$path contains an image without intrinsic dimensions");
        }
    }

    foreach ($xpath->query('//*[@src] | //link[@href]') as $resource) {
        if (!$resource instanceof DOMElement) {
            continue;
        }
        $url = $resource->hasAttribute('src') ? $resource->getAttribute('src') : $resource->getAttribute('href');
        $assetPath = parse_url($url, PHP_URL_PATH) ?: '';
        if (str_starts_with($assetPath, '/assets/')) {
            $checkedAssets[$assetPath] = true;
        }
    }
    foreach ($xpath->query('//*[@srcset]') as $resource) {
        if (!$resource instanceof DOMElement) {
            continue;
        }
        foreach (explode(',', $resource->getAttribute('srcset')) as $candidate) {
            $url = trim(explode(' ', trim($candidate))[0]);
            $assetPath = parse_url($url, PHP_URL_PATH) ?: '';
            if (str_starts_with($assetPath, '/assets/')) {
                $checkedAssets[$assetPath] = true;
            }
        }
    }

    foreach ($xpath->query('//a[@href]') as $link) {
        if (!$link instanceof DOMElement) {
            continue;
        }
        $href = $link->getAttribute('href');
        if (!str_starts_with($href, '/') || str_starts_with($href, '//')) {
            continue;
        }
        $linkPath = parse_url($href, PHP_URL_PATH) ?: '/';
        if (!str_starts_with($linkPath, '/assets/')) {
            $checkedLinks[$linkPath] = true;
        }
    }
}

foreach (array_keys($checkedLinks) as $path) {
    [$status] = fetch_url($baseUrl . $path);
    if ($status >= 400 || $status === 0) {
        fail($failures, "Internal link $path returned $status");
    }
}

foreach (array_keys($checkedAssets) as $path) {
    [$status] = fetch_url($baseUrl . $path);
    if ($status !== 200) {
        fail($failures, "Referenced asset $path returned $status");
    }
}

[$notFoundStatus, $notFoundHtml] = fetch_url($baseUrl . '/seo-test-missing-page');
if ($notFoundStatus !== 404) {
    fail($failures, "Missing-page test returned $notFoundStatus instead of 404");
}
$notFoundDom = new DOMDocument();
libxml_use_internal_errors(true);
$notFoundDom->loadHTML($notFoundHtml);
libxml_clear_errors();
$notFoundXpath = new DOMXPath($notFoundDom);
if (!str_ends_with(xpath_text($notFoundXpath, '//title'), TITLE_SUFFIX)) {
    fail($failures, '404 title does not end with the required phone suffix');
}
if ($notFoundXpath->query('//link[@rel="canonical"]')->length !== 0) {
    fail($failures, '404 page must not declare a canonical URL');
}
if (!str_contains(attribute_value($notFoundXpath, '//meta[@name="robots"]', 'content'), 'noindex')) {
    fail($failures, '404 page must include noindex');
}

if ($failures !== []) {
    fwrite(STDERR, implode("\n", array_map(static fn(string $failure): string => "FAIL: $failure", $failures)) . "\n");
    exit(1);
}

printf("SEO smoke test passed: %d sitemap pages, %d internal routes, and %d assets checked.\n", count($paths), count($checkedLinks), count($checkedAssets));
