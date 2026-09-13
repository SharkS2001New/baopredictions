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
    'a' => 'Yes. Tip boards, jackpot sheets, Yesterday, Results, and the reasoning on each card are free to view. Bao Predictions does not take stakes or sell access to picks.

You can read every published lean, check settled outcomes, and follow jackpot sheets without creating an account. Partnership enquiries and corrections go through Contact — not a paywall.',
  ],
  [
    'q' => 'Are tips guaranteed or “sure wins”?',
    'a' => 'No. A model lean describes how strongly the available data points toward a selection — it is not a promised hit rate and never a guarantee.

Cards are hard-capped at 85% confidence and never show 100%. Even leans in the Must Win (75%+) or Sure Bets (78%+) bands can lose. Football stays unpredictable; the honest check is Results and Yesterday, where losses stay visible.',
  ],
  [
    'q' => 'What does the confidence percentage mean?',
    'a' => 'It is a capped model lean for publishing and filtering — not a predicted win rate. Stephen Karuku, Lead Analyst, reviews model output before anything goes live.

Publish floors: below 55% a fixture usually stays off tip boards. Must Win Teams Today is high-confidence 1X2 (roughly 75%+). Sure Bets Today is the strongest mixed-market band (roughly 78%+). The ceiling is 85%; we never display 100%.',
  ],
  [
    'q' => 'How do you build a prediction?',
    'a' => 'Each card starts with model output, then a human check against form, home/away splits, league position, head-to-head where still relevant, team news, and match context before publish.

Recent form is weighted more when it comes from the same competition and venue. H2H is supporting evidence, not a standalone rule. Team news can flip a lean after first publish — that is why last-updated times matter. Full methodology is on How We Predict.',
  ],
  [
    'q' => 'What is the difference between Today, Tomorrow, Yesterday and Results?',
    'a' => 'Today is the main daily board for fixtures on the current matchday. Tomorrow is a provisional early board that can move after lineups and team news.

Yesterday is the previous matchday audit — wins and losses kept on the same cards. Results is the rolling last seven days of settled tips plus the headline track-record strip. They answer different questions: live picks vs one day vs a week vs long-run sample.',
  ],
  [
    'q' => 'What are Livescores?',
    'a' => 'The live board for matches already underway — current score and minute first, then any still-relevant tip. It is not a second copy of the pre-match Today page.

Use Livescores to follow in-play fixtures. Settled outcomes and performance auditing belong on Results and Yesterday once the final whistle passes.',
  ],
  [
    'q' => 'Which jackpots do you cover?',
    'a' => 'SportPesa Mega Jackpot, SportPesa Midweek Jackpot, Betika Midweek Jackpot, SportyBet Daily, Odibets Laki Tatu, and Mozzart Super Daily Jackpot — each with per-game reasoning on its own sheet.

Confirm live game count, stake, deadline and bonus rules on the operator before you play. Bao names products exactly as listed; we do not promise jackpot hits.',
  ],
  [
    'q' => 'How often are tips updated?',
    'a' => 'Daily boards refresh as fixtures and team news change. Tomorrow tips are provisional until closer to kickoff. Jackpot sheets follow each operator’s round.

Livescores reloads about every 90 seconds while matches are in play. Check the last-updated line on each page before treating an older card as current.',
  ],
  [
    'q' => 'Where can I see if tips won or lost?',
    'a' => 'Football Predictions Yesterday for one matchday; Football Results for the seven-day settled list and headline track-record figures. Losses stay published.

Headline win-rate figures update on Results as the qualifying sample grows.',
  ],
  [
    'q' => 'Do you encourage people to start betting?',
    'a' => 'No. Bao publishes analysis for adults who already choose to bet with licensed operators. We are not a bookmaker and do not take stakes.

Read Responsible Betting first. Tips are informational opinions — not financial advice. 18+ only (or legal age where you live). Never stake money you cannot afford to lose.',
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
<p class="lede">Direct answers on free tips, model leans, board roles, jackpots, and how Yesterday differs from Results — with the methodology detail most tip-site FAQs skip.</p>
  </header>

  <article class="prose">
<p>This FAQ goes beyond “tips are free” and “bet responsibly.” It explains what confidence figures actually mean (model lean, not win rate), how Today, Tomorrow, Yesterday and Results differ, which Kenyan jackpots Bao covers by exact product name, and why Stephen Karuku reviews cards before publish.</p>

<h2>Using the site</h2>
<?php echo bao_faq_items_html(array_slice($baoFaqs, 0, 4)); ?>

<h2>Boards, livescores and jackpots</h2>
<?php echo bao_faq_items_html(array_slice($baoFaqs, 4, 4)); ?>

<h2>Track record and responsible use</h2>
<?php echo bao_faq_items_html(array_slice($baoFaqs, 8)); ?>

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
<p>Each answer below states the direct takeaway first, then the checkable detail — publish floors, board roles, and where to audit wins and losses — so you can use the site without treating any lean as a sure win.</p>
<p class="seo-related"><strong>Related:</strong> <a href="/how-we-predict">How We Predict</a> · <a href="/results">Football Results</a> · <a href="/responsible-betting">Responsible Betting</a> · <a href="/contact-us">Contact</a></p>
<p><strong>18+ | Gamble responsibly.</strong></p>
  </article>
</div>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
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
