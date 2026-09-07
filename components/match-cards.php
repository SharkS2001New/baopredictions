<?php
/**
 * Match cards (default fixture format) + optional tips table.
 * Games are rendered inline where the page calls bao_matches_html() —
 * not injected later via JS.
 */

if (!function_exists('bao_h')) {
    function bao_h(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

function bao_team_initials(string $name): string {
    $words = preg_split('/\s+/', trim($name)) ?: [];
    $words = array_values(array_filter($words, static function ($w) {
        return !in_array(strtoupper($w), ['FC', 'CF', 'AFC', 'SC', 'AC'], true);
    }));
    if (!$words) {
        return '?';
    }
    if (count($words) === 1) {
        return strtoupper(substr($words[0], 0, 2));
    }
    return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
}

function bao_guess_confidence(string $pick, ?string $score = null): int {
    $base = 72 + (strlen($pick) % 14);
    if ($score && $score !== '—' && $score !== '-') {
        $base = min(95, $base + 6);
    }
    return min(95, $base);
}

/**
 * Kick-off label: time only for single-day boards; date + time when games span days (jackpots).
 *
 * @param array<string,mixed> $g
 */
function bao_kickoff_label(array $g, bool $withDate = false): string
{
    $clock = trim((string) ($g['time_clock'] ?? ''));
    if ($clock === '') {
        $raw = trim((string) ($g['time'] ?? ''));
        // If time already looks like "Sat · 7:30 PM" or "Sat 5 Sep · 7:30 PM", take the clock part.
        if (str_contains($raw, '·')) {
            $parts = explode('·', $raw);
            $clock = trim((string) end($parts));
        } else {
            $clock = $raw;
        }
    }
    if ($clock === '') {
        return '—';
    }
    if (!$withDate) {
        $time = trim((string) ($g['time'] ?? ''));
        return $time !== '' ? $time : $clock;
    }

    $dateLabel = trim((string) ($g['date_label'] ?? ''));
    if ($dateLabel === '' && !empty($g['date'])) {
        try {
            $dateLabel = (new DateTimeImmutable((string) $g['date']))->format('D j M');
        } catch (Throwable $e) {
            $dateLabel = (string) $g['date'];
        }
    }
    if ($dateLabel === '') {
        return $clock;
    }
    return $dateLabel . ' · ' . $clock;
}

/**
 * True when the fixture list covers more than one calendar day.
 *
 * @param list<array<string,mixed>> $games
 */
function bao_games_span_days(array $games): bool
{
    $dates = [];
    foreach ($games as $g) {
        $d = trim((string) ($g['date'] ?? ''));
        if ($d !== '') {
            $dates[$d] = true;
        }
    }
    return count($dates) > 1;
}

/**
 * @param array<string,mixed> $g
 */
function bao_match_card(array $g): string {
    $home = $g['home'] ?? 'Home';
    $away = $g['away'] ?? 'Away';
    $league = $g['league'] ?? '';
    $time = $g['time'] ?? '';
    $kickoffIso = trim((string) ($g['kickoff_iso'] ?? ''));
    $showDate = !empty($g['_show_date']);
    $kickLabel = bao_kickoff_label($g, $showDate);
    $pick = $g['pick'] ?? '—';
    $odds = isset($g['odds']) ? (string) $g['odds'] : '';
    $confidence = isset($g['confidence']) ? (int) $g['confidence'] : bao_guess_confidence($pick, $g['score'] ?? null);
    $reason = trim($g['reason'] ?? '');
    $score = $g['score'] ?? null;
    $won = $g['won'] ?? null;
    $winning = $g['winning'] ?? null;
    $isLive = !empty($g['is_live']);
    $status = strtoupper(trim((string) ($g['status'] ?? '')));
    $statusLong = trim((string) ($g['status_long'] ?? ''));
    $homeLogo = $g['home_logo'] ?? '';
    $awayLogo = $g['away_logo'] ?? '';
    $showTick = ($won === true) || ($winning === true);
    $showLost = ($won === false);
    $tickTitle = ($won === true) ? 'Won' : 'Tip currently winning';
    $marketLabel = trim((string) ($g['market_label'] ?? ''));
    if ($marketLabel === '' && !empty($g['market'])) {
        $marketLabel = match ((string) $g['market']) {
            'double_chance' => 'DC',
            'over_under' => 'O/U',
            'btts' => 'BTTS',
            'ht_ft' => 'HT/FT',
            default => '1X2',
        };
    }

    $aria = bao_h($home . ' vs ' . $away);
    $cardClass = 'at-match-card'
        . ($isLive ? ' is-live' : '')
        . ($showTick ? ' is-tip-hit' : '')
        . ($showLost ? ' is-tip-lost' : '');
    $html = '<article class="' . $cardClass . '" aria-label="' . $aria . '">';

    $html .= '<div class="at-card-league">';
    $html .= '<span class="at-league-dot" aria-hidden="true"></span>';
    $html .= '<span class="at-league-name">' . bao_h($league !== '' ? $league : 'Football') . '</span>';
    if ($isLive) {
        $html .= '<span class="at-live-pill" title="' . bao_h($statusLong !== '' ? $statusLong : $status) . '">LIVE'
            . ($status !== '' ? ' · ' . bao_h($status) : '')
            . '</span>';
    }
    $html .= '</div>';

    $html .= '<div class="at-card-teams">';
    $html .= '<div class="at-team">';
    if ($homeLogo !== '') {
        $html .= '<img class="at-crest" src="' . bao_h($homeLogo) . '" alt="" width="28" height="28" loading="lazy" decoding="async">';
    } else {
        $html .= '<span class="at-crest at-crest-fallback" aria-hidden="true">' . bao_h(bao_team_initials($home)) . '</span>';
    }
    $html .= '<span class="at-team-name">' . bao_h($home) . '</span></div>';
    $html .= '<div class="at-team">';
    if ($awayLogo !== '') {
        $html .= '<img class="at-crest" src="' . bao_h($awayLogo) . '" alt="" width="28" height="28" loading="lazy" decoding="async">';
    } else {
        $html .= '<span class="at-crest at-crest-fallback" aria-hidden="true">' . bao_h(bao_team_initials($away)) . '</span>';
    }
    $html .= '<span class="at-team-name">' . bao_h($away) . '</span></div>';
    $html .= '</div>';

    $html .= '<div class="at-card-meta">';
    if ($score !== null && $score !== '' && $score !== '—') {
        $html .= '<span class="at-score-row">';
        $html .= '<span class="at-score">' . bao_h((string) $score) . '</span>';
        if ($showTick) {
            $html .= '<span class="at-outcome-tick" title="' . bao_h($tickTitle) . '" aria-label="' . bao_h($tickTitle) . '">✅</span>';
        } elseif ($showLost) {
            $html .= '<span class="at-outcome-lost" title="Lost" aria-label="Lost">❌</span>';
        }
        $html .= '</span>';
    } elseif ($kickLabel !== '' && $kickLabel !== '—') {
        $html .= '<span class="at-time bao-kickoff-time"'
            . ($kickoffIso !== '' ? ' data-kickoff-utc="' . bao_h($kickoffIso) . '"' : '')
            . ($showDate ? ' data-show-date="1"' : '')
            . ' title="Kick-off time (shown in your timezone)">';
        $html .= '<span class="at-clock" aria-hidden="true"></span>';
        $html .= '<span class="bao-kickoff-label">' . bao_h($kickLabel) . '</span>';
        $html .= '</span>';
    }
    $html .= '</div>';

    $html .= '<div class="at-card-tip">';
    $html .= '<div class="at-tip-left">';
    if ($marketLabel !== '') {
        $html .= '<span class="at-market-pill">' . bao_h($marketLabel) . '</span> ';
    }
    $html .= '<span class="at-tip-label">Tip:</span> ';
    $html .= '<span class="at-tip-pick">' . bao_h($pick) . '</span>';
    if ($odds !== '' && $odds !== '—') {
        $html .= '<span class="at-odds-pill">@ ' . bao_h($odds) . '</span>';
    }
    $html .= '</div>';
    $html .= '<span class="at-conf-pill" title="Internal model lean only — not a predicted win rate or guarantee">'
        . $confidence . '% model</span>';
    $html .= '</div>';

    if ($reason !== '') {
        $html .= '<p class="at-card-reason">' . bao_h($reason) . '</p>';
    }

    $html .= '</article>';
    return $html;
}

/**
 * Fixture list for this page's games (server-rendered in place — not late-injected).
 * Shows at most 20 cards initially; optional Show More refetches the next window from /api/{page}.
 *
 * @param list<array<string,mixed>> $games
 * @param array{title?: string, class?: string, layout?: string, show_date?: bool, page?: string, page_size?: int, chunk_size?: int, load_more?: bool} $opts
 *        layout: 'cards' (default) | 'table'
 *        show_date: force date+time (jackpots); auto when games span multiple days
 *        page: api-pages key (e.g. football-predictions-today) — enables Show More
 */
function bao_matches_html(array $games, array $opts = []): string {
    $title = $opts['title'] ?? '';
    $class = $opts['class'] ?? '';
    $layout = $opts['layout'] ?? 'cards';
    $page = trim((string) ($opts['page'] ?? ''));
    $pageSize = max(1, (int) ($opts['page_size'] ?? 20));
    $chunkSize = max(1, (int) ($opts['chunk_size'] ?? 20));
    $loadMore = ($opts['load_more'] ?? true) !== false;
    $showDate = array_key_exists('show_date', $opts)
        ? (bool) $opts['show_date']
        : bao_games_span_days($games);

    $total = count($games);
    $visible = ($loadMore && $page !== '' && $layout === 'cards' && $total > $pageSize)
        ? array_slice($games, 0, $pageSize)
        : $games;
    $hasMore = $loadMore && $page !== '' && $layout === 'cards' && $total > count($visible);

    if ($layout === 'table') {
        return bao_matches_table_html($games, $title, $class, $showDate);
    }

    $html = '<div class="matches-block' . ($class ? ' ' . bao_h($class) : '') . '">';
    if ($title !== '') {
        $html .= '<h2 class="at-matches-title">' . bao_h($title) . '</h2>';
    }
    $html .= '<div class="matches-container at-matches-grid" data-bao-matches>';
    foreach ($visible as $g) {
        $html .= bao_match_card($g + ['_show_date' => $showDate]);
    }
    $html .= '</div>';
    if ($hasMore) {
        $html .= bao_load_more_html($page, $pageSize, $chunkSize, $showDate, $total);
    }
    $html .= '</div>';
    return $html;
}

/**
 * Show More Matches control (pitch-style).
 */
function bao_load_more_html(string $page, int $nextStart, int $chunkSize, bool $showDate, int $knownTotal = 0): string
{
    $api = '/api/' . ltrim($page, '/');
    $html = '<div class="bao-load-more-wrap">';
    $html .= '<button type="button" class="bao-load-more"'
        . ' data-api="' . bao_h($api) . '"'
        . ' data-start="' . (int) $nextStart . '"'
        . ' data-chunk="' . (int) $chunkSize . '"'
        . ($showDate ? ' data-show-date="1"' : '')
        . ($knownTotal > 0 ? ' data-known-total="' . (int) $knownTotal . '"' : '')
        . '>';
    $html .= '<span class="bao-load-more-label">Show More Matches</span>';
    $html .= '<span class="bao-load-more-busy" hidden>Loading…</span>';
    $html .= '</button></div>';
    return $html;
}

/**
 * Optional tips-table layout (not the default fixture format).
 *
 * @param list<array<string,mixed>> $games
 */
function bao_matches_table_html(array $games, string $title, string $class, bool $showDate): string
{
    $html = '<div class="matches-block tips-table-block' . ($class ? ' ' . bao_h($class) : '') . '">';
    if ($title !== '') {
        $html .= '<h2 class="at-matches-title">' . bao_h($title) . '</h2>';
    }
    $html .= '<div class="tips-table-wrap">';
    $html .= '<table class="tips-table">';
    $html .= '<thead><tr>';
    $html .= '<th scope="col">' . ($showDate ? 'Kick-off' : 'Time') . '</th>';
    $html .= '<th scope="col">League</th>';
    $html .= '<th scope="col">Match</th>';
    $html .= '<th scope="col">Tip</th>';
    $html .= '<th scope="col">Odds</th>';
    $html .= '<th scope="col">Model %</th>';
    $html .= '<th scope="col">Notes</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($games as $g) {
        $home = (string) ($g['home'] ?? 'Home');
        $away = (string) ($g['away'] ?? 'Away');
        $league = (string) ($g['league'] ?? 'Football');
        $kickoffIso = trim((string) ($g['kickoff_iso'] ?? ''));
        $pick = (string) ($g['pick'] ?? '—');
        $odds = isset($g['odds']) && $g['odds'] !== '' && $g['odds'] !== null ? (string) $g['odds'] : '—';
        $confidence = isset($g['confidence']) ? (int) $g['confidence'] : bao_guess_confidence($pick, $g['score'] ?? null);
        $reason = trim((string) ($g['reason'] ?? ''));
        $score = $g['score'] ?? null;
        $won = $g['won'] ?? null;
        $isLive = !empty($g['is_live']);
        $marketLabel = trim((string) ($g['market_label'] ?? ''));
        $kickLabel = bao_kickoff_label($g, $showDate);

        $rowClass = 'tips-row';
        if ($isLive) {
            $rowClass .= ' is-live';
        }
        if ($won === true) {
            $rowClass .= ' is-won';
        } elseif ($won === false) {
            $rowClass .= ' is-lost';
        }

        $html .= '<tr class="' . $rowClass . '">';

        $html .= '<td class="tips-col-time">';
        if ($score !== null && $score !== '' && $score !== '—') {
            $html .= '<span class="tips-score">' . bao_h((string) $score) . '</span>';
        } else {
            $html .= '<span class="bao-kickoff-time"'
                . ($kickoffIso !== '' ? ' data-kickoff-utc="' . bao_h($kickoffIso) . '"' : '')
                . ($showDate ? ' data-show-date="1"' : '')
                . '><span class="bao-kickoff-label">' . bao_h($kickLabel) . '</span></span>';
        }
        if ($isLive) {
            $html .= ' <span class="tips-live">LIVE</span>';
        }
        $html .= '</td>';

        $html .= '<td class="tips-col-league">' . bao_h($league) . '</td>';
        $html .= '<td class="tips-col-match"><span class="tips-home">' . bao_h($home) . '</span>'
            . ' <span class="tips-vs">vs</span> '
            . '<span class="tips-away">' . bao_h($away) . '</span></td>';

        $html .= '<td class="tips-col-tip">';
        if ($marketLabel !== '') {
            $html .= '<span class="tips-market">' . bao_h($marketLabel) . '</span> ';
        }
        $html .= '<strong class="tips-pick">' . bao_h($pick) . '</strong>';
        if ($won === true) {
            $html .= ' <span class="tips-outcome won" title="Won">✅</span>';
        } elseif ($won === false) {
            $html .= ' <span class="tips-outcome lost" title="Lost">❌</span>';
        }
        $html .= '</td>';

        $html .= '<td class="tips-col-odds">' . bao_h($odds) . '</td>';
        $html .= '<td class="tips-col-conf" title="Model lean — not a win guarantee">' . $confidence . '%</td>';
        $html .= '<td class="tips-col-notes">' . ($reason !== '' ? bao_h($reason) : '—') . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div>';
    return $html;
}

/**
 * Pre-built accumulator tickets (3/5/8-fold).
 *
 * @param list<array<string,mixed>> $tickets
 * @param array{title?: string} $opts
 */
function bao_accumulators_html(array $tickets, array $opts = []): string
{
    if ($tickets === []) {
        return '';
    }
    $title = $opts['title'] ?? '';
    $html = '<div class="matches-block">';
    if ($title !== '') {
        $html .= '<h2 class="at-matches-title">' . bao_h($title) . '</h2>';
    }
    $html .= '<ul class="acca-list">';
    foreach ($tickets as $t) {
        $html .= '<li><article class="acca-ticket">';
        $html .= '<header class="acca-header"><div>';
        $html .= '<h3 class="mt-0 mb-0">' . bao_h((string) ($t['name'] ?? 'Accumulator')) . '</h3>';
        $html .= '<p class="text-muted mb-0">' . (int) ($t['legs'] ?? 0) . '-fold · '
            . (int) ($t['blended_confidence'] ?? 0) . '% blended model lean</p>';
        $html .= '</div><div class="acca-header-odds">';
        $html .= '<span class="acca-odds">' . bao_h((string) ($t['combined_odds'] ?? '—')) . '</span>';
        $html .= '<span class="acca-odds-label">Combined odds</span>';
        $html .= '</div></header>';

        $html .= '<div class="acca-legs" role="table" aria-label="Accumulator legs">';
        $html .= '<div class="acca-leg acca-leg-head" role="row">';
        $html .= '<span role="columnheader">Game</span>';
        $html .= '<span role="columnheader">Tip</span>';
        $html .= '<span role="columnheader">Odds</span>';
        $html .= '<span role="columnheader">Model %</span>';
        $html .= '</div>';
        foreach (($t['picks'] ?? []) as $p) {
            $home = trim((string) ($p['home'] ?? ''));
            $away = trim((string) ($p['away'] ?? ''));
            $game = $home !== '' || $away !== '' ? $home . ' vs ' . $away : '—';
            $pick = (string) ($p['pick'] ?? '—');
            $odds = (string) ($p['odds'] ?? '—');
            $win = (int) ($p['confidence'] ?? 0);
            $html .= '<div class="acca-leg" role="row">';
            $html .= '<span class="acca-leg-game" role="cell">' . bao_h($game) . '</span>';
            $html .= '<span class="acca-leg-tip" role="cell">' . bao_h($pick) . '</span>';
            $html .= '<span class="acca-leg-odds" role="cell">' . bao_h($odds) . '</span>';
            $html .= '<span class="acca-leg-win" role="cell" title="Model lean — not a win guarantee">' . $win . '%</span>';
            $html .= '</div>';
        }
        $html .= '</div></article></li>';
    }
    $html .= '</ul></div>';
    return $html;
}
