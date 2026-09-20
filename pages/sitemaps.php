<?php
/**
 * Human-readable HTML sitemap — button-grid directory (crawler XML remains /sitemap.xml).
 */
require_once __DIR__ . '/../components/seo.php';

$brandLandings = require __DIR__ . '/../config/brand-landings.php';
if (!is_array($brandLandings)) {
  $brandLandings = [];
}

$existingBrands = [
  ['label' => 'Bet Numbers Tips', 'href' => '/betnumbers-tips'],
  ['label' => 'SokaFans Predictions', 'href' => '/sokafans-predictions'],
  ['label' => 'Cheerplex Predictions', 'href' => '/cheerplex-tips'],
  ['label' => 'Sunpel Prediction', 'href' => '/sunpel-prediction'],
  ['label' => 'VenasBet Predictions', 'href' => '/venasbet-predictions'],
];
foreach ($brandLandings as $slug => $meta) {
  if (!is_array($meta)) {
    continue;
  }
  $existingBrands[] = [
    'label' => (string) ($meta['breadcrumb'] ?? $meta['brand'] ?? $slug),
    'href' => '/' . $slug,
  ];
}
usort($existingBrands, static function ($a, $b) {
  return strcasecmp((string) $a['label'], (string) $b['label']);
});

$sections = [
  [
    'id' => 'quick-links',
    'title' => 'Quick Links',
    'links' => array_merge(
      [
        ['label' => 'Football Predictions Today', 'href' => '/football-predictions-today'],
        ['label' => 'Football Predictions Tomorrow', 'href' => '/football-predictions-tomorrow'],
        ['label' => 'Football Predictions Yesterday', 'href' => '/football-predictions-yesterday'],
        ['label' => 'Weekend Football Predictions', 'href' => '/weekend-football-predictions'],
        ['label' => 'Live Football Predictions', 'href' => '/live-football-predictions'],
        ['label' => 'Must Win Teams Today', 'href' => '/must-win-teams-today'],
        ['label' => 'Sure Bets Today', 'href' => '/sure-bets-today'],
        ['label' => 'Banker of the Day', 'href' => '/banker-of-the-day'],
        ['label' => 'Accumulator Tips', 'href' => '/accumulator-tips'],
        ['label' => '1X2 Predictions', 'href' => '/1x2-predictions'],
        ['label' => 'Double Chance Predictions', 'href' => '/double-chance-predictions'],
        ['label' => 'Over/Under Predictions', 'href' => '/over-under-predictions'],
        ['label' => 'BTTS Predictions', 'href' => '/btts-predictions'],
        ['label' => 'HT/FT Predictions', 'href' => '/ht-ft-predictions'],
        ['label' => 'Jackpot Predictions', 'href' => '/jackpot-predictions'],
        ['label' => 'Results', 'href' => '/results'],
        ['label' => 'How We Predict', 'href' => '/how-we-predict'],
        ['label' => 'Mega Jackpot Strategy Guide', 'href' => '/mega-jackpot-strategy-guide'],
        ['label' => 'How to Read BTTS Odds', 'href' => '/how-to-read-btts-odds'],
      ],
      $existingBrands
    ),
  ],
  [
    'id' => 'free-predictions',
    'title' => 'Free Predictions',
    'links' => $existingBrands,
  ],
  [
    'id' => 'jackpots',
    'title' => 'Jackpots',
    'links' => [
      ['label' => 'Jackpot Predictions Hub', 'href' => '/jackpot-predictions'],
      ['label' => 'SportPesa Mega Jackpot Predictions', 'href' => '/jackpots/sportpesa-mega-jackpot-predictions'],
      ['label' => 'SportPesa Midweek Jackpot Predictions', 'href' => '/jackpots/sportpesa-midweek-jackpot-predictions'],
      ['label' => 'Betika Midweek Jackpot Predictions', 'href' => '/jackpots/betika-midweek-jackpot-predictions'],
      ['label' => 'SportyBet Daily Jackpot Predictions', 'href' => '/jackpots/sportybet-daily-jackpot-predictions'],
      ['label' => 'Odibets Laki Tatu Predictions', 'href' => '/jackpots/odibets-laki-tatu-predictions'],
      ['label' => 'Mozzart Super Daily Jackpot Predictions', 'href' => '/jackpots/mozzart-super-daily-jackpot-predictions'],
      ['label' => 'Mega Jackpot Strategy Guide', 'href' => '/mega-jackpot-strategy-guide'],
    ],
  ],
  [
    'id' => 'site',
    'title' => 'Site',
    'links' => [
      ['label' => 'About Us', 'href' => '/about-us'],
      ['label' => 'FAQ', 'href' => '/faq'],
      ['label' => 'Blog', 'href' => '/blog'],
      ['label' => 'Partners', 'href' => '/partners'],
      ['label' => 'Contact Us', 'href' => '/contact-us'],
      ['label' => 'Responsible Betting', 'href' => '/responsible-betting'],
      ['label' => 'Privacy Policy', 'href' => '/privacy-policy'],
      ['label' => 'Terms of Service', 'href' => '/terms-of-service'],
      ['label' => 'XML Sitemap', 'href' => '/sitemap.xml'],
      ['label' => 'llms.txt', 'href' => '/llms.txt'],
    ],
  ],
];

$linkCount = 0;
foreach ($sections as $section) {
  $linkCount += count($section['links']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sitemaps — All Bao Predictions Pages</title>
  <meta name="description" content="Browse every Bao Predictions page: daily tip boards, markets, Kenya jackpots, brand prediction pages, guides and legal links. XML sitemap for crawlers included.">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://www.baopredictions.com/sitemaps">

  <meta name="keywords" content="bao predictions sitemap, football predictions links, jackpot predictions index">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Sitemaps — All Bao Predictions Pages">
  <meta name="twitter:description" content="Browse every Bao Predictions page: tip boards, markets, jackpots, brand pages and guides.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/sitemaps">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/sitemaps">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Sitemaps — All Bao Predictions Pages">
  <meta property="og:description" content="Browse every Bao Predictions page: tip boards, markets, jackpots, brand pages and guides.">
  <meta property="og:url" content="https://www.baopredictions.com/sitemaps">
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
    <li><span aria-current="page">Sitemaps</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>Bao Predictions Links</h1>
    <p class="lede">Browse tip boards, markets, jackpots and brand pages. Crawlers can also use <a href="/sitemap.xml">sitemap.xml</a>.</p>
  </header>

  <p class="sitemaps-meta"><?php echo (int) $linkCount; ?> links</p>

<?php foreach ($sections as $section): ?>
  <section class="sitemaps-panel" aria-labelledby="sitemaps-<?php echo bao_h($section['id']); ?>">
    <header class="sitemaps-panel-head">
      <h2 id="sitemaps-<?php echo bao_h($section['id']); ?>"><?php echo bao_h($section['title']); ?></h2>
    </header>
    <ul class="sitemaps-grid">
<?php foreach ($section['links'] as $link): ?>
      <li><a href="<?php echo bao_h($link['href']); ?>"><?php echo bao_h($link['label']); ?></a></li>
<?php endforeach; ?>
    </ul>
  </section>
<?php endforeach; ?>

</div>
</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Sitemaps', 'url' => '/sitemaps'],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
