<?php
/**
 * Shared SEO / trust snippets for Bao Predictions (Accuratetip-style stack).
 */

function bao_today_label(): string {
    return date('l j F Y');
}

if (!function_exists('bao_h')) {
    function bao_h(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

function bao_last_updated_html(?string $iso = null): string {
    $iso = $iso ?: date('c');
    $label = date('j M Y, H:i', strtotime($iso)) . ' EAT';
    return '<p class="last-updated">Last updated: <time datetime="' . bao_h($iso) . '">' . bao_h($label) . '</time> · Bao Predictions Analysis Team</p>';
}

function bao_rg_notice_html(): string {
    return '<p class="rg-notice"><span class="age-badge">18+</span> Tips are informational opinions for entertainment — not financial advice, not betting tips that guarantee profit, and not a substitute for your own judgment. Confidence scores are model leans only; they are not predicted win rates. Never stake money you cannot afford to lose. <a href="/responsible-betting">Responsible betting</a>. Must be 18+ (or legal age where you live).</p>';
}

function bao_featured_banner_html(string $title, string $text): string {
    return '<aside class="featured-banner" aria-label="Editor note">'
        . '<p class="featured-kicker">' . bao_h($title) . '</p>'
        . '<p class="featured-text">' . bao_h($text) . '</p>'
        . '</aside>';
}

function bao_seo_article_html(string $title, string $bodyHtml): string {
    return '<section class="section section-muted seo-article-section">'
        . '<div class="wrap">'
        . '<article class="content-article prose">'
        . '<header class="article-header"><h2 class="article-title">' . bao_h($title) . '</h2></header>'
        . '<div class="article-content">' . $bodyHtml . '</div>'
        . '</article>'
        . '</div></section>';
}

function bao_faq_html(array $faqs, string $heading = 'FAQ'): string {
    if (!$faqs) {
        return '';
    }
    $html = '<section class="section"><div class="wrap"><h2 class="section-title">' . bao_h($heading) . '</h2><ul class="faq-list">';
    foreach ($faqs as $item) {
        $q = bao_h($item['q']);
        $a = bao_h($item['a']);
        $html .= '<li><details><summary>' . $q . '</summary><p>' . $a . '</p></details></li>';
    }
    $html .= '</ul></div></section>';
    return $html;
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

/**
 * Extra head tags (Accuratetip-style): meta title, keywords, author, date, twitter, article times.
 */
function bao_head_meta_tags(array $seo): string {
    $title = $seo['title'] ?? '';
    $titleAttr = $seo['title_php'] ?? null; // may contain live PHP date() for dated titles
    $desc = $seo['description'] ?? '';
    $keywords = $seo['keywords'] ?? '';
    $canonical = $seo['canonical'] ?? '/';
    $abs = 'https://www.baopredictions.com' . ($canonical === '/' ? '/' : $canonical);

    $titleContent = $titleAttr !== null && $titleAttr !== ''
        ? $titleAttr
        : bao_h($title);

    $out = '';
    if ($title !== '' || $titleAttr) {
        $out .= '<meta name="title" content="' . $titleContent . '">' . "\n  ";
    }
    if ($keywords !== '') {
        $out .= '<meta name="keywords" content="' . bao_h($keywords) . '">' . "\n  ";
    }
    $out .= '<meta name="author" content="Bao Predictions Analysis Team">' . "\n  ";
    // Emit PHP date tags into page source (split closer so this file stays pure PHP).
    $phpDate = '<?php echo date(' . "'Y-m-d'" . '); ?' . '>';
    $phpIso = '<?php echo date(' . "'c'" . '); ?' . '>';
    $out .= '<meta name="date" content="' . $phpDate . '">' . "\n  ";
    $out .= '<meta property="article:published_time" content="' . $phpIso . '">' . "\n  ";
    $out .= '<meta property="article:modified_time" content="' . $phpIso . '">' . "\n  ";
    $out .= '<meta property="article:author" content="Bao Predictions">' . "\n  ";
    $out .= '<meta name="twitter:card" content="summary_large_image">' . "\n  ";
    $out .= '<meta name="twitter:title" content="' . $titleContent . '">' . "\n  ";
    $out .= '<meta name="twitter:description" content="' . bao_h($desc) . '">' . "\n  ";
    $out .= '<link rel="alternate" hreflang="en" href="' . bao_h($abs) . '">';
    return $out;
}
