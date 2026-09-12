<?php
/**
 * Prediction of the Day — highest-confidence tip from today's board for the sidebar.
 */

require_once __DIR__ . '/api-curl.php';
require_once __DIR__ . '/match-cards.php';

/**
 * @return array{game: array<string,mixed>, source: string}|null
 */
function bao_tip_of_day_pick(): ?array
{
    static $cached = false;
    static $result = null;
    if ($cached) {
        return $result;
    }
    $cached = true;

    $sources = [
        ['path' => '/api/sure-bets-today', 'href' => '/sure-bets-today', 'label' => 'sure-bets'],
        ['path' => '/api/football-predictions-today', 'href' => '/football-predictions-today', 'label' => 'today'],
    ];

    foreach ($sources as $src) {
        $payload = bao_curl_api($src['path']);
        $games = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
            ? $payload['games']
            : [];
        $best = bao_tip_of_day_best_game($games);
        if ($best !== null) {
            $result = [
                'game' => $best,
                'source' => $src['label'],
                'href' => $src['href'],
            ];
            return $result;
        }
    }

    return null;
}

/**
 * Prefer unsettled, not-finished fixtures with the strongest published confidence.
 *
 * @param list<array<string,mixed>> $games
 * @return array<string,mixed>|null
 */
function bao_tip_of_day_best_game(array $games): ?array
{
    $candidates = [];
    foreach ($games as $g) {
        if (!is_array($g)) {
            continue;
        }
        // Skip fully settled tips — tip of the day should be actionable.
        if (array_key_exists('won', $g) && $g['won'] !== null) {
            continue;
        }
        $status = strtoupper(trim((string) ($g['status'] ?? '')));
        if (in_array($status, ['FT', 'AET', 'PEN', 'CANC', 'PST', 'ABD', 'AWD', 'WO'], true)) {
            continue;
        }
        $candidates[] = $g;
    }
    if ($candidates === []) {
        // Fall back to any published tip if everything settled.
        $candidates = array_values(array_filter($games, 'is_array'));
    }
    if ($candidates === []) {
        return null;
    }

    usort($candidates, static function (array $a, array $b): int {
        $ca = bao_display_confidence(isset($a['confidence']) ? (int) $a['confidence'] : 0);
        $cb = bao_display_confidence(isset($b['confidence']) ? (int) $b['confidence'] : 0);
        if ($ca !== $cb) {
            return $cb <=> $ca;
        }
        // Prefer popular leagues, then earlier kickoff.
        $pa = (int) ($a['popular'] ?? 0);
        $pb = (int) ($b['popular'] ?? 0);
        if ($pa !== $pb) {
            return $pb <=> $pa;
        }
        $ka = (string) ($a['kickoff_iso'] ?? $a['kickoff'] ?? '');
        $kb = (string) ($b['kickoff_iso'] ?? $b['kickoff'] ?? '');
        return $ka <=> $kb;
    });

    return $candidates[0];
}

/**
 * @return array{label: string, url: string}|null
 */
function bao_tip_of_day_sponsor(): ?array
{
    try {
        $root = dirname(__DIR__);
        $autoload = $root . '/vendor/autoload.php';
        if (is_file($autoload)) {
            require_once $autoload;
        }
        if (!class_exists(\App\Services\FooterSponsorsService::class, false)) {
            require_once $root . '/src/Services/FooterSponsorsService.php';
        }
        $links = (new \App\Services\FooterSponsorsService())->visibleLinks();
        if ($links === []) {
            return null;
        }
        $first = $links[0];
        $url = trim((string) ($first['url'] ?? ''));
        $label = trim((string) ($first['label'] ?? ''));
        if ($url === '') {
            return null;
        }
        if ($label === '' || preg_match('#^https?://#i', $label)) {
            $host = parse_url($url, PHP_URL_HOST);
            $label = is_string($host) && $host !== '' ? $host : 'Partner';
        }
        return ['label' => $label, 'url' => $url];
    } catch (Throwable $e) {
        return null;
    }
}

