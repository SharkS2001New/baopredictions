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

/** Lead analyst — used in bylines, About, analyst card, and Person schema. */
function bao_lead_analyst(): array {
    return [
        'name' => 'Stephen Karuku',
        'initials' => 'SK',
        'job_title' => 'Lead Analyst',
        'role_line' => 'Lead Football Analyst — Bao Predictions Editorial',
        'url' => 'https://www.baopredictions.com/about-us#stephen-karuku',
        'works_for' => 'Bao Predictions',
        'bio' => 'Stephen reviews published tip boards and jackpot sheets at Bao Predictions: model output starts the process, then he checks team news, rotation risk and price context before a card goes live. Every selection is an opinion based on available match data — not a guaranteed result.',
    ];
}

/**
 * GoalVertex-style analyst card for tip pages.
 */
function bao_analyst_card_html(): string {
    $a = bao_lead_analyst();
    return '<aside class="bao-analyst-card" aria-label="Lead analyst">'
        . '<div class="bao-analyst-avatar" aria-hidden="true">' . bao_h($a['initials']) . '</div>'
        . '<div class="bao-analyst-body">'
        . '<p class="bao-analyst-name"><a href="' . bao_h($a['url']) . '">' . bao_h($a['name']) . '</a></p>'
        . '<p class="bao-analyst-role">' . bao_h($a['role_line']) . '</p>'
        . '<p class="bao-analyst-bio">' . bao_h($a['bio']) . '</p>'
        . '</div>'
        . '</aside>';
}

function bao_last_updated_html(?string $iso = null): string {
    $iso = $iso ?: date('c');
    $label = date('j M Y, H:i', strtotime($iso)) . ' EAT';
    return '<p class="last-updated">Last updated <time datetime="' . bao_h($iso) . '">' . bao_h($label) . '</time>'
        . ' · <a href="https://www.baopredictions.com/">Bao Predictions</a></p>';
}

/**
 * Board freshness from page API payload last_updated, else stats, else now.
 *
 * @param array<string,mixed>|null $payload
 */
function bao_board_freshness_html(?array $payload = null): string {
    $iso = null;
    if (is_array($payload) && !empty($payload['last_updated'])) {
        $iso = (string) $payload['last_updated'];
    } else {
        require_once __DIR__ . '/api-curl.php';
        $stats = bao_api_stats();
        if (is_array($stats) && !empty($stats['last_updated'])) {
            $iso = (string) $stats['last_updated'];
        }
    }
    return bao_last_updated_html($iso);
}

/**
 * Compact bridge under tip boards — Today / Yesterday / Results.
 */
function bao_results_bridge_html(): string {
    return '<nav class="bao-results-bridge" aria-label="Prediction timeline">'
        . '<a href="/football-predictions-today">Today</a>'
        . '<a href="/football-predictions-yesterday">Yesterday</a>'
        . '<a href="/results">Results</a>'
        . '<a href="/how-we-predict">How we predict</a>'
        . '</nav>';
}

/**
 * Brand keyword sections for jackpot intents (links only — no empty shells).
 * Preserves existing Prediction / Prediction Today copy elsewhere on the page.
 */
function bao_brand_jackpot_sections_html(string $brand): string {
    $b = bao_h($brand);
    return '<h2 id="' . bao_h(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $brand) ?: 'brand') . '-jackpot') . '">' . $b . ' Jackpot Prediction</h2>'
        . '<p>A <strong>' . $b . ' jackpot prediction</strong> should be checked fixture-by-fixture against the live operator coupon. Bao Predictions publishes free Kenya jackpot sheets with a 1X2 lean and short reason on every game.</p>'
        . '<p class="seo-related"><a href="/jackpot-predictions">All jackpot predictions</a></p>'
        . '<h2>' . $b . ' Mega Jackpot Prediction</h2>'
        . '<p>For <strong>' . $b . ' Mega Jackpot prediction</strong> searches, use the current SportPesa Mega card rather than an old indexed round. Each of the 17 fixtures is assessed separately.</p>'
        . '<p class="seo-related"><a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a> · <a href="/mega-jackpot-strategy-guide">Mega Jackpot Strategy Guide</a></p>'
        . '<h2>' . $b . ' SportPesa Mega Jackpot Prediction</h2>'
        . '<p>Looking for a <strong>' . $b . ' SportPesa Mega Jackpot prediction</strong>? Open the live Mega sheet for this weekend’s fixtures, stakes and per-match notes — then confirm the coupon on SportPesa before you play.</p>'
        . '<p class="seo-related"><a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek Jackpot Predictions</a></p>';
}

