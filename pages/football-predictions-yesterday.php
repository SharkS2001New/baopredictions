<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yesterday's Football Predictions Results | Bao Predictions</title>
  <meta name="description" content="Yesterday's tips vs actual results. Every published pick stays listed — wins and losses. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/football-predictions-yesterday">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="Yesterday's Football Predictions Results | Bao Predictions">
  <meta name="keywords" content="yesterday football predictions results, tip verification">
  <meta name="author" content="Bao Predictions Analysis Team">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Bao Predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Yesterday's Football Predictions Results | Bao Predictions">
  <meta name="twitter:description" content="Yesterday's tips vs actual results. Every published pick stays listed — wins and losses. 18+ only.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/football-predictions-yesterday">
  <!--BAO_HEAD_EXTRA_END-->

  <meta property="og:title" content="Yesterday's Football Predictions Results | Bao Predictions">
  <meta property="og:description" content="Yesterday's tips vs actual results. Every published pick stays listed — wins and losses. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/football-predictions-yesterday">
  <meta property="og:type" content="article">
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
$yesterdayLabel = date('l j F Y', strtotime('-1 day'));
?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Yesterday</span></li>
  </ol>
</nav>

<header class="page-hero">
    <h1>Yesterday's Predictions &amp; Results</h1>
<?php echo bao_last_updated_html(); ?>
<?php echo bao_rg_notice_html(); ?>

<p class="lede">Settled tips from yesterday. We show wins and losses so you can judge our track record honestly.</p>
  </header>

</div>

<section class="section-tight">
  <div class="wrap wrap-wide">
<div class="main-grid">
<div class="matches-area">
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
    <h2>Why we keep losing tips visible</h2>
    <p>This is where you can check our track record before trusting today's picks. Every prediction we made yesterday stays listed with the outcome — wins and losses both. If a pick didn't work out, we don't remove it. For the longer-term picture, use the full <a href="/results">results and statistics page</a>.</p>
    <?php echo bao_shortlist_summary_html($games, 'settled board', "Yesterday's"); ?>
    <p class="seo-related"><strong>Related:</strong> <a href="/results">Full results</a> · <a href="/football-predictions-today">Today's predictions</a> · <a href="/how-we-predict">How we predict</a></p>
  </div>
</section>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">Yesterday's Results FAQ</h2>
    <ul class="faq-list">
      <li><details><summary>Why keep losing tips?</summary><p>A site that deletes losers is not trustworthy. Yesterday is the daily proof layer.</p></details></li>
      <li><details><summary>Is this the full archive?</summary><p>Yesterday is the daily slice; Results is the longer track record.</p></details></li>
      <li><details><summary>When does Yesterday update?</summary><p>After the previous day's fixtures settle.</p></details></li>
      <li><details><summary>Can I compare to today?</summary><p>Yes — use Today for live tips and Yesterday for verification.</p></details></li>
      <li><details><summary>Do voids count as losses?</summary><p>Postponements are generally excluded from win-rate maths.</p></details></li>
      <li><details><summary>Are tips free to review?</summary><p>Yes.</p></details></li>
    </ul>
  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/timezone.js" defer></script>
<script src="/assets/js/load-more.js" defer></script>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php
$baoYFaqs = [
  ['q' => 'Why keep losing tips?', 'a' => 'A site that deletes losers is not trustworthy. Yesterday is the daily proof layer.'],
  ['q' => 'Is this the full archive?', 'a' => 'Yesterday is the daily slice; Results is the longer track record.'],
  ['q' => 'When does Yesterday update?', 'a' => 'After the previous day\'s fixtures settle.'],
  ['q' => 'Can I compare to today?', 'a' => 'Yes — use Today for live tips and Yesterday for verification.'],
  ['q' => 'Do voids count as losses?', 'a' => 'Postponements are generally excluded from win-rate maths.'],
  ['q' => 'Are tips free to review?', 'a' => 'Yes.'],
];
echo bao_faq_schema($baoYFaqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Yesterday', 'url' => '/football-predictions-yesterday'],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
