<?php
/**
 * Accuratetip-style left sidebar for prediction pages.
 * Set $bao_sidebar_active to a path slug (e.g. '1x2-predictions') to highlight a market.
 */
if (!isset($bao_sidebar_active)) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $path = trim(parse_url($uri, PHP_URL_PATH) ?? '', '/');
    $bao_sidebar_active = $path === '' ? 'home' : $path;
}

function bao_sidebar_active(string $slug, string $active): string {
    return $active === $slug || str_ends_with($active, $slug) ? ' is-active' : '';
}

require_once __DIR__ . '/api-curl.php';
$baoStats = bao_api_stats();
$baoMarkets = is_array($baoStats['markets'] ?? null) ? $baoStats['markets'] : [];
$baoToday = is_array($baoStats['today'] ?? null) ? $baoStats['today'] : [];
// Defense in depth: never show a win rate / units / avg odds when nothing has settled today.
$baoTodaySettled = (int) ($baoToday['settled_total'] ?? 0);
$baoTodayWinRate = $baoTodaySettled > 0 && isset($baoToday['win_rate']) && $baoToday['win_rate'] !== null
    ? (float) $baoToday['win_rate']
    : null;
$baoTodayUnits = $baoTodaySettled > 0 && isset($baoToday['units']) && $baoToday['units'] !== null
    ? (float) $baoToday['units']
    : null;
$baoTodayAvgOdds = $baoTodaySettled > 0 && isset($baoToday['avg_odds']) && $baoToday['avg_odds'] !== null
    ? (float) $baoToday['avg_odds']
    : null;

$mc = static function (array $markets, string $key): string {
    $n = isset($markets[$key]) ? (int) $markets[$key] : 0;
    return (string) $n;
};

$a = $bao_sidebar_active;
require_once __DIR__ . '/tip-of-day.php';
?>
<aside class="sidebar" aria-label="Markets and statistics">
  <div class="tip-day-slot tip-day-slot--sidebar">
<?php echo bao_tip_of_day_html(); ?>
  </div>

  <div class="sidebar-collapsible">
    <section class="panel sidebar-panel-markets">
      <header class="panel-header">
        <h2 class="panel-title">Betting Markets</h2>
      </header>
      <div class="panel-content">
        <nav class="market-list" aria-label="Betting markets">
          <a href="/1x2-predictions" class="market-item-link<?= bao_sidebar_active('1x2-predictions', $a) ?>">
            <span class="market-info">
              <span class="market-icon">1X2</span>
              <span class="market-name">Match Result</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, '1x2-predictions')) ?></span>
          </a>
          <a href="/over-under-predictions" class="market-item-link<?= bao_sidebar_active('over-under-predictions', $a) ?>">
            <span class="market-info">
              <span class="market-icon">O/U</span>
              <span class="market-name">Over/Under</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'over-under-predictions')) ?></span>
          </a>
          <a href="/btts-predictions" class="market-item-link<?= bao_sidebar_active('btts-predictions', $a) ?>">
            <span class="market-info">
              <span class="market-icon">GG</span>
              <span class="market-name">BTTS</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'btts-predictions')) ?></span>
          </a>
          <a href="/double-chance-predictions" class="market-item-link<?= bao_sidebar_active('double-chance-predictions', $a) ?>">
            <span class="market-info">
              <span class="market-icon">DC</span>
              <span class="market-name">Double Chance</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'double-chance-predictions')) ?></span>
          </a>
          <a href="/ht-ft-predictions" class="market-item-link<?= bao_sidebar_active('ht-ft-predictions', $a) ?>">
            <span class="market-info">
              <span class="market-icon">H/F</span>
              <span class="market-name">HT/FT</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'ht-ft-predictions')) ?></span>
          </a>
          <div class="market-divider" role="separator"></div>
          <a href="/live-football-predictions" class="market-item-link<?= bao_sidebar_active('live-football-predictions', $a) ?>">
            <span class="market-info">
              <span class="market-icon">LV</span>
              <span class="market-name">Livescores</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'live-football-predictions')) ?></span>
          </a>
          <a href="/must-win-teams-today" class="market-item-link<?= bao_sidebar_active('must-win-teams-today', $a) ?>">
            <span class="market-info">
              <span class="market-icon">MW</span>
              <span class="market-name">Must Win Teams</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'must-win-teams-today')) ?></span>
          </a>
          <a href="/sure-bets-today" class="market-item-link<?= bao_sidebar_active('sure-bets-today', $a) ?>">
            <span class="market-info">
              <span class="market-icon">SB</span>
              <span class="market-name">Sure Bets</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'sure-bets-today')) ?></span>
          </a>
          <a href="/banker-of-the-day" class="market-item-link<?= bao_sidebar_active('banker-of-the-day', $a) ?>">
            <span class="market-info">
              <span class="market-icon">BK</span>
              <span class="market-name">Banker of the Day</span>
            </span>
            <span class="market-count">1</span>
          </a>
          <a href="/accumulator-tips" class="market-item-link<?= bao_sidebar_active('accumulator-tips', $a) ?>">
            <span class="market-info">
              <span class="market-icon">AC</span>
              <span class="market-name">Accumulators</span>
            </span>
            <span class="market-count"><?= htmlspecialchars($mc($baoMarkets, 'accumulator-tips')) ?></span>
          </a>
        </nav>
      </div>
    </section>

    <section class="panel">
      <header class="panel-header">
        <h2 class="panel-title">Today's Performance</h2>
      </header>
      <div class="panel-content">
        <dl class="quick-stats">
          <div class="quick-stat">
            <dt class="quick-stat-label">Settled</dt>
            <dd class="quick-stat-value"><?= htmlspecialchars((string) ($baoToday['settled_display'] ?? '—')) ?></dd>
          </div>
          <div class="quick-stat">
            <dt class="quick-stat-label">Win Rate</dt>
            <dd class="quick-stat-value"><?= htmlspecialchars(bao_fmt_pct($baoTodayWinRate, 0)) ?></dd>
          </div>
          <div class="quick-stat">
            <dt class="quick-stat-label">Units</dt>
            <dd class="quick-stat-value"><?= htmlspecialchars(bao_fmt_units($baoTodayUnits)) ?></dd>
          </div>
          <div class="quick-stat">
            <dt class="quick-stat-label">Avg Odds</dt>
            <dd class="quick-stat-value"><?= htmlspecialchars($baoTodayAvgOdds !== null ? number_format($baoTodayAvgOdds, 2) : '—') ?></dd>
          </div>
        </dl>
      </div>
    </section>

    <section class="panel panel-cta">
      <div class="panel-content">
        <h3 class="panel-cta-title">Transparent track record</h3>
        <p class="panel-cta-text">Wins and losses published. Judge our accuracy for yourself.</p>
        <a href="/results" class="btn btn-primary btn-block">View Results</a>
        <a href="/how-we-predict" class="btn btn-outline btn-block" style="margin-top:0.5rem">How We Predict</a>
      </div>
    </section>
  </div>
</aside>
