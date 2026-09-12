<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FAQ | Bao Predictions</title>
  <meta name="description" content="FAQ for Bao Predictions — free tips, model leans, jackpots, Yesterday vs Results, and responsible use. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/faq">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="FAQ | Bao Predictions">
  <meta name="keywords" content="bao predictions faq, football predictions faq, are tips free, confidence ratings explained">
  <meta name="author" content="Stephen Karuku">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Stephen Karuku">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="FAQ | Bao Predictions">
  <meta name="twitter:description" content="FAQ for Bao Predictions — free tips, model leans, jackpots, Yesterday vs Results, and responsible use. 18+ only.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/faq">
  <!--BAO_HEAD_EXTRA_END-->

  <meta property="og:title" content="FAQ | Bao Predictions">
  <meta property="og:description" content="FAQ for Bao Predictions — free tips, model leans, jackpots, Yesterday vs Results, and responsible use. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/faq">
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
$predToday = is_array($stats) ? (int) ($stats['today']['predictions'] ?? 0) : 0;

$baoFaqs = [
  [
    'q' => 'Is Bao Predictions free?',
    'a' => 'Yes. Tip boards, jackpot sheets, Yesterday, Results, and reasoning on each card are free to view. We do not take stakes.',
  ],
  [
    'q' => 'Are tips guaranteed or “sure wins”?',
    'a' => 'No. A model lean is the strength of a published selection, not a promised hit rate. Cards never show 100%, and even 75–85% leans can lose.',
  ],
  [
    'q' => 'What does the confidence percentage mean?',
    'a' => 'It is a capped model lean for publishing and filtering (hard-capped at 85%). Below 55% we usually leave the fixture off tip boards. Must Win Teams Today is high-confidence 1X2 (roughly 75%+); Sure Bets Today is the strongest mixed-market band (roughly 78%+).',
  ],
  [
    'q' => 'How do you build a prediction?',
    'a' => 'Recent form (including last-six context where reliable), home and away splits, league position, head-to-head where still relevant, team news, and match context — then a human check before publish. Full detail is on How We Predict.',
  ],
  [
    'q' => 'What is the difference between Today, Tomorrow, Yesterday and Results?',
    'a' => 'Today is the main daily board. Tomorrow is a provisional early board that can move after lineups. Yesterday is the previous matchday audit (wins and losses kept). Results is the rolling last seven days of settled tips plus the longer qualifying track strip.',
  ],
  [
    'q' => 'What are Livescores?',
    'a' => 'The live board for matches already underway — current score and minute first, then any still-relevant tip. It is not a second copy of the pre-match Today page. Settled outcomes belong on Results.',
  ],
  [
    'q' => 'Which jackpots do you cover?',
    'a' => 'SportPesa Mega Jackpot, SportPesa Midweek Jackpot, Betika Midweek Jackpot, SportyBet Daily, Odibets Laki Tatu, and Mozzart Super Daily Jackpot. Confirm live game count, stake and deadline on the operator before you play.',
  ],
  [
    'q' => 'How often are tips updated?',
    'a' => 'Daily boards refresh as fixtures and team news change. Tomorrow tips are provisional. Jackpot sheets follow each operator’s round. Livescores reloads about every 90 seconds while matches are in play.',
  ],
  [
    'q' => 'Where can I see if tips won or lost?',
    'a' => 'Football Predictions Yesterday for one matchday; Football Results for the seven-day settled list and headline track-record figures. Losses stay published.',
  ],
  [
    'q' => 'Do you encourage people to start betting?',
    'a' => 'No. We publish analysis for adults who already choose to bet with licensed operators. Read Responsible Betting first. 18+ only.',
  ],
];
?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">FAQ</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>Frequently Asked Questions</h1>
<?php echo bao_last_updated_html($updatedIso); ?>
<p class="lede">Short answers on free tips, model leans, jackpots, and how Yesterday differs from Results.</p>
  </header>

  <article class="prose">
<p>This FAQ covers the questions people ask most about Bao Predictions: whether tips are free, what a confidence figure means, how Today / Tomorrow / Yesterday / Results fit together, which Kenyan jackpots we cover, and how to use the site without treating any selection as a sure win.</p>

<h2>Using the site</h2>
<ul class="faq-list">
<?php foreach (array_slice($baoFaqs, 0, 4) as $item): ?>
  <li><details><summary><?php echo bao_h($item['q']); ?></summary><p><?php echo bao_h($item['a']); ?></p></details></li>
<?php endforeach; ?>
</ul>

<h2>Boards, livescores and jackpots</h2>
<ul class="faq-list">
<?php foreach (array_slice($baoFaqs, 4, 4) as $item): ?>
  <li><details><summary><?php echo bao_h($item['q']); ?></summary><p><?php echo bao_h($item['a']); ?></p></details></li>
<?php endforeach; ?>
</ul>

<h2>Track record and responsible use</h2>
<ul class="faq-list">
<?php foreach (array_slice($baoFaqs, 8) as $item): ?>
  <li><details><summary><?php echo bao_h($item['q']); ?></summary><p><?php echo bao_h($item['a']); ?></p></details></li>
<?php endforeach; ?>
</ul>

<p>As of <strong><?php echo bao_h($updatedDate); ?></strong><?php
if ($predToday > 0) {
  echo ", today's boards show <strong>" . (int) $predToday . '</strong> published predictions';
}
if ($settledTips > 0 && $winRate !== null) {
  echo ($predToday > 0 ? ', and ' : ', ') . 'the qualifying settled 1X2 sample is <strong>'
    . (int) $settledTips . '</strong> tips at a headline <strong>'
    . bao_h((string) $winRate) . '%</strong> win rate';
}
?>. Those figures are a published record, not a promise about the next card.</p>
<p>Generic FAQs often stop at “tips are free” and “bet responsibly.” This page also separates provisional Tomorrow tips from settled Yesterday audits, and Results from a single matchday — the same structure used across the tip boards themselves.</p>
<p class="seo-related"><strong>Related:</strong> <a href="/how-we-predict">How We Predict</a> · <a href="/results">Football Results</a> · <a href="/responsible-betting">Responsible Betting</a> · <a href="/contact-us">Contact</a></p>
<p><strong>18+ | Gamble responsibly.</strong></p>
  </article>
</div>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_faq_schema($baoFaqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'FAQ', 'url' => '/faq'],
]);
echo bao_article_schema(
  'Frequently Asked Questions',
  'FAQ for Bao Predictions — free tips, model leans, jackpots, Yesterday vs Results, and responsible use. 18+ only.',
  '/faq'
);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
