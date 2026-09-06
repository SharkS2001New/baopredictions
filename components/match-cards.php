<?php
/**
 * Accuratetip-style match cards (dark tip cards, 3-col grid).
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
 * @param array{
 *   time?: string,
 *   date_label?: string,
 *   league?: string,
 *   home: string,
 *   away: string,
 *   home_logo?: string,
 *   away_logo?: string,
 *   odds?: string|float,
 *   pick: string,
 *   confidence?: int,
 *   reason?: string,
 *   score?: string,
 *   won?: bool|null
 * } $g
 */
function bao_match_card(array $g): string {
    $home = $g['home'] ?? 'Home';
    $away = $g['away'] ?? 'Away';
    $league = $g['league'] ?? '';
    $time = $g['time'] ?? '';
    $kickoffIso = trim((string) ($g['kickoff_iso'] ?? ''));
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

    // League row
    $html .= '<div class="at-card-league">';
    $html .= '<span class="at-league-dot" aria-hidden="true"></span>';
    $html .= '<span class="at-league-name">' . bao_h($league !== '' ? $league : 'Football') . '</span>';
    if ($isLive) {
        $html .= '<span class="at-live-pill" title="' . bao_h($statusLong !== '' ? $statusLong : $status) . '">LIVE'
            . ($status !== '' ? ' · ' . bao_h($status) : '')
            . '</span>';
    }
    $html .= '</div>';

    // Teams row
    $html .= '<div class="at-card-teams">';
    $html .= '<div class="at-team at-team-home">';
    if ($homeLogo !== '') {
        $html .= '<img class="at-crest" src="' . bao_h($homeLogo) . '" alt="" width="28" height="28" loading="lazy">';
    } else {
        $html .= '<span class="at-crest at-crest-fallback">' . bao_h(bao_team_initials($home)) . '</span>';
    }
    $html .= '<span class="at-team-name">' . bao_h($home) . '</span>';
    $html .= '</div>';

    $html .= '<span class="at-vs">VS</span>';

    $html .= '<div class="at-team at-team-away">';
    $html .= '<span class="at-team-name">' . bao_h($away) . '</span>';
    if ($awayLogo !== '') {
        $html .= '<img class="at-crest" src="' . bao_h($awayLogo) . '" alt="" width="28" height="28" loading="lazy">';
    } else {
        $html .= '<span class="at-crest at-crest-fallback">' . bao_h(bao_team_initials($away)) . '</span>';
    }
    $html .= '</div>';
    $html .= '</div>';

    // Time / score (+ win/lost next to score)
    $html .= '<div class="at-card-meta">';
    if ($score && $score !== '—' && $score !== '-') {
        $html .= '<span class="at-score-row">';
        $html .= '<span class="at-score' . ($isLive ? ' is-live' : '') . '">' . bao_h($score) . '</span>';
        if ($showTick) {
            $html .= '<span class="at-outcome-tick" title="' . bao_h($tickTitle) . '" aria-label="' . bao_h($tickTitle) . '">'
                . '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false">'
                . '<circle cx="12" cy="12" r="11" fill="currentColor"/>'
                . '<path d="M7 12.5l3 3 7-7" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>'
                . '</svg>'
                . '</span>';
        } elseif ($showLost) {
            $html .= '<span class="at-outcome-lost" title="Lost" aria-label="Lost">❌</span>';
        }
        $html .= '</span>';
    } elseif ($time !== '') {
        $html .= '<span class="at-time bao-kickoff-time"'
            . ($kickoffIso !== '' ? ' data-kickoff-utc="' . bao_h($kickoffIso) . '"' : '')
            . ' title="Kick-off time (shown in your timezone)">';
        $html .= '<span class="at-clock" aria-hidden="true"></span>';
        $html .= '<span class="bao-kickoff-label">' . bao_h($time) . '</span>';
        $html .= '</span>';
    }
    $html .= '</div>';

    // Tip footer
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
 * @param list<array> $games
 * @param array{title?: string, class?: string} $opts
 */
function bao_matches_html(array $games, array $opts = []): string {
    $title = $opts['title'] ?? '';
    $class = $opts['class'] ?? '';
    $html = '<div class="matches-block' . ($class ? ' ' . bao_h($class) : '') . '">';
    if ($title !== '') {
        $html .= '<h2 class="at-matches-title">' . bao_h($title) . '</h2>';
    }
    $html .= '<div class="matches-container at-matches-grid">';
    foreach ($games as $g) {
        $html .= bao_match_card($g);
    }
    $html .= '</div></div>';
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
