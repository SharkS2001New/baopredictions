<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Football Results &amp; Prediction Track Record | Bao Predictions</title>
  <meta name="description" content="Check recent football results and settled tip outcomes from the last 7 days — wins, losses, scores and prediction performance from Bao Predictions.">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://www.baopredictions.com/results">

  <meta name="keywords" content="football results, prediction results, tip track record, settled tips, bao predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Football Results &amp; Prediction Track Record | Bao Predictions">
  <meta name="twitter:description" content="Recent football results and settled tip outcomes — wins, losses, scores and prediction performance.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/results">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/results">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Football Results &amp; Prediction Track Record | Bao Predictions">
  <meta property="og:description" content="Recent football results and settled tip outcomes — wins, losses, scores and prediction performance.">
  <meta property="og:url" content="https://www.baopredictions.com/results">
  <meta property="og:site_name" content="Bao Predictions">
    <script>
  (function () {
    try {
      var t = localStorage.getItem('bao-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {}
  })();
  </script>
<?php require __DIR__ . '/../components/head-assets.php'; ?>
<?php require __DIR__ . '/../components/favicon.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../components/header.php'; ?>
<main id="main">
<?php
require_once __DIR__ . '/../components/seo.php';
require_once __DIR__ . '/../components/api-curl.php';
$baoStats = bao_api_stats();
$baoTrack = is_array($baoStats['track'] ?? null) ? $baoStats['track'] : [];
$updatedIso = is_array($baoStats) && !empty($baoStats['last_updated'])
  ? (string) $baoStats['last_updated']
  : date('c');
$updatedDate = date('j F Y', strtotime($updatedIso));
$payload = bao_curl_api('/api/results');
$games = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
  ? $payload['games']
  : [];
$weekCount = count($games);
?>

<div class="wrap wrap-wide">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Football Results</span></li>
  </ol>
</nav>

  <header class="page-hero page-hero--full">
    <h1>Football Prediction Results</h1>
<p class="lede">Rolling seven-day settled tips with the original leans beside final scores. Separate from Yesterday's single-matchday audit — use Results to review a longer performance window.</p>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_intro_links_html(); ?>
  </header>
</div>

<section class="section-tight section-dark">
  <div class="wrap">
    <div class="track-strip">
      <div><strong><?= htmlspecialchars(bao_fmt_pct(isset($baoStats['win_rate']) ? (float) $baoStats['win_rate'] : (isset($baoTrack['win_rate']) ? (float) $baoTrack['win_rate'] : null))) ?></strong><span>Win rate</span></div>
      <div><strong><?= htmlspecialchars(bao_fmt_roi(isset($baoStats['roi']) ? (float) $baoStats['roi'] : (isset($baoTrack['roi']) ? (float) $baoTrack['roi'] : null))) ?></strong><span>ROI</span></div>
      <div><strong><?= htmlspecialchars((string) ((int) ($baoStats['settled_tips'] ?? $baoTrack['settled_tips'] ?? 0))) ?></strong><span>Settled tips</span></div>
      <div><strong><?= htmlspecialchars((string) ((int) ($baoStats['win_streak'] ?? $baoStats['recent']['win_streak'] ?? 0))) ?></strong><span>Best streak (3 days)</span></div>
    </div>
    <p class="text-muted" style="margin:0.75rem 0 0;font-size:0.9rem;text-align:center">Headline figures use the qualifying 1X2 sample — not only the seven-day list below.</p>
  </div>
</section>

<section class="section">
  <div class="wrap wrap-wide">
    <div class="main-grid">
<div class="matches-area">
    <h2 class="section-title">Recent settled tips (last 7 days)</h2>
    <p class="text-muted" style="margin:0 0 1rem">Rolling week of published 1X2 tips that settled — newest first. For a single matchday only, use <a href="/football-predictions-yesterday">yesterday's football predictions</a>.</p>

<?php
if ($payload === null) {
  echo bao_api_fail_msg();
} elseif (!$games) {
  echo bao_api_empty_msg('fixtures');
} else {
  echo bao_matches_html($games, ['page' => (string)($payload['page'] ?? '')]);
}
?>
</div><!-- /.matches-area -->
<?php require __DIR__ . '/../components/sidebar.php'; ?>

</div><!-- /.main-grid -->
  </div>
</section>

<section class="section section-muted bao-seo-stack">
  <div class="wrap prose">
    <h2>Football Results: Recent Scores &amp; Prediction Results</h2>
    <p><strong>Football results</strong> show what happened after the predictions were published. Bao Predictions tracks settled 1X2 predictions across a rolling <strong>last seven days</strong>, showing the actual final score alongside the original prediction, published odds and model lean.</p>
    <p>This makes the Results page useful for more than checking a score. You can see which predictions matched the final result, which did not, and review Bao's recent prediction record without removing unsuccessful selections.</p>

    <h2>Recent Football Results</h2>
    <p>The Bao Results page covers a rolling seven-day window of <strong>settled football tips</strong>. As matches finish and predictions settle, the page records the outcome and keeps the original prediction available for comparison.</p>
    <p>Each result can show:</p>
    <ul>
      <li><strong>Competition</strong> — the league or competition in which the match was played</li>
      <li><strong>Teams</strong> — the home and away sides</li>
      <li><strong>Final score</strong> — the actual result after the match</li>
      <li><strong>1X2 prediction</strong> — the selection published before kick-off</li>
      <li><strong>Published odds</strong> — the price recorded with the original prediction</li>
      <li><strong>Model lean</strong> — the model's assessment at the time the prediction was published</li>
      <li><strong>Outcome</strong> — whether the original selection won or lost</li>
    </ul>
    <p>This gives the results page a simple purpose: <strong>compare the prediction with what actually happened on the pitch</strong>.</p>
    <p>The current page was updated on <strong><?php echo bao_h($updatedDate); ?></strong><?php
if ($weekCount > 0) {
  echo ' and displays <strong>' . (int) $weekCount . '</strong> settled tips from the preceding seven days';
} else {
  echo ' and displays settled tips from the preceding seven days as fixtures finish';
}
?>. The headline performance figures above are presented separately from the rolling list of recent matches, so the seven-day entries should not be confused with the full qualifying record used for those figures.</p>

    <h2>Football Results Today and Yesterday</h2>
    <p>The Results page changes throughout the week as new matches settle. That makes it relevant to searches for <strong>football results today</strong>, while completed fixtures from earlier in the seven-day window remain available for recent-result checks.</p>
    <p>For a specific previous matchday, Bao also has a separate <a href="/football-predictions-yesterday">Yesterday</a> page. That page is focused on the previous day's predictions, whereas the Results page provides the wider seven-day view.</p>
    <p>This distinction matters when checking prediction performance. A single day's results can be unusually strong or weak, while a rolling seven-day record gives more context around recent performance.</p>

    <h2>Football Prediction Results</h2>
    <p>The most useful part of the Results page is the connection between the <strong>original prediction and final result</strong>.</p>
    <p>A winning prediction is easy to notice, but losing selections are just as important when assessing a prediction service. Bao keeps unsuccessful predictions in the record instead of presenting only winning examples.</p>
    <p>The page also explains how its headline record is calculated. The qualifying performance figures use settled 1X2 tips with the required model information and a book price, while incomplete entries and postponed fixtures are excluded.</p>
    <p>Model confidence should also be read correctly. A model lean is an assessment of the available data, not a guaranteed probability that the selection will win. Football remains unpredictable, and a correct way to judge predictions is to compare published selections with their actual results over a meaningful sample.</p>
    <p>The <strong>last seven days</strong> therefore provide a practical recent snapshot: what Bao predicted, what happened, and how the selections performed.</p>
    <p><strong>18+ | Gamble responsibly.</strong> Football predictions are informational opinions, not guaranteed outcomes. Never stake money you cannot afford to lose. <a href="/responsible-betting">Responsible betting</a>.</p>

    <p class="seo-related"><strong>Related:</strong> <a href="/football-predictions-yesterday">yesterday's football predictions</a> · <a href="/football-predictions-today">today's football predictions</a></p>
  </div>
</section>

<?php
$faqs = [
  [
    'q' => 'Is this the same as Yesterday?',
    'a' => 'No. Yesterday is one matchday. Results is the rolling last seven days of settled tips, plus the headline track-record strip at the top.

Use Yesterday when you want a single day\'s audit. Use Results when you want recent context across the week and the longer qualifying sample.',
  ],
  [
    'q' => 'Do you hide losing tips?',
    'a' => 'No. Wins and losses both stay published so the prediction record can be reviewed honestly.

Each entry keeps the original 1X2 selection, published odds where recorded, model lean and final score together.',
  ],
  [
    'q' => 'How are the headline figures calculated?',
    'a' => 'Settled 1X2 tips with the required model information and a book price. Incomplete entries and postponements are excluded. Those figures are separate from the seven-day list alone.

Headline figures update as the qualifying sample grows.',
  ],
  [
    'q' => 'Are model leans guaranteed win rates?',
    'a' => 'No. A model lean is an assessment of the available data at publish time — capped at 85% — not a guaranteed probability of winning.

The honest read is how published selections perform over a meaningful sample on this page, including losses.',
  ],
  [
    'q' => 'Does this cover football results today?',
    'a' => 'Yes — as today\'s matches settle they enter the rolling seven-day board. Earlier days in the window remain available for recent checks.

The list fills as fixtures finish.',
  ],
  [
    'q' => 'Where else can I look?',
    'a' => 'Yesterday\'s football predictions for a single matchday, and today\'s football predictions for the live pre-match board.

How We Predict explains methodology; Responsible Betting covers staking risk (18+).',
  ],
];
?>


<section class="section section-tight bao-analyst-wrap">
  <div class="wrap">
    <?php echo bao_analyst_card_html(); ?>
  </div>
</section>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">Football Results FAQ</h2>
    <?php echo bao_faq_items_html($faqs); ?>

  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/timezone.js?v=20260913c" defer></script>
<script src="/assets/js/load-more.js?v=20260913c" defer></script>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_faq_schema($faqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Football Results', 'url' => '/results'],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