function bao_tip_of_day_html(): string
{
    $pick = bao_tip_of_day_pick();
    if ($pick === null) {
        return '';
    }

    $g = $pick['game'];
    $href = (string) $pick['href'];
    $home = trim((string) ($g['home'] ?? 'Home'));
    $away = trim((string) ($g['away'] ?? 'Away'));
    $league = trim((string) ($g['league'] ?? 'Football'));
    $country = trim((string) ($g['country'] ?? ''));
    $leagueLine = $country !== '' && stripos($league, $country) === false
        ? $league . ' · ' . $country
        : $league;
    $homeLogo = trim((string) ($g['home_logo'] ?? ''));
    $awayLogo = trim((string) ($g['away_logo'] ?? ''));
    $pickLabel = trim((string) ($g['pick'] ?? '—'));
    $marketLabel = trim((string) ($g['market_label'] ?? ''));
    if ($marketLabel === '' && !empty($g['market'])) {
        $marketLabel = match ((string) $g['market']) {
            'double_chance' => 'Double Chance',
            'over_under' => 'Over/Under',
            'btts' => 'BTTS',
            'ht_ft' => 'HT/FT',
            default => '1X2',
        };
    }
    $predictionText = $marketLabel !== ''
        ? $marketLabel . ': ' . $pickLabel
        : $pickLabel;

    $oddsRaw = $g['odds'] ?? null;
    $oddsNum = is_numeric($oddsRaw) ? (float) $oddsRaw : 0.0;
    $oddsDisplay = $oddsNum > 1 ? number_format($oddsNum, 2) : '—';
    $stake = 100;
    $payoutDisplay = $oddsNum > 1
        ? 'KES' . number_format((int) round($stake * $oddsNum), 0, '.', '')
        : '—';

    $clock = trim((string) ($g['time_clock'] ?? ''));
    if ($clock === '') {
        $clock = trim((string) ($g['time'] ?? ''));
        if (str_contains($clock, '·')) {
            $parts = explode('·', $clock);
            $clock = trim((string) end($parts));
        }
    }
    $dateLabel = trim((string) ($g['date_label'] ?? ''));
    if ($dateLabel === '' && !empty($g['date'])) {
        try {
            $dateLabel = (new DateTimeImmutable((string) $g['date']))->format('D - j M Y');
        } catch (Throwable $e) {
            $dateLabel = (string) $g['date'];
        }
    }
    $kickoffIso = trim((string) ($g['kickoff_iso'] ?? ''));
    $confidence = bao_display_confidence(isset($g['confidence']) ? (int) $g['confidence'] : 0);
    $isLive = !empty($g['is_live']);
    $sponsor = bao_tip_of_day_sponsor();

    $html = '<section class="tip-day" aria-label="Prediction of the Day">';
    $html .= '<header class="tip-day-header">';
    $html .= '<span class="tip-day-ball" aria-hidden="true"></span>';
    $html .= '<h2 class="tip-day-title">Prediction of the Day</h2>';
    $html .= '</header>';

    $html .= '<div class="tip-day-match">';
    $html .= '<div class="tip-day-meta">';
    $html .= '<span class="tip-day-league">' . bao_h($leagueLine) . '</span>';
    if ($clock !== '') {
        $html .= '<span class="tip-day-time bao-kickoff-time"'
            . ($kickoffIso !== '' ? ' data-kickoff-utc="' . bao_h($kickoffIso) . '"' : '')
            . '><span class="bao-kickoff-label">' . bao_h($clock) . '</span></span>';
    }
    if ($dateLabel !== '') {
        $html .= '<span class="tip-day-date">' . bao_h($dateLabel) . '</span>';
    }
    $html .= '</div>';

    $html .= '<div class="tip-day-teams">';
    $html .= '<div class="tip-day-team">';
    if ($homeLogo !== '') {
        $html .= '<img class="tip-day-crest" src="' . bao_h($homeLogo) . '" alt="" width="40" height="40" loading="lazy">';
    } else {
        $html .= '<span class="tip-day-crest tip-day-crest--empty" aria-hidden="true"></span>';
    }
    $html .= '<span class="tip-day-team-name">' . bao_h($home) . '</span>';
    $html .= '</div>';

    $html .= '<div class="tip-day-vs" aria-hidden="true"><span>V.S</span>';
    $html .= $isLive ? '<em>Live</em>' : '<em>Today</em>';
    $html .= '</div>';

    $html .= '<div class="tip-day-team">';
    if ($awayLogo !== '') {
        $html .= '<img class="tip-day-crest" src="' . bao_h($awayLogo) . '" alt="" width="40" height="40" loading="lazy">';
    } else {
        $html .= '<span class="tip-day-crest tip-day-crest--empty" aria-hidden="true"></span>';
    }
    $html .= '<span class="tip-day-team-name">' . bao_h($away) . '</span>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '<div class="tip-day-stats">';
    $html .= '<div class="tip-day-stat">';
    $html .= '<span class="tip-day-stat-label">Prediction</span>';
    $html .= '<span class="tip-day-stat-value tip-day-pick">' . bao_h($predictionText) . '</span>';
    $html .= '</div>';
    $html .= '<div class="tip-day-stat tip-day-stat--odds">';
    $html .= '<span class="tip-day-stat-label">Odds</span>';
    $html .= '<span class="tip-day-stat-value tip-day-odds">' . bao_h($oddsDisplay) . '</span>';
    $html .= '</div>';
    $html .= '<div class="tip-day-stat tip-day-stat--payout">';
    $html .= '<span class="tip-day-stat-label">Bet KES' . (int) $stake . '</span>';
    $html .= '<span class="tip-day-stat-value tip-day-payout">' . bao_h($payoutDisplay) . '</span>';
    $html .= '</div>';
    $html .= '</div>';

    if ($confidence > 0) {
        $html .= '<p class="tip-day-conf">' . (int) $confidence . '% model lean — not a guarantee</p>';
    }

    if ($sponsor !== null) {
        $html .= '<div class="tip-day-sponsor">';
        $html .= '<p class="tip-day-sponsor-label">Betting odds sponsored by</p>';
        $html .= '<a class="tip-day-sponsor-card" href="' . bao_h($sponsor['url']) . '" rel="sponsored noopener noreferrer" target="_blank">';
        $html .= '<span class="tip-day-sponsor-name">' . bao_h($sponsor['label']) . '</span>';
        $html .= '<span class="tip-day-sponsor-badge">Partner offer</span>';
        $html .= '</a>';
        $html .= '</div>';
    }

    $html .= '<div class="tip-day-actions">';
    $html .= '<a class="tip-day-btn tip-day-btn--ghost" href="' . bao_h($href) . '">See Prediction</a>';
    if ($sponsor !== null) {
        $html .= '<a class="tip-day-btn tip-day-btn--bet" href="' . bao_h($sponsor['url']) . '" rel="sponsored noopener noreferrer" target="_blank">Bet Now</a>';
    } else {
        $html .= '<a class="tip-day-btn tip-day-btn--bet" href="' . bao_h($href) . '">View Board</a>';
    }
    $html .= '</div>';

    $html .= '</section>';
    return $html;
}
