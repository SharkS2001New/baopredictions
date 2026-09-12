<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>How We Predict | Bao Predictions Methodology</title>
  <meta name="description" content="How Bao Predictions builds football tips: form, home and away records, team news, model leans, publish floors, and a public results trail. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/how-we-predict">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="How We Predict | Bao Predictions Methodology">
  <meta name="keywords" content="how we predict, bao predictions methodology, football prediction model, confidence ratings explained">
  <meta name="author" content="Stephen Karuku">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Stephen Karuku">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="How We Predict | Bao Predictions Methodology">
  <meta name="twitter:description" content="How Bao Predictions builds football tips: form, home and away records, team news, model leans, publish floors, and a public results trail. 18+ only.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/how-we-predict">
  <!--BAO_HEAD_EXTRA_END-->

  <meta property="og:title" content="How We Predict | Bao Predictions Methodology">
  <meta property="og:description" content="How Bao Predictions builds football tips: form, home and away records, team news, model leans, publish floors, and a public results trail. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/how-we-predict">
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
$updatedTime = date('H:i', strtotime($updatedIso));
$track = is_array($stats) && is_array($stats['track'] ?? null) ? $stats['track'] : [];
$settledTips = (int) ($track['settled_tips'] ?? $stats['settled_tips'] ?? 0);
$winRate = $track['win_rate'] ?? ($stats['win_rate'] ?? null);
$predToday = is_array($stats) ? (int) ($stats['today']['predictions'] ?? 0) : 0;
?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">How We Predict</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>How We Predict</h1>
<?php echo bao_last_updated_html($updatedIso); ?>
<p class="lede">What goes into a Bao tip, what a model lean means, and how you can check the published record afterwards.</p>
  </header>

  <article class="prose">
<p>Bao Predictions builds every football tip from match data first, then a human check of team news before publish. We look at recent form (especially the last six matches where the data is reliable), home and away splits, league position, head-to-head where it still matters, and confirmed availability. The number on a card is a <strong>model lean</strong> — the strength of that day's published selection — not a win-rate promise and never a “sure win.”</p>

<h2>What we weigh before a tip goes live</h2>
<p>A useful lean needs more than a famous club name. We treat each fixture on its own evidence:</p>
<ul>
  <li><strong>Recent form</strong> — latest results, with more weight when they came in the same competition and venue as the upcoming match.</li>
  <li><strong>Home and away form</strong> — a side that dominates at home but leaks goals on the road is not the same tip in both settings.</li>
  <li><strong>League position and match context</strong> — title races, relegation scraps, European qualification, and “nothing left to play for” change how teams approach a game.</li>
  <li><strong>Head-to-head</strong> — supporting context only when current squads and setups still look comparable.</li>
  <li><strong>Team news</strong> — injuries, suspensions, and rotation risk, checked as late as the feed allows.</li>
  <li><strong>Market and odds</strong> — when our lean disagrees sharply with the book price, we review again rather than auto-flipping the tip without a football reason.</li>
</ul>
<p>Jackpot sheets (SportPesa Mega Jackpot, SportyBet Daily, Odibets Laki Tatu, Mozzart Super Daily Jackpot, and the others we cover) use the same per-game standard: 1X2 plus Double Chance where the fixture is narrow, with weaker legs labelled instead of dressed up as bankers.</p>

<h2>What the confidence number actually means</h2>
<p>Published cards use a capped scale. We never show 100%, because that would read as a guarantee.</p>
<ul>
  <li><strong>75–85%</strong> — strongest published leans (hard-capped at 85%).</li>
  <li><strong>60–74%</strong> — solid lean with clear reasoning, still not a lock.</li>
  <li><strong>55–59%</strong> — thinner edge; often better as an accumulator leg than a heavy single.</li>
  <li><strong>Below 55%</strong> — not published on tip boards. We leave the fixture off rather than invent a lean.</li>
</ul>
<p>Shortlists sit on top of that floor: <a href="/must-win-teams-today">Must Win Teams Today</a> is high-confidence 1X2 (roughly 75%+). <a href="/sure-bets-today">Sure Bets Today</a> is the strongest mixed-market band (roughly 78%+). Tomorrow's board is provisional — it can move after today's results and late lineups. Livescores is for matches already underway, not a second copy of the pre-match card.</p>

<h2>How you can check us after the match</h2>
<p>As of <strong><?php echo bao_h($updatedDate); ?> at <?php echo bao_h($updatedTime); ?> EAT</strong><?php
if ($predToday > 0) {
  echo ", today's boards carry <strong>" . (int) $predToday . "</strong> published predictions";
}
if ($settledTips > 0 && $winRate !== null) {
  echo ($predToday > 0 ? ', and ' : ', ') . 'the qualifying settled 1X2 sample stands at <strong>'
    . (int) $settledTips . '</strong> tips with a headline win rate of <strong>'
    . bao_h((string) $winRate) . '%</strong>';
}
?>. Those headline figures only count tips with a full model split and a book price; incomplete rows and postponements are excluded.</p>
<p>Most tip sites stop at “here are today's picks.” Bao keeps the full loop visible: <a href="/football-predictions-today">Football Predictions Today</a> for the live board, <a href="/football-predictions-yesterday">Football Predictions Yesterday</a> for the daily audit, and <a href="/results">Football Results</a> for the rolling seven-day settled list plus the longer track strip. Wins and losses both stay published.</p>
<p><strong>18+.</strong> Tips are informational opinions. Betting involves risk. Read <a href="/responsible-betting">Responsible Betting</a> before staking, and never bet money you cannot afford to lose.</p>
  </article>
</div>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">How We Predict FAQ</h2>
    <ul class="faq-list">
      <li><details><summary>Is confidence a win probability?</summary><p>No. It is a model lean for publishing and filtering — never a guaranteed hit rate. Cards never display above 85% or at 100%.</p></details></li>
      <li><details><summary>Do humans review every tip?</summary><p>Data starts the process; Lead Analyst Stephen Karuku checks team news before the lean is published.</p></details></li>
      <li><details><summary>Why are some matches missing?</summary><p>Below 55% we usually leave the fixture off rather than publish a thin guess.</p></details></li>
      <li><details><summary>Where can I see accuracy?</summary><p>Yesterday for one matchday; Results for the seven-day list and longer qualifying sample.</p></details></li>
    </ul>
  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php
$baoHowFaqs = [
  ['q' => 'Is confidence a win probability?', 'a' => 'No. It is a model lean for publishing and filtering — never a guaranteed hit rate. Cards never display above 85% or at 100%.'],
  ['q' => 'Do humans review every tip?', 'a' => 'Data starts the process; Lead Analyst Stephen Karuku checks team news before the lean is published.'],
  ['q' => 'Why are some matches missing?', 'a' => 'Below 55% we usually leave the fixture off rather than publish a thin guess.'],
  ['q' => 'Where can I see accuracy?', 'a' => 'Yesterday for one matchday; Results for the seven-day list and longer qualifying sample.'],
];
echo bao_faq_schema($baoHowFaqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'How We Predict', 'url' => '/how-we-predict'],
]);
echo bao_article_schema(
  'How We Predict',
  'How Bao Predictions builds football tips: form, home and away records, team news, model leans, publish floors, and a public results trail. 18+ only.',
  '/how-we-predict'
);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
