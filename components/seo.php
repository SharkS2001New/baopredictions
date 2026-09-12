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

/** Lead analyst — used in bylines, About, and Person schema. */
function bao_lead_analyst(): array {
    return [
        'name' => 'Stephen Karuku',
        'job_title' => 'Lead Analyst',
        'url' => 'https://www.baopredictions.com/about-us#stephen-karuku',
        'works_for' => 'Bao Predictions',
    ];
}

function bao_last_updated_html(?string $iso = null): string {
    $iso = $iso ?: date('c');
    $label = date('j M Y, H:i', strtotime($iso)) . ' EAT';
    $analyst = bao_lead_analyst();
    return '<p class="last-updated">Last updated <time datetime="' . bao_h($iso) . '">' . bao_h($label) . '</time>'
        . ' · By <a href="' . bao_h($analyst['url']) . '">' . bao_h($analyst['name']) . '</a>'
        . ', ' . bao_h($analyst['job_title']) . '</p>';
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
 * Previous jackpot round with settled ✅/❌ (when a newer round has replaced it).
 *
 * @param array<string,mixed>|null $payload
 */
function bao_jackpot_previous_results_html(?array $payload): string {
    if ($payload === null) {
        return '';
    }
    $games = $payload['previous_games'] ?? null;
    if (!is_array($games) || $games === []) {
        return '';
    }
    require_once __DIR__ . '/match-cards.php';
    $hits = 0;
    $settled = 0;
    foreach ($games as $g) {
        if (!is_array($g)) {
            continue;
        }
        $won = $g['won'] ?? null;
        $wonDc = array_key_exists('won_dc', $g) ? $g['won_dc'] : null;
        // Combined result: DC cover counts as a win for the game.
        if ($won === true || $wonDc === true) {
            $hits++;
            $settled++;
        } elseif ($won === false && ($wonDc === false || $wonDc === null)) {
            $settled++;
        } elseif ($won === null && $wonDc === false) {
            $settled++;
        }
    }
    $summary = $settled > 0
        ? ($hits . '/' . $settled . ' correct (1X2 or DC)')
        : 'Scores update as fixtures finish';
    $html = '<section class="jackpot-previous section-tight" aria-labelledby="jackpot-previous-title">';
    $html .= '<h2 id="jackpot-previous-title" class="at-matches-title">Previous round results</h2>';
    $html .= '<p class="text-muted mb-md">' . bao_h($summary) . ' · Tip format 1 | 1X · ✅ hit · ❌ miss</p>';
    $html .= bao_matches_html($games, [
        'show_date' => true,
        'page' => (string) ($payload['page'] ?? '') . '-previous',
    ]);
    $html .= '</section>';
    return $html;
}

/**
 * Dynamic one-liner naming the actual top picks on a shortlist page
 * (never hardcoded example clubs).
 *
 * @param list<array<string,mixed>> $games
 */
/**
 * Dynamic shortlist lead-in for SEO sections.
 *
 * @param list<array<string,mixed>> $games
 * @param string $label e.g. "early board", "must-win shortlist"
 * @param string $dayPossessive e.g. "Today's", "Tomorrow's", "Yesterday's", "This weekend's"
 */
function bao_shortlist_summary_html(array $games, string $label = 'shortlist', string $dayPossessive = "Today's"): string {
    if (!$games) {
        return '<p>No ' . bao_h($label) . ' picks published for this board yet.</p>';
    }
    // Always name the strongest published leans — not whatever order the board uses (kickoff vs confidence).
    $ranked = $games;
    usort($ranked, static function (array $a, array $b): int {
        $ca = (int) ($a['confidence'] ?? 0);
        $cb = (int) ($b['confidence'] ?? 0);
        if ($ca !== $cb) {
            return $cb <=> $ca;
        }
        return ((int) ($a['fixture_id'] ?? 0)) <=> ((int) ($b['fixture_id'] ?? 0));
    });
    $top = array_slice($ranked, 0, 3);
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
    $prefix = bao_h($dayPossessive) . ' ' . bao_h($label);
    if (count($bits) === 1) {
        $lead = $prefix . ' is led by ' . $bits[0] . '.';
    } elseif (count($bits) === 2) {
        $lead = $prefix . ' is led by ' . $bits[0] . ' and ' . $bits[1] . '.';
    } else {
        $lead = $prefix . ' is led by ' . $bits[0]
            . ', then ' . $bits[1] . ', with ' . $bits[2] . ' also clearing the bar.';
    }
    if ($n > 3) {
        $lead .= ' ' . $n . ' picks published on this board.';
    }
    return '<p>' . $lead . '</p>';
}

/**
 * Live win + loss examples from a settled board (verification copy).
 *
 * @param list<array<string,mixed>> $games
 */
function bao_settled_audit_examples_html(array $games): string {
    $win = null;
    $loss = null;
    foreach ($games as $g) {
        if (!is_array($g) || ($g['won'] ?? null) === null) {
            continue;
        }
        if ($g['won'] === true && $win === null) {
            $win = $g;
        } elseif ($g['won'] === false && $loss === null) {
            $loss = $g;
        }
        if ($win !== null && $loss !== null) {
            break;
        }
    }
    if ($win === null && $loss === null) {
        return '<p>As fixtures settle, each card above keeps the original prediction beside the final score — wins and losses both.</p>';
    }

    $fmt = static function (array $g): string {
        $home = trim((string) ($g['home'] ?? 'Home'));
        $away = trim((string) ($g['away'] ?? 'Away'));
        $score = trim((string) ($g['score'] ?? ''));
        $league = trim((string) ($g['league'] ?? 'Football'));
        $pick = trim((string) ($g['pick'] ?? 'selection'));
        $conf = isset($g['confidence']) ? (int) $g['confidence'] : 0;
        $line = '<strong>' . bao_h($home);
        if ($score !== '') {
            $line .= ' ' . bao_h($score) . ' ' . bao_h($away) . '</strong>';
        } else {
            $line .= ' vs ' . bao_h($away) . '</strong>';
        }
        $line .= ' in ' . bao_h($league) . ' with a ' . bao_h($pick) . ' selection';
        if ($conf > 0) {
            $line .= ' and a ' . $conf . '% model lean';
        }
        return $line;
    };

    $html = '<p>For example, the current archive';
    if ($win !== null) {
        $html .= ' records ' . $fmt($win) . '.';
    }
    if ($loss !== null) {
        $html .= ($win !== null ? ' It also shows unsuccessful calls, such as ' : ' records ')
            . $fmt($loss)
            . ', where the published prediction did not match the result.';
    } else {
        $html .= ' Losing calls stay listed alongside winning ones when they settle.';
    }
    $html .= '</p>';
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
    $analyst = bao_lead_analyst();
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Bao Predictions',
        'alternateName' => 'Bao Predictions Analysis Team',
        'url' => 'https://www.baopredictions.com',
        'logo' => 'https://www.baopredictions.com/assets/img/logo-mark.png',
        'description' => 'Kenya-facing football predictions and jackpot analysis with a public track record of wins and losses.',
        'areaServed' => 'KE',
        'knowsAbout' => [
            'Football predictions',
            'SportPesa Mega Jackpot',
            'Betika Midweek Jackpot',
            'FKF Premier League',
        ],
        'employee' => [
            '@type' => 'Person',
            'name' => $analyst['name'],
            'jobTitle' => $analyst['job_title'],
            'url' => $analyst['url'],
        ],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

function bao_person_schema(): string {
    $analyst = bao_lead_analyst();
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $analyst['name'],
        'jobTitle' => $analyst['job_title'],
        'url' => $analyst['url'],
        'worksFor' => [
            '@type' => 'Organization',
            'name' => $analyst['works_for'],
            'url' => 'https://www.baopredictions.com',
        ],
        'knowsAbout' => [
            'Football predictions',
            'Football betting markets',
            'Kenya jackpot tips',
        ],
    ];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

function bao_article_schema(string $headline, string $description, string $url): string {
    $analyst = bao_lead_analyst();
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $headline,
        'description' => $description,
        'datePublished' => date('c'),
        'dateModified' => date('c'),
        'author' => [
            '@type' => 'Person',
            'name' => $analyst['name'],
            'jobTitle' => $analyst['job_title'],
            'url' => $analyst['url'],
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
