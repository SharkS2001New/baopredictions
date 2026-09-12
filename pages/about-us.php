<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us | Bao Predictions</title>
  <meta name="description" content="About Bao Predictions — Kenya-facing football tips and jackpot sheets with model leans, human review, and a public wins-and-losses record.">
  <link rel="canonical" href="https://www.baopredictions.com/about-us">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="About Us | Bao Predictions">
  <meta name="keywords" content="about bao predictions, bao predictions analysis team, kenya football tips">
  <meta name="author" content="Bao Predictions Analysis Team">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Bao Predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="About Us | Bao Predictions">
  <meta name="twitter:description" content="About Bao Predictions — Kenya-facing football tips and jackpot sheets with model leans, human review, and a public wins-and-losses record.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/about-us">
  <!--BAO_HEAD_EXTRA_END-->

  <meta property="og:title" content="About Us | Bao Predictions">
  <meta property="og:description" content="About Bao Predictions — Kenya-facing football tips and jackpot sheets with model leans, human review, and a public wins-and-losses record.">
  <meta property="og:url" content="https://www.baopredictions.com/about-us">
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
$stats = bao_api_stats();
$updatedIso = is_array($stats) && !empty($stats['last_updated'])
  ? (string) $stats['last_updated']
  : date('c');
$updatedDate = date('j F Y', strtotime($updatedIso));
$track = is_array($stats) && is_array($stats['track'] ?? null) ? $stats['track'] : [];
$settledTips = (int) ($track['settled_tips'] ?? $stats['settled_tips'] ?? 0);
$winRate = $track['win_rate'] ?? ($stats['win_rate'] ?? null);

$baoAboutFaqs = [
  [
    'q' => 'Are you a bookmaker?',
    'a' => 'No. Bao Predictions publishes analysis only. We do not accept stakes.',
  ],
  [
    'q' => 'Who writes the tips?',
    'a' => 'The Bao Predictions Analysis Team — a Kenya-facing editorial desk that reviews model output against team news before publishing.',
  ],
  [
    'q' => 'Where are you focused?',
    'a' => 'Kenya-facing readers and jackpot operators, with tip boards weighted to major European and global leagues. FKF Premier League fixtures publish when model and odds clear the same bar.',
  ],
  [
    'q' => 'How do I contact you?',
    'a' => 'Use Contact for tip corrections, partnerships and press.',
  ],
];
?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">About Us</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>About Bao Predictions</h1>
<?php echo bao_last_updated_html($updatedIso); ?>
<p class="lede">Kenya-facing football tips and jackpot sheets — model leans, human review, and a public record that keeps losses visible.</p>
  </header>

  <article class="prose">
<p>Bao Predictions is a football prediction site built for readers who want the tip, the reasoning, and a way to check what happened afterwards. We are not a bookmaker. We do not take stakes. Every published lean is an opinion based on available match data — not a guaranteed result.</p>

<h2>Who is behind the tips</h2>
<p>Tips are researched and signed off by the <strong>Bao Predictions Analysis Team</strong>, a small Kenya-facing desk that follows football markets and local jackpot products. Model output starts the process; an analyst still checks team news, rotation risk and price context before a card goes live. Nothing is published only because a fixture exists on the calendar.</p>
<p>Methodology lives on <a href="/how-we-predict">How We Predict</a>. The numbers live on <a href="/results">Football Results</a> and <a href="/football-predictions-yesterday">Football Predictions Yesterday</a>.</p>

<h2>What we cover</h2>
<p>Day to day we publish single-match tips across 1X2, BTTS, Over/Under, Double Chance and HT/FT, plus shortlists such as Must Win Teams Today and Sure Bets Today. Jackpot sheets cover SportPesa Mega Jackpot, SportPesa Midweek Jackpot, Betika Midweek Jackpot, SportyBet Daily, Odibets Laki Tatu and Mozzart Super Daily Jackpot.</p>
<p>We also ingest <strong>FKF Premier League</strong> fixtures from our Kenya feed. They appear when the model split and book prices clear the same publish bar we use elsewhere. Sparse KPL odds mean those cards show up less often than Premier League or Champions League tips — we would rather leave a match off than invent a lean.</p>
<p>As of <strong><?php echo bao_h($updatedDate); ?></strong><?php
if ($settledTips > 0 && $winRate !== null) {
  echo ', the qualifying settled 1X2 sample stands at <strong>' . (int) $settledTips
    . '</strong> tips with a headline win rate of <strong>' . bao_h((string) $winRate) . '%</strong>';
} else {
  echo ', settled performance figures update on Results as fixtures finish';
}
?>. That sample excludes incomplete model rows and postponements.</p>
<p>Plenty of tip brands stay anonymous and delete losers. Bao’s difference is the named desk plus the audit trail: Today for the live board, Yesterday for one matchday, Results for the rolling week and longer track strip.</p>
<p>Partnerships and corrections go through <a href="/contact-us">Contact</a>. <strong>18+ | Gamble responsibly.</strong></p>
  </article>
</div>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">About FAQ</h2>
    <ul class="faq-list">
<?php foreach ($baoAboutFaqs as $item): ?>
      <li><details><summary><?php echo bao_h($item['q']); ?></summary><p><?php echo bao_h($item['a']); ?></p></details></li>
<?php endforeach; ?>
    </ul>
  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_faq_schema($baoAboutFaqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'About', 'url' => '/about-us'],
]);
echo bao_article_schema(
  'About Bao Predictions',
  'About Bao Predictions — Kenya-facing football tips and jackpot sheets with model leans, human review, and a public wins-and-losses record.',
  '/about-us'
);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
