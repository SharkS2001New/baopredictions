<?php
/**
 * Brand tip-board directory — linked from the header, footer and homepage
 * so crawlers reach each brand page from the site, not only from sitemap.xml.
 */
require_once __DIR__ . '/../components/seo.php';

$brandLandings = require __DIR__ . '/../config/brand-landings.php';
if (!is_array($brandLandings)) {
    $brandLandings = [];
}

$links = [
    ['label' => 'Bet Numbers Tips', 'href' => '/betnumbers-tips'],
    ['label' => 'SokaFans Predictions', 'href' => '/sokafans-predictions'],
    ['label' => 'Cheerplex Predictions', 'href' => '/cheerplex-tips'],
    ['label' => 'Sunpel Prediction', 'href' => '/sunpel-prediction'],
    ['label' => 'VenasBet Predictions', 'href' => '/venasbet-predictions'],
];

foreach ($brandLandings as $slug => $meta) {
    if (!is_string($slug) || $slug === '' || !is_array($meta)) {
        continue;
    }
    $links[] = [
        'label' => (string) ($meta['breadcrumb'] ?? $meta['brand'] ?? $slug),
        'href' => '/' . $slug,
    ];
}

$seen = [];
$unique = [];
foreach ($links as $link) {
    $href = strtolower(rtrim((string) $link['href'], '/'));
    if ($href === '' || isset($seen[$href])) {
        continue;
    }
    $seen[$href] = true;
    $unique[] = $link;
}
usort($unique, static function ($a, $b) {
    return strcasecmp((string) $a['label'], (string) $b['label']);
});
$links = $unique;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Football Prediction Sites — Bao Predictions Directory</title>
  <meta name="description" content="Browse Bao Predictions pages for popular prediction-site searches, including Forebet, BetClan, Soccer24, SportyTrader and the other brand tip boards.">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://www.baopredictions.com/prediction-sites">

  <meta name="keywords" content="football prediction sites, forebet predictions, betclan predictions, soccer24 predictions, bao predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Football Prediction Sites — Bao Predictions Directory">
  <meta name="twitter:description" content="Browse Bao Predictions brand tip boards for popular prediction-site searches.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/prediction-sites">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/prediction-sites">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Football Prediction Sites — Bao Predictions Directory">
  <meta property="og:description" content="Browse Bao Predictions brand tip boards for popular prediction-site searches.">
  <meta property="og:url" content="https://www.baopredictions.com/prediction-sites">
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
<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Prediction Sites</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>Prediction site searches</h1>
    <p class="lede">Landing pages for common tip-site keywords. Each board is a Bao Predictions page with its own fixtures and match notes.</p>
  </header>

  <p class="sitemaps-meta"><?php echo count($links); ?> sites</p>

  <section class="sitemaps-panel" aria-labelledby="prediction-sites-list">
    <header class="sitemaps-panel-head">
      <h2 id="prediction-sites-list">Brand tip boards</h2>
    </header>
    <ul class="sitemaps-grid">
<?php foreach ($links as $link): ?>
      <li><a href="<?php echo bao_h($link['href']); ?>"><?php echo bao_h($link['label']); ?></a></li>
<?php endforeach; ?>
    </ul>
  </section>

</div>
</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Prediction Sites', 'url' => '/prediction-sites'],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
