<?php
/**
 * Prediction of the Day — highest-confidence tip from today's board for the sidebar.
 * Prefers popular_status=1 leagues, then model lean.
 */

require_once __DIR__ . '/api-curl.php';
require_once __DIR__ . '/match-cards.php';

/**
 * @return array{game: array<string,mixed>, source: string, href: string}|null
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

    $pool = [];
    foreach ($sources as $src) {
        $payload = bao_curl_api($src['path']);
        $games = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
            ? $payload['games']
            : [];
        foreach ($games as $g) {
            if (!is_array($g)) {
                continue;
            }
            $pool[] = [
                'game' => $g,
                'source' => $src['label'],
                'href' => $src['href'],
            ];
        }
    }

    $best = bao_tip_of_day_best_from_pool($pool);
    $result = $best;
    return $result;
}

/**
 * Prefer popular leagues first, then strongest published confidence.
 *
 * @param list<array{game: array<string,mixed>, source: string, href: string}> $pool
 * @return array{game: array<string,mixed>, source: string, href: string}|null
 */
function bao_tip_of_day_best_from_pool(array $pool): ?array
{
    $candidates = [];
    foreach ($pool as $row) {
        $g = $row['game'];
        // Skip fully settled tips — tip of the day should be actionable.
        if (array_key_exists('won', $g) && $g['won'] !== null) {
            continue;
        }
        $status = strtoupper(trim((string) ($g['status'] ?? '')));
        if (in_array($status, ['FT', 'AET', 'PEN', 'CANC', 'PST', 'ABD', 'AWD', 'WO'], true)) {
            continue;
        }
        $candidates[] = $row;
    }
    if ($candidates === []) {
        $candidates = $pool;
    }
    if ($candidates === []) {
        return null;
    }

    usort($candidates, static function (array $a, array $b): int {
        $ga = $a['game'];
        $gb = $b['game'];
        // Popular leagues first (popular_status = 1).
        $pa = (int) ($ga['popular'] ?? $ga['popular_status'] ?? 0) > 0 ? 1 : 0;
        $pb = (int) ($gb['popular'] ?? $gb['popular_status'] ?? 0) > 0 ? 1 : 0;
        if ($pa !== $pb) {
            return $pb <=> $pa;
        }
        $ca = bao_display_confidence(isset($ga['confidence']) ? (int) $ga['confidence'] : 0);
        $cb = bao_display_confidence(isset($gb['confidence']) ? (int) $gb['confidence'] : 0);
        if ($ca !== $cb) {
            return $cb <=> $ca;
        }
        $ka = (string) ($ga['kickoff_iso'] ?? $ga['kickoff'] ?? '');
        $kb = (string) ($gb['kickoff_iso'] ?? $gb['kickoff'] ?? '');
        return $ka <=> $kb;
    });

    return $candidates[0];
}

/**
 * @param list<array<string,mixed>> $games
 * @return array<string,mixed>|null
 */
function bao_tip_of_day_best_game(array $games): ?array
{
    $pool = [];
    foreach ($games as $g) {
        if (!is_array($g)) {
            continue;
        }
        $pool[] = ['game' => $g, 'source' => 'board', 'href' => '/football-predictions-today'];
    }
    $best = bao_tip_of_day_best_from_pool($pool);
    return $best['game'] ?? null;
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
            $dateLabel = (new DateTimeImmutable((string) $g['date']))->format('D j M');
        } catch (Throwable $e) {
            $dateLabel = (string) $g['date'];
        }
    }
    // Prefer a compact one-line kickoff: "5:00 PM · Sat 12 Sep"
    $kickLine = '';
    if ($clock !== '' && $dateLabel !== '') {
        $kickLine = $clock . ' · ' . $dateLabel;
    } elseif ($clock !== '') {
        $kickLine = $clock;
    } elseif ($dateLabel !== '') {
        $kickLine = $dateLabel;
    }

    $kickoffIso = trim((string) ($g['kickoff_iso'] ?? ''));
    $isLive = !empty($g['is_live']);
    $status = strtoupper(trim((string) ($g['status'] ?? '')));
    $statusLong = trim((string) ($g['status_long'] ?? ''));
    $score = trim((string) ($g['score'] ?? ''));
    $sponsor = bao_tip_of_day_sponsor();

    $html = '<section class="tip-day' . ($isLive ? ' is-live' : '') . '" aria-label="Prediction of the Day">';
    $html .= '<header class="tip-day-header">';
    $html .= '<span class="tip-day-ball" aria-hidden="true"></span>';
    $html .= '<h2 class="tip-day-title"><a href="/banker-of-the-day">Prediction of the Day</a></h2>';
    if ($isLive) {
        $html .= '<span class="tip-day-live at-live-pill" title="'
            . bao_h($statusLong !== '' ? $statusLong : $status)
            . '">LIVE'
            . ($status !== '' ? ' · ' . bao_h($status) : '')
            . '</span>';
    }
    $html .= '</header>';

    $html .= '<a class="tip-day-body-link" href="/banker-of-the-day">';
    $html .= '<div class="tip-day-match">';
    $html .= '<div class="tip-day-meta">';
    $html .= '<span class="tip-day-league">' . bao_h($leagueLine) . '</span>';
    if ($kickLine !== '') {
        $html .= '<span class="tip-day-kick bao-kickoff-time"'
            . ($kickoffIso !== '' ? ' data-kickoff-utc="' . bao_h($kickoffIso) . '"' : '')
            . ' data-show-date="1"'
            . '><span class="bao-kickoff-label">' . bao_h($kickLine) . '</span></span>';
    }
    $html .= '</div>';

    $html .= '<div class="tip-day-teams">';
    $html .= '<div class="tip-day-team">';
    if ($homeLogo !== '') {
        $html .= '<img class="tip-day-crest" src="' . bao_h(bao_img_url($homeLogo, 80)) . '" alt="" width="40" height="40" loading="lazy" decoding="async">';
    } else {
        $html .= '<span class="tip-day-crest tip-day-crest--empty" aria-hidden="true"></span>';
    }
    $html .= '<span class="tip-day-team-name">' . bao_h($home) . '</span>';
    $html .= '</div>';

    $html .= '<div class="tip-day-vs">';
    if ($isLive && $score !== '' && $score !== '—') {
        $html .= '<span class="tip-day-score">' . bao_h($score) . '</span>';
    } else {
        $html .= '<span>V.S</span>';
    }
    $html .= '</div>';

    $html .= '<div class="tip-day-team">';
    if ($awayLogo !== '') {
        $html .= '<img class="tip-day-crest" src="' . bao_h(bao_img_url($awayLogo, 80)) . '" alt="" width="40" height="40" loading="lazy" decoding="async">';
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
    $html .= '<p class="tip-day-more">Open Banker of the Day →</p>';
    $html .= '</a>';

    if ($sponsor !== null) {
        $html .= '<div class="tip-day-sponsor">';
        $html .= '<p class="tip-day-sponsor-label">Betting odds sponsored by</p>';
        $html .= '<a class="tip-day-sponsor-card" href="' . bao_h($sponsor['url']) . '" rel="sponsored noopener noreferrer" target="_blank">';
        $html .= '<span class="tip-day-sponsor-name">' . bao_h($sponsor['label']) . '</span>';
        $html .= '<span class="tip-day-sponsor-badge">Partner offer</span>';
        $html .= '</a>';
        $html .= '</div>';
    }

    $html .= '</section>';
    return $html;
}
