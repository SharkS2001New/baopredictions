<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yesterday's Football Predictions &amp; Results | Bao Predictions</title>
  <meta name="description" content="Check yesterday's football predictions with final scores, wins and losses, model leans and transparent tip results from Bao Predictions.">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://www.baopredictions.com/football-predictions-yesterday">

  <meta name="keywords" content="football predictions yesterday, yesterday football tips, prediction results yesterday, bao predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Yesterday's Football Predictions &amp; Results | Bao Predictions">
  <meta name="twitter:description" content="Yesterday's football predictions with final scores, wins, losses and transparent tip results.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/football-predictions-yesterday">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/football-predictions-yesterday">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Yesterday's Football Predictions &amp; Results | Bao Predictions">
  <meta property="og:description" content="Yesterday's football predictions with final scores, wins, losses and transparent tip results.">
  <meta property="og:url" content="https://www.baopredictions.com/football-predictions-yesterday">
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
$payload = bao_curl_api('/api/football-predictions-yesterday');
$games = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
  ? $payload['games']
  : [];
$stats = bao_api_stats();
$yDateRaw = is_array($payload) && !empty($payload['date'])
  ? (string) $payload['date']
  : date('Y-m-d', strtotime('-1 day'));
$yesterdayLabel = date('l, j F Y', strtotime($yDateRaw));
$yesterdayShort = date('j F Y', strtotime($yDateRaw));
$updatedIso = is_array($stats) && !empty($stats['last_updated'])
  ? (string) $stats['last_updated']
  : date('c');
$updatedDate = date('j F Y', strtotime($updatedIso));
$updatedTime = date('H:i', strtotime($updatedIso));

$yWins = 0;
$yLosses = 0;
$yPending = 0;
foreach ($games as $g) {
  if (!is_array($g)) {
    continue;
  }
  $won = $g['won'] ?? null;
  if ($won === true) {
    $yWins++;
  } elseif ($won === false) {
    $yLosses++;
  } else {
    $yPending++;
  }
}
$yAnalysed = count($games);
$ySettled = $yWins + $yLosses;
$yRate = $ySettled > 0 ? (int) round(100 * $yWins / $ySettled) : null;
$settledCount = $ySettled;
?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Football Predictions Yesterday</span></li>
  </ol>
</nav>

<header class="page-hero">
    <h1>Yesterday's Football Predictions</h1>
<p class="lede">How tips published for <strong><?php echo bao_h($yesterdayLabel); ?></strong> finished — every win and loss kept on the board so you can judge the record, not marketing claims.</p>
  </header>

</div>

<?php if ($yAnalysed > 0): ?>
<section class="section-tight section-dark" aria-label="Yesterday's prediction performance">
  <div class="wrap">
    <div class="track-strip track-strip--yesterday">
      <div><strong><?php echo (int) $yAnalysed; ?></strong><span>Matches analysed</span></div>
      <div><strong><?php echo (int) $yWins; ?></strong><span>Predictions won</span></div>
      <div><strong><?php echo (int) $yLosses; ?></strong><span>Predictions lost</span></div>
      <div><strong><?php echo $yRate !== null ? (int) $yRate . '%' : '—'; ?></strong><span>Success rate</span></div>
    </div>
    <p class="track-strip-note">Based on <?php echo (int) $ySettled; ?> settled tip<?php echo $ySettled === 1 ? '' : 's'; ?> from <?php echo bao_h($yesterdayShort); ?><?php
if ($yPending > 0) {
  echo ' · ' . (int) $yPending . ' still pending';
}
?>. Success rate = wins ÷ settled.</p>
  </div>
</section>
<?php endif; ?>

<section class="section-tight">
  <div class="wrap wrap-wide">
<div class="main-grid">
<div class="matches-area" data-bao-result-filters>
    <h2 class="section-title">Yesterday's Results</h2>
    <p class="text-muted" style="margin:0 0 0.85rem">Results from tips published for <strong><?php echo bao_h($yesterdayLabel); ?></strong>. Filter the board or open the longer <a href="/results">Results</a> archive for a seven-day view.</p>

<?php if ($yAnalysed > 0): ?>
    <div class="result-filters" role="group" aria-label="Filter yesterday's results">
      <button type="button" class="result-filter-btn is-active" data-result-filter="all" aria-pressed="true">All results</button>
      <button type="button" class="result-filter-btn" data-result-filter="won" aria-pressed="false">Won only</button>
      <button type="button" class="result-filter-btn" data-result-filter="lost" aria-pressed="false">Lost only</button>
      <span class="result-filter-count" data-result-count>Showing <?php echo (int) $yAnalysed; ?> result<?php echo $yAnalysed === 1 ? '' : 's'; ?></span>
    </div>
<?php endif; ?>

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
    <h2>Football Predictions Yesterday</h2>
    <p><strong>Football predictions yesterday</strong> are useful for checking how published football tips performed after the matches have finished. Bao Predictions keeps yesterday's selections visible alongside the actual results, so you can see which predictions won, which lost and what the original model lean was before the match started.</p>
    <p>This makes the page different from a list of final football scores. The purpose is to compare <strong>prediction versus outcome</strong> and review the record without removing unsuccessful selections. The settled board above is that daily audit trail for <strong><?php echo bao_h($yesterdayLabel); ?></strong><?php
