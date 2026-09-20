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
$faqTitle = $brand . ' FAQ';
$sections = is_array($meta['sections'] ?? null) ? $meta['sections'] : [];

require_once __DIR__ . '/../components/api-curl.php';
$payload = bao_curl_api('/api/' . $slug);
$games = (is_array($payload) && !empty($payload['games']) && is_array($payload['games']))
  ? $payload['games']
  : [];
$todayLabel = date('j F Y');
$tipCount = count($games);

$faqs = [
  [
    'q' => 'Are ' . $brand . ' tips free here?',
    'a' => 'Yes. This tip board on Bao Predictions is free to view. There is no VIP paywall on the cards above.',
  ],
  [
    'q' => 'Which markets appear on this board?',
    'a' => '1X2, Double Chance, BTTS, Over/Under and HT/FT — one recommended market per fixture, with a short reason on the card.',
  ],
  [
    'q' => 'How do I know the tips are still current?',
    'a' => 'Check the last-updated timestamp at the top of this page and the kickoff on each card. Team news can change a lean after first publish — re-check before you stake.',
  ],
  [
    'q' => 'Do you guarantee wins?',
    'a' => 'No. Confidence figures are model leans with a publish cap, not promised win rates. Stake only what you can afford to lose.',
  ],
];
if (array_intersect($sections, ['jackpot', 'mega_jackpot', 'sportpesa_mega', 'midweek_jackpot'])) {
  array_splice($faqs, 2, 0, [[
    'q' => 'Is this a full jackpot coupon?',
    'a' => 'No. This page is the daily tip board. Open the matching jackpot sheet from the sections above or the Jackpot Predictions hub, then confirm the live operator card before you play.',
  ]]);
}
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
<?php
if ($intro !== '') {
  echo '<p class="lede">' . $intro;
  if ($tipCount > 0) {
    echo ' <strong>' . (int) $tipCount . ' tips</strong> are on this page for <strong>' . bao_h($todayLabel) . '</strong>.';
  }
  echo '</p>';
} else {
  echo '<p class="lede"><strong>' . bao_h($brand) . '</strong> tips on this page for <strong>' . bao_h($todayLabel) . '</strong>';
  if ($tipCount > 0) {
    echo ' — <strong>' . (int) $tipCount . ' selections</strong>';
  }
  echo '. Each card shows one recommended market with a short reason.</p>';
}
?>
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
echo bao_results_bridge_html();
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
<?php
$seoPartial = __DIR__ . '/brand-seo/' . $slug . '.php';
if (is_file($seoPartial)) {
  include $seoPartial;
} else {
  echo '<p>Tips for ' . bao_h($brand) . ' are on the board above.</p>';
}
?>
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