/**
 * Brand tip-board SEO body (SokaFans-quality pattern).
 * Tip cards stay above this stack — copy explains the board; it does not re-list every pick.
 *
 * @param list<array<string,mixed>> $games
 * @param array{
 *   angle?:string,
 *   related?:string,
 *   shortlist_label?:string,
 *   overview?:string
 * } $opts angle/overview may contain trusted HTML (<strong>, <a>)
 */
function bao_brand_seo_stack_html(string $brand, array $games, array $opts = []): string {
    $b = bao_h($brand);
    $todayLabel = bao_h(date('j F Y'));
    $shortlistLabel = (string) ($opts['shortlist_label'] ?? ($brand . ' shortlist'));
    $angle = trim((string) ($opts['angle'] ?? ''));
    $overview = trim((string) ($opts['overview'] ?? ''));
    $related = trim((string) ($opts['related'] ?? ''));
    if ($related === '') {
        $related = '<a href="/football-predictions-today">Football Predictions Today</a>'
            . ' · <a href="/betnumbers-tips">Bet Numbers Tips</a>'
            . ' · <a href="/sokafans-predictions">SokaFans Predictions</a>'
            . ' · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>'
            . ' · <a href="/results">Results</a>'
            . ' · <a href="/how-we-predict">How We Predict</a>';
    }

    $html = '<h2>' . $b . ' Predictions</h2>';
    if ($overview !== '') {
        $html .= '<p>' . $overview . '</p>';
    } else {
        $html .= '<p><strong>' . $b . ' predictions</strong> are daily football tip boards that bettors often search alongside weekend fixtures and Mega Jackpot coupons. On Bao Predictions, this page is a free ' . $b . '-style board: one recommended market per fixture, with the lean and reasoning visible without a VIP paywall.</p>';
    }
    $html .= '<p>The tip cards above are the live selections for today. The sections below explain how to read a tip, when to switch to a jackpot sheet, and how this free board differs from paid tip packages.</p>';

    if ($angle !== '') {
        $html .= '<p>' . $angle . '</p>';
    }

    $html .= '<h2>' . $b . ' Prediction</h2>'
        . '<p>A <strong>' . $b . ' prediction</strong> on this board is a single-fixture selection — not an accumulator ticket and not a full jackpot coupon. Depending on the evidence, the published market may be:</p>'
        . '<ul>'
        . '<li><strong>1X2</strong> — home win, draw or away win</li>'
        . '<li><strong>Double Chance</strong> — 1X, 12 or X2 when a single result looks thin</li>'
        . '<li><strong>BTTS</strong> — both teams to score</li>'
        . '<li><strong>Over/Under</strong> — usually the 2.5 goals line</li>'
        . '<li><strong>HT/FT</strong> — Half Time/Full Time combinations when tempo supports them</li>'
        . '</ul>'
        . '<p>Judge the pick against the fixture and the market shown on the card. A strong league position does not automatically justify every market — especially goals or Double Chance leans.</p>';

    $html .= '<h2>' . $b . ' Prediction Today</h2>'
        . '<p>For <strong>' . $b . ' prediction today</strong> on <strong>' . $todayLabel . '</strong>, use the live board above. Tips can move when team news or kickoff changes land, so check the last-updated time before you stake.</p>'
        . bao_shortlist_summary_html($games, $shortlistLabel)
        . '<p>If you are planning Saturday–Sunday fixtures rather than today’s midweek slate, move to <a href="/weekend-football-predictions">Weekend Football Predictions</a>. Settled outcomes belong on <a href="/football-predictions-yesterday">Yesterday</a> and <a href="/results">Results</a> — not on this pre-match board.</p>';

    $html .= '<h2>How to read a tip on this board</h2>'
        . '<p>Before you copy a selection onto a slip, check:</p>'
        . '<ul>'
        . '<li>Kickoff time and whether the fixture is still scheduled</li>'
        . '<li>The market on the card (1X2 is not the same as Double Chance or Over/Under)</li>'
        . '<li>The short reason — it should match the market being tipped</li>'
        . '<li>Confidence as a model lean only (capped for publish; never a “sure win”)</li>'
        . '<li>Whether you need a jackpot sheet instead of a daily singles board</li>'
        . '</ul>'
        . '<p>Bao’s publish rules and confidence caps are documented on <a href="/how-we-predict">How we predict</a>.</p>';

    $html .= bao_brand_jackpot_sections_html($brand);

    $html .= '<h2>Free ' . $b . '-style tips vs VIP walls</h2>'
        . '<p>Many ' . $b . '-style searches expect a free daily list, then hit a paywall for “VIP” or long jackpot packages. This Bao page keeps the daily mixed-market board free: every card above shows the pick and reason without registration.</p>'
        . '<p>What you still need to verify yourself: the bookmaker’s live odds, confirmed lineups, and — for coupons — the current SportPesa Mega Jackpot or Betika round on the operator app. A free tip board does not replace the live coupon.</p>';

    $html .= '<p><strong>18+ only. Gamble responsibly.</strong> Football predictions are opinions based on available match data, not guaranteed outcomes. Stake only what you can afford to lose. <a href="/responsible-betting">Responsible Betting</a>.</p>'
        . '<p class="seo-related"><strong>Related:</strong> ' . $related . '</p>';

    return $html;
}