if ($ySettled > 0) {
  echo ' — currently <strong>' . (int) $yWins . '</strong> won and <strong>' . (int) $yLosses . '</strong> lost';
  if ($yRate !== null) {
    echo ' (<strong>' . (int) $yRate . '%</strong> of settled tips)';
  }
}
?>.</p>

    <h2>How to Read Yesterday's Football Predictions</h2>
    <p>Bao's yesterday page shows settled predictions from the previous day's fixtures. Each entry identifies the competition, teams, final score, selected market and the published model assessment.</p>
    <?php echo bao_settled_audit_examples_html($games); ?>
    <p>That distinction matters when reviewing football predictions. Looking only at winning selections can make a prediction service appear more accurate than it actually was. A proper review needs the losing calls as well.</p>
    <p>Bao currently displays:</p>
    <ul>
      <li>The original prediction</li>
      <li>The final match result</li>
      <li>The market selected</li>
      <li>The model confidence shown before the match</li>
      <li>A short explanation for the original selection</li>
      <li>Wins and losses without removing unsuccessful picks</li>
    </ul>
    <p>The page was last updated <strong><?php echo bao_h($updatedDate); ?> at <?php echo bao_h($updatedTime); ?> EAT</strong><?php
if ($settledCount > 0) {
  echo ', with <strong>' . (int) $settledCount . '</strong> settled selections from the previous day\'s matches';
} else {
  echo ', with settled selections from the previous day\'s matches as fixtures finish';
}
?>.</p>

    <h2>Reviewing AI Football Predictions Yesterday</h2>
    <p>Searches for <strong>AI football predictions yesterday</strong> are increasingly focused on whether data-driven forecasts actually performed as expected. The useful way to assess an AI or statistical prediction is to compare its published call with the completed match, rather than judging it from the confidence number alone.</p>
    <p>Bao makes that comparison possible because its yesterday archive retains both successful and unsuccessful predictions. Confidence scores are <strong>model leans, not predicted win rates</strong>, so a 76% or 80% rating should not be interpreted as an 80% guarantee of winning.</p>
    <p>The archive can also be used alongside the longer results record. Yesterday is the daily verification layer; the wider <a href="/results">Results</a> section provides the longer-term view rather than relying on one day's performance.</p>

    <h2>Why Yesterday's Results Matter</h2>
    <p>A prediction should be judged after the match, not just when it is published. Yesterday's page provides that audit trail by leaving the original selection and its outcome together.</p>
    <p>That gives bettors a straightforward way to ask: <strong>What was predicted, what actually happened, and how often has the prediction process performed over a larger sample?</strong></p>

    <p class="seo-related"><strong>Related:</strong> <a href="/1x2-predictions">1X2 predictions</a> · <a href="/ht-ft-predictions">HT/FT predictions</a> · <a href="/results">Results</a> · <a href="/responsible-betting">Responsible betting</a></p>
  </div>
</section>

<?php
$faqs = [
  [
    'q' => 'What is the Yesterday page?',
    'a' => 'A single-matchday audit — every published tip from the previous day beside its final score, wins and losses kept on the same cards.

It answers “how did yesterday’s board land?” Results covers a rolling seven-day window instead of one day.',
  ],
  [
    'q' => 'Do you remove losing tips?',
    'a' => 'No. Unsuccessful predictions stay published with the original lean, market and reasoning. That is the point of an audit page.

Generic tip sites often delete losers. Bao keeps them so you can judge the record honestly — including calls that looked strong on paper.',
  ],
  [
    'q' => 'How is Yesterday different from Results?',
    'a' => 'Yesterday is one matchday only. Results is the rolling last seven days of settled tips plus the headline track-record strip above the list.

A single day can look unusually hot or cold; the seven-day view adds context. Both pages keep losses visible.',
  ],
  [
    'q' => 'What do confidence figures mean here?',
    'a' => 'The percentage shown is the model lean at publish time — capped at 85%, never 100% — not a guaranteed win rate for that card.

A high lean that lost still tells you something: even top-band selections fail. Compare the lean to the outcome rather than assuming the number was a promise.',
  ],
  [
    'q' => 'Can I see examples of wins and losses?',
    'a' => 'Yes. Use All / Won only / Lost only above the board, or read the cards directly — each settled tip keeps the original pick beside the final score.

Use those examples to see how form, team news and venue context played out — not as proof the next card will repeat.',
  ],
  [
    'q' => 'Where is today\'s live board?',
    'a' => 'Football Predictions Today for the current matchday. Tomorrow for the early next-day board. Livescores for fixtures already underway.

Check Today for pre-match leans on the current calendar day.',
  ],
];
?>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">Football Predictions Yesterday FAQ</h2>
    <?php echo bao_faq_items_html($faqs); ?>

  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/timezone.js?v=20260913c" defer></script>
<script src="/assets/js/load-more.js?v=20260913e" defer></script>
<script src="/assets/js/result-filters.js?v=20260913e" defer></script>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_faq_schema($faqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Football Predictions Yesterday', 'url' => '/football-predictions-yesterday'],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
