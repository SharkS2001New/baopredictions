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
$breadcrumb = (string) $meta['breadcrumb'];
$canonical = 'https://www.baopredictions.com/' . $slug;

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

<header class="page-hero page-hero--full page-hero--title-only">
    <h1><?php echo bao_h($h1); ?></h1>
  </header>

</div>

<section class="section-tight section-tight--flush-top">
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

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/timezone.js?v=20260913c" defer></script>
<script src="/assets/js/load-more.js?v=20260913c" defer></script>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => $breadcrumb, 'url' => '/' . $slug],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