function bao_rg_notice_html(): string {
    return '<p class="rg-notice"><span class="age-badge">18+</span> Tips are informational opinions for entertainment — not financial advice, not betting tips that guarantee profit, and not a substitute for your own judgment. Confidence scores are model leans only; they are not predicted win rates. Never stake money you cannot afford to lose. <a href="/responsible-betting">Responsible betting</a>. Must be 18+ (or legal age where you live).</p>';
}

/**
 * Closing intro line with internal links (Betwinner360-style page heroes).
 * Pass optional custom HTML for the sentence body if a page needs different anchors.
 */
function bao_intro_links_html(?string $html = null): string {
    if ($html !== null && $html !== '') {
        return '<p class="intro-links">' . $html . '</p>';
    }
    return '<p class="intro-links">With our free tips, <a href="/football-predictions-today">Football Predictions Today</a>, or <a href="/jackpot-predictions">Jackpot Predictions</a> you can compare more boards before you stake with your favourite bookmakers.</p>';
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
    $label = (string) ($sheet['label'] ?? 'Jackpot');
    $count = (int) ($sheet['count'] ?? 0);
    $schedule = (string) ($sheet['schedule'] ?? 'open');
    $prize = (string) ($sheet['prize_label'] ?? 'varies');
    $countBit = $count > 0
        ? $count . ' fixtures'
        : 'this round\'s fixtures';
    return '<p class="lede">Free <strong>' . bao_h($label) . '</strong> tip sheet for '
        . bao_h($schedule) . ' play — ' . bao_h($countBit)
        . ' with a 1X2 lean and short reasoning on every game. Prize pool '
        . bao_h($prize) . '. Confirm the live card on the operator before you play.</p>';
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
        'tip_of_day' => false,
        'load_more' => false,
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

/** Render FAQ answer text (supports \n\n paragraph breaks). */
function bao_faq_answer_html(string $a): string {
    $parts = preg_split('/\n\n+/', trim($a)) ?: [];
    $html = '';
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') {
            continue;
        }
        $html .= '<p>' . bao_h($part) . '</p>';
    }
    return $html !== '' ? $html : '<p>' . bao_h($a) . '</p>';
}

/** Render a full FAQ list from ['q' => …, 'a' => …] items. */
function bao_faq_items_html(array $faqs): string {
    if (!$faqs) {
        return '';
    }
    $html = '<ul class="faq-list">';
    foreach ($faqs as $item) {
        $html .= '<li><details><summary>' . bao_h((string) ($item['q'] ?? '')) . '</summary>'
            . bao_faq_answer_html((string) ($item['a'] ?? ''))
            . '</details></li>';
    }
    $html .= '</ul>';
    return $html;
}

function bao_faq_schema(array $faqs): string {
    if (!$faqs) {
        return '';
    }
    $entities = [];
    foreach ($faqs as $item) {
        $answer = preg_replace('/\n\n+/', ' ', trim((string) ($item['a'] ?? ''))) ?? (string) ($item['a'] ?? '');
        $entities[] = [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $answer,
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
            'Kenya jackpot tips',
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
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $headline,
        'description' => $description,
        'url' => 'https://www.baopredictions.com' . $url,
        'isPartOf' => [
            '@type' => 'WebSite',
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
