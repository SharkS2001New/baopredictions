<?php
/**
 * Small shared snippets used by pages (last-updated, 18+ notice, JSON-LD).
 * Page titles/descriptions/write-ups live in each pages/*.php file — not here.
 */

if (!function_exists('bao_h')) {
    function bao_h(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

function bao_last_updated_html(?string $iso = null): string {
    $iso = $iso ?: date('c');
    $label = date('j M Y, H:i', strtotime($iso)) . ' EAT';
    return '<p class="last-updated">Last updated <time datetime="' . bao_h($iso) . '">' . bao_h($label) . '</time>'
        . ' · By <a href="/about-us">Bao Predictions Analysis Team</a></p>';
}

function bao_rg_notice_html(): string {
    return '<p class="rg-notice"><span class="age-badge">18+</span> Tips are informational opinions for entertainment — not financial advice, not betting tips that guarantee profit, and not a substitute for your own judgment. Confidence scores are model leans only; they are not predicted win rates. Never stake money you cannot afford to lose. <a href="/responsible-betting">Responsible betting</a>. Must be 18+ (or legal age where you live).</p>';
}

function bao_faq_schema(array $faqs): string {
    if (!$faqs) {
        return '';
    }
    $entities = [];
    foreach ($faqs as $item) {
        $entities[] = [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ];
    }
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $entities,
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

function bao_breadcrumb_schema(array $crumbs): string {
    $items = [];
    $i = 1;
    foreach ($crumbs as $crumb) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $i++,
            'name' => $crumb['name'],
            'item' => 'https://www.baopredictions.com' . $crumb['url'],
        ];
    }
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

function bao_organization_schema(): string {
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Bao Predictions',
        'url' => 'https://www.baopredictions.com',
        'logo' => 'https://www.baopredictions.com/assets/img/logo-mark.png',
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES) . '</script>';
}

function bao_article_schema(string $headline, string $description, string $url): string {
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $headline,
        'description' => $description,
        'datePublished' => date('c'),
        'dateModified' => date('c'),
        'author' => [
            '@type' => 'Organization',
            'name' => 'Bao Predictions',
            'url' => 'https://www.baopredictions.com',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Bao Predictions',
            'url' => 'https://www.baopredictions.com',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => 'https://www.baopredictions.com/assets/img/logo-mark.png',
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => 'https://www.baopredictions.com' . $url,
        ],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}
