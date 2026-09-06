<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Football Tip Results &amp; Track Record | Bao Predictions</title>
  <meta name="description" content="Settled prediction results — wins and losses both stay published so you can audit our track record. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/results/">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="Football Tip Results &amp; Track Record | Bao Predictions">
  <meta name="keywords" content="football tip results, prediction track record, tipster accuracy">
  <meta name="author" content="Bao Predictions Analysis Team">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Bao Predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Football Tip Results &amp; Track Record | Bao Predictions">
  <meta name="twitter:description" content="Settled prediction results — wins and losses both stay published so you can audit our track record. 18+ only.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/results/">
  <!--BAO_HEAD_EXTRA_END-->

  <meta property="og:title" content="Football Tip Results &amp; Track Record | Bao Predictions">
  <meta property="og:description" content="Settled prediction results — wins and losses both stay published so you can audit our track record. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/results/">
  <meta property="og:type" content="article">
  <meta property="og:site_name" content="Bao Predictions">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Source+Sans+3:wght@400;500;600&display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
  (function () {
    try {
      var t = localStorage.getItem('bao-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {}
  })();
  </script>
  <link rel="stylesheet" href="/assets/css/main.css">
<?php require __DIR__ . '/../components/favicon.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../components/header.php'; ?>
<main id="main">

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">

    <li>

      <a href="/">Home</a>

    </li>

    <li>

      <span aria-current="page">Results</span>

    </li>

  </ol>
</nav>

  <header class="page-hero">
    <h1>Results &amp; Track Record</h1>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_last_updated_html(); ?>
<?php echo bao_rg_notice_html(); ?>

<p class="lede">Is Bao Predictions accurate? Judge the numbers — wins and losses — not slogans.</p>
  </header>
</div>

<?php
require_once __DIR__ . '/../components/api-curl.php';
$baoStats = bao_api_stats();
$baoTrack = is_array($baoStats['track'] ?? null) ? $baoStats['track'] : [];
?>
<section class="section-tight section-dark">
  <div class="wrap">
    <div class="track-strip">
      <div><strong><?= htmlspecialchars(bao_fmt_pct(isset($baoStats['win_rate']) ? (float) $baoStats['win_rate'] : (isset($baoTrack['win_rate']) ? (float) $baoTrack['win_rate'] : null))) ?></strong><span>Win rate</span></div>
      <div><strong><?= htmlspecialchars(bao_fmt_roi(isset($baoStats['roi']) ? (float) $baoStats['roi'] : (isset($baoTrack['roi']) ? (float) $baoTrack['roi'] : null))) ?></strong><span>ROI</span></div>
      <div><strong><?= htmlspecialchars((string) ((int) ($baoStats['settled_tips'] ?? $baoTrack['settled_tips'] ?? 0))) ?></strong><span>Settled tips</span></div>
      <div><strong><?= htmlspecialchars((string) ((int) ($baoStats['win_streak'] ?? $baoStats['recent']['win_streak'] ?? 0))) ?></strong><span>Best streak (3 days)</span></div>
    </div>
  </div>
</section>
<section class="section">
  <div class="wrap wrap-wide">
    <div class="main-grid">
<?php require __DIR__ . '/../components/sidebar.php'; ?>
<div class="matches-area">
    <h2 class="section-title">Yesterday's settled tips</h2>

  <?php
require_once __DIR__ . '/../components/api-curl.php';
$payload = bao_curl_api('/api/results');
if ($payload === null) {
  echo bao_api_fail_msg();
} elseif (empty($payload['games'])) {
  echo bao_api_empty_msg('fixtures');
} else {
  echo bao_matches_html($payload['games'], ['title' => 'Settled results']);
}
?>
</div><!-- /.matches-area -->
</div><!-- /.main-grid -->
  </div>
</section>
<!-- Page write-up: edit this block in this PHP file only (source of truth). -->
<section class="section section-muted bao-seo-stack">
  <div class="wrap prose bao-writeup">
<p class="seo-unique">We track every published prediction from the moment it goes live. Wins and losses both stay visible. Use yesterday&#039;s page for the most recent matchday, and this page for the longer-term picture by market and over time.</p>
<!--BAO_FEATURED_START-->
<aside class="featured-banner" aria-label="Editor note"><p class="featured-kicker">Wins and losses — both stay published</p><p class="featured-text">Audit our settled tips before you trust today&#039;s board. Past performance is not a guarantee of future results.</p></aside>
<!--BAO_FEATURED_END-->
<p class="seo-related"><strong>Related:</strong> <a href="/football-predictions-yesterday">Yesterday&#039;s results</a> · <a href="/how-we-predict">How we predict</a> · <a href="/about-us">About us</a></p>
  </div>
<!--BAO_ARTICLE_START-->
  <div class="wrap seo-article-block">
<article class="content-article prose"><header class="article-header"><h2 class="article-title">How to read Bao&#039;s track record</h2></header><div class="article-content"><p>Win rate counts only settled predictions where the specific market outcome matched the result. Postponements are excluded. ROI, when shown, uses published odds at tip time — not closing lines after the fact.</p></div></article>
  </div>
<!--BAO_ARTICLE_END-->
</section>
<section class="section section-tight bao-faq">
  <div class="wrap"><h2 class="section-title">Results FAQ</h2><ul class="faq-list"><li><details><summary>Do you hide losing tips?</summary><p>No. Wins and losses both stay published.</p></details></li><li><details><summary>How is win rate calculated?</summary><p>Settled predictions where the specific market outcome matched the result. Postponements are excluded.</p></details></li><li><details><summary>Is past performance a guarantee?</summary><p>No. It is a transparency tool only.</p></details></li><li><details><summary>What odds do you use for ROI?</summary><p>Published odds at tip time when shown — not closing lines after the fact.</p></details></li><li><details><summary>How often is Results updated?</summary><p>After matchdays as fixtures settle.</p></details></li><li><details><summary>Where are yesterday&#039;s tips?</summary><p>On the Yesterday predictions page for a daily verification layer.</p></details></li></ul>
  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/timezone.js" defer></script>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_faq_schema(array (
  0 =>
  array (
    'q' => 'Do you hide losing tips?',
    'a' => 'No. Wins and losses both stay published.',
  ),
  1 =>
  array (
    'q' => 'How is win rate calculated?',
    'a' => 'Settled predictions where the specific market outcome matched the result. Postponements are excluded.',
  ),
  2 =>
  array (
    'q' => 'Is past performance a guarantee?',
    'a' => 'No. It is a transparency tool only.',
  ),
  3 =>
  array (
    'q' => 'What odds do you use for ROI?',
    'a' => 'Published odds at tip time when shown — not closing lines after the fact.',
  ),
  4 =>
  array (
    'q' => 'How often is Results updated?',
    'a' => 'After matchdays as fixtures settle.',
  ),
  5 =>
  array (
    'q' => 'Where are yesterday\'s tips?',
    'a' => 'On the Yesterday predictions page for a daily verification layer.',
  ),
)); echo bao_breadcrumb_schema(array (
  0 =>
  array (
    'name' => 'Home',
    'url' => '/',
  ),
  1 =>
  array (
    'name' => 'Results',
    'url' => '/results',
  ),
)); echo bao_article_schema('How to read Bao\'s track record', 'Settled prediction results — wins and losses both stay published so you can audit our track record. 18+ only.', '/results'); echo bao_organization_schema(); ?>
<!--BAO_SCHEMA_END-->
</body>
</html>
