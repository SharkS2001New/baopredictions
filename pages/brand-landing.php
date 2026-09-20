<?php
/**
 * Shared brand-comparison tip board.
 * Expects $bao_brand_slug (URL/api key) set by the router.
 */
require_once __DIR__ . '/../components/seo.php';

$brands = require __DIR__ . '/../config/brand-landings.php';
$slug = isset($bao_brand_slug) ? (string) $bao_brand_slug : '';
$meta = is_array($brands[$slug] ?? null) ? $brands[$slug] : null;
if ($meta === null) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    return;
}

$brand = (string) $meta['brand'];
$title = (string) $meta['title'];
$description = (string) $meta['description'];
$keywords = (string) $meta['keywords'];
$h1 = (string) $meta['h1'];
$intro = trim((string) ($meta['intro'] ?? $meta['lede'] ?? ''));
$introLinks = trim((string) ($meta['intro_links'] ?? ''));
$breadcrumb = (string) $meta['breadcrumb'];
$canonical = 'https://www.baopredictions.com/' . $slug;
$faqTitle = $brand . ' Predictions FAQ';

$faqs = [
  [
    'q' => 'Are ' . $brand . ' predictions free here?',
    'a' => 'Yes. Every tip board and jackpot sheet on Bao Predictions is free to view. There is no VIP paywall on this page.',
  ],
  [
    'q' => 'Which markets appear on this board?',
    'a' => 'The same mixed-market engine as Bet Numbers Tips: 1X2, BTTS, Over/Under 2.5 and Double Chance — one recommended market per fixture.',
  ],
  [
    'q' => 'Where are jackpot sheets?',
    'a' => 'Use the Jackpot Predictions hub for SportPesa Mega, Midweek, Betika, SportyBet, Odibets Laki Tatu and Mozzart Super Daily — each match is analysed separately.',
  ],
  [
    'q' => 'Do you guarantee wins?',
    'a' => 'No. Football predictions are opinions based on available match data, not guaranteed outcomes. Stake only what you can afford to lose.',
  ],
];

require_once __DIR__ . '/../components/api-curl.php';
$payload = bao_curl_api('/api/' . $slug);
$games = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
  ? $payload['games']
  : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo bao_h($title); ?></title>
  <meta name="description" content="<?php echo bao_h($description); ?>">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="<?php echo bao_h($canonical); ?>">

  <meta name="keywords" content="<?php echo bao_h($keywords); ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo bao_h($title); ?>">
  <meta name="twitter:description" content="<?php echo bao_h($description); ?>">
  <link rel="alternate" hreflang="en" href="<?php echo bao_h($canonical); ?>">
  <link rel="alternate" hreflang="x-default" href="<?php echo bao_h($canonical); ?>">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo bao_h($title); ?>">
  <meta property="og:description" content="<?php echo bao_h($description); ?>">
  <meta property="og:url" content="<?php echo bao_h($canonical); ?>">
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

<div class="wrap wrap-wide">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page"><?php echo bao_h($breadcrumb); ?></span></li>
  </ol>
</nav>

<header class="page-hero page-hero--full">
    <h1><?php echo bao_h($h1); ?></h1>
<?php echo bao_board_freshness_html(is_array($payload) ? $payload : null); ?>
<?php if ($intro !== ''): ?>
    <p class="lede"><?php echo $intro; ?></p>
<?php endif; ?>
<?php echo bao_intro_links_html($introLinks !== '' ? $introLinks : null); ?>
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
  echo bao_matches_html($games, ['page' => $slug]);
}
?>

  </div><!-- /.matches-area -->
<?php
$bao_sidebar_active = $slug;
require __DIR__ . '/../components/sidebar.php';
?>

</div><!-- /.main-grid -->
</div>
</section>

<section class="section section-muted bao-seo-stack">
  <div class="wrap prose">
    <h2><?php echo bao_h($brand); ?> Predictions</h2>
    <p>Looking for <strong><?php echo bao_h($brand); ?></strong> football predictions and tips? Bao Predictions covers daily football selections, jackpot fixtures and popular betting markets including 1X2, Double Chance, BTTS, Over/Under and Half Time/Full Time. Check the available match information and compare the selections before placing a bet.</p>

    <h2><?php echo bao_h($brand); ?> Prediction</h2>
    <p>A <strong><?php echo bao_h($brand); ?> prediction</strong> gives you a football selection for an individual match or a group of fixtures. Depending on the match, the prediction may cover 1X2, Double Chance, BTTS, Over/Under or Half Time/Full Time.</p>
    <p>When comparing predictions, look at the actual fixture as well as the selected market. A strong-looking team on paper does not automatically make every betting market suitable, particularly when the prediction is based on goals, both teams to score or a double-chance outcome.</p>

    <h2><?php echo bao_h($brand); ?> Prediction Today</h2>
    <p>For <strong><?php echo bao_h($brand); ?> prediction today</strong>, check the latest available football fixtures and selections for the current day's matches. Today's predictions can change as fixtures, team information and available markets are updated, so it is worth checking the latest version before making a selection.</p>
    <p>The daily list can include matches from different competitions, giving you the option to review individual predictions rather than relying on one overall tip. Always check the fixture time and market before placing a bet.</p>

<?php echo bao_brand_jackpot_sections_html($brand); ?>

    <p><strong>18+ only. Gamble responsibly.</strong> Football predictions are opinions, not guaranteed outcomes. See <a href="/responsible-betting">Responsible Betting</a>.</p>
    <p class="seo-related"><strong>Related:</strong> <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/football-predictions-yesterday">Yesterday</a> · <a href="/results">Results</a> · <a href="/jackpot-predictions">Jackpot Predictions</a> · <a href="/betnumbers-tips">Bet Numbers Tips</a></p>
  </div>
</section>


<section class="section section-tight bao-analyst-wrap">
  <div class="wrap">
    <?php echo bao_analyst_card_html(); ?>
  </div>
</section>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title"><?php echo bao_h($faqTitle); ?></h2>
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
  ['name' => $breadcrumb, 'url' => '/' . $slug],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
