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

/**
 * Load a jackpot sheet payload and resolve game count from the live fixtures array
 * (never a separate hardcoded strip number). Config expected_games is fallback only.
 *
 * @return array{
 *   payload:?array<string,mixed>,
 *   count:int,
 *   label:string,
 *   schedule:string,
 *   prize_label:string
 * }
 */
function bao_jackpot_sheet(string $slug, string $apiPath): array {
    require_once __DIR__ . '/api-curl.php';
    $all = require dirname(__DIR__) . '/config/jackpots.php';
    $meta = is_array($all[$slug] ?? null) ? $all[$slug] : [];
    $payload = bao_curl_api($apiPath);
    $live = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
        ? count($payload['games'])
        : 0;
    $expected = (int) ($meta['expected_games'] ?? 0);
    $prize = $meta['prize_label'] ?? null;

    return [
        'payload' => $payload,
        'count' => $live > 0 ? $live : $expected,
        'label' => (string) ($meta['label'] ?? 'Jackpot'),
        'schedule' => ucfirst((string) ($meta['schedule'] ?? 'open')),
        'prize_label' => $prize !== null && $prize !== '' ? (string) $prize : 'varies',
    ];
}

function bao_jackpot_lede_html(array $sheet): string {
    return '<p class="lede">' . (int) $sheet['count'] . ' games · '
        . bao_h((string) $sheet['schedule']) . ' · Prize pool '
        . bao_h((string) $sheet['prize_label']) . '</p>';
}

/**
 * Dynamic one-liner naming the actual top picks on a shortlist page
 * (never hardcoded example clubs).
 *
 * @param list<array<string,mixed>> $games
 */
function bao_shortlist_summary_html(array $games, string $label = 'shortlist'): string {
    if (!$games) {
        return '<p>No ' . bao_h($label) . ' picks published for this board yet.</p>';
    }
    $top = array_slice($games, 0, 3);
    $bits = [];
    foreach ($top as $g) {
        $home = trim((string) ($g['home'] ?? 'Home'));
        $away = trim((string) ($g['away'] ?? 'Away'));
        $pick = trim((string) ($g['pick'] ?? 'lean'));
        $conf = (int) ($g['confidence'] ?? 0);
        $bits[] = bao_h($home) . ' vs ' . bao_h($away)
            . ' (' . bao_h($pick) . ($conf > 0 ? ', ' . $conf . '%' : '') . ')';
    }
    $n = count($games);
    if (count($bits) === 1) {
        $lead = 'Today\'s ' . bao_h($label) . ' is led by ' . $bits[0] . '.';
    } elseif (count($bits) === 2) {
        $lead = 'Today\'s ' . bao_h($label) . ' is led by ' . $bits[0] . ' and ' . $bits[1] . '.';
    } else {
        $lead = 'Today\'s ' . bao_h($label) . ' is led by ' . $bits[0]
            . ', then ' . $bits[1] . ', with ' . $bits[2] . ' also clearing the bar.';
    }
    if ($n > 3) {
        $lead .= ' ' . $n . ' picks published on this board.';
    }
    return '<p>' . $lead . '</p>';
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
