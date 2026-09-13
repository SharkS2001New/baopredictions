<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Link Exchange Partners | Bao Predictions</title>
  <meta name="description" content="Partner with Bao Predictions for a free editorial link exchange — football and sports sites only. Apply via hello@baopredictions.com.">
  <link rel="canonical" href="https://www.baopredictions.com/partners">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="Link Exchange Partners | Bao Predictions">
  <meta name="keywords" content="bao predictions partners, link exchange football, backlink exchange kenya, football prediction partners">
  <meta name="author" content="Stephen Karuku">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Stephen Karuku">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Link Exchange Partners | Bao Predictions">
  <meta name="twitter:description" content="Partner with Bao Predictions for a free editorial link exchange — football and sports sites only. Apply via hello@baopredictions.com.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/partners">
  <!--BAO_HEAD_EXTRA_END-->

  <meta property="og:title" content="Link Exchange Partners | Bao Predictions">
  <meta property="og:description" content="Partner with Bao Predictions for a free editorial link exchange — football and sports sites only. Apply via hello@baopredictions.com.">
  <meta property="og:url" content="https://www.baopredictions.com/partners">
  <meta property="og:type" content="website">
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
$partners = require __DIR__ . '/../config/partners.php';
if (!is_array($partners)) {
  $partners = [];
}
$partners = array_values(array_filter($partners, static function ($p) {
  return is_array($p) && !empty($p['name']) && !empty($p['url']);
}));
$partnerCount = count($partners);
$updatedIso = date('c');
$updatedDate = date('j F Y');
$mailto = 'mailto:hello@baopredictions.com?subject=' . rawurlencode('Link Exchange Request');

?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Partners</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <p class="partners-eyebrow">Free backlink exchange</p>
    <h1>Link Exchange Partners</h1>
<?php echo bao_last_updated_html($updatedIso); ?>
<p class="lede">Bao Predictions partners with relevant football, sports and betting-content sites for mutual editorial backlinks — no fee, no paid placement packages.</p>
  </header>

  <ul class="partners-stats" aria-label="Programme highlights">
    <li><strong>Free</strong><span>No cost ever</span></li>
    <li><strong>48h</strong><span>Review target</span></li>
    <li><strong>Dofollow</strong><span>Both directions</span></li>
    <li><strong>Editorial</strong><span>Contextual only</span></li>
  </ul>

  <section class="partners-panel" aria-labelledby="partners-current-title">
    <header class="partners-panel-head">
      <h2 id="partners-current-title">Current link partners</h2>
      <p><?php echo (int) $partnerCount; ?> partner<?php echo $partnerCount === 1 ? '' : 's'; ?> · Last updated <?php echo bao_h($updatedDate); ?></p>
    </header>
<?php if ($partnerCount === 0): ?>
    <p class="partners-empty">Applications are open. Approved partners will be listed here with their site name and destination URL.</p>
<?php else: ?>
    <ul class="partners-list">
<?php foreach ($partners as $p):
  $name = (string) $p['name'];
  $url = (string) $p['url'];
  $note = trim((string) ($p['note'] ?? ''));
  $host = parse_url($url, PHP_URL_HOST);
  $hostLabel = is_string($host) && $host !== '' ? preg_replace('/^www\./i', '', $host) : $url;
?>
      <li>
        <a href="<?php echo bao_h($url); ?>" rel="noopener noreferrer" target="_blank"><?php echo bao_h($name); ?></a>
<?php if ($note !== ''): ?>
        <span><?php echo bao_h($note); ?></span>
<?php else: ?>
        <span><?php echo bao_h((string) $hostLabel); ?></span>
<?php endif; ?>
      </li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>
  </section>

  <section class="partners-apply" aria-labelledby="partners-apply-title">
    <h2 id="partners-apply-title">Apply for a link exchange</h2>
    <ol class="partners-steps">
      <li>
        <span class="partners-step-num" aria-hidden="true">01</span>
        <h3>Send your site details</h3>
        <p>Email <a href="<?php echo bao_h($mailto); ?>">hello@baopredictions.com</a> with subject <strong>Link Exchange Request</strong>. Include your URL, niche, domain rating if you have it, the page where you would place our link, and proposed anchor text.</p>
        <p>You can also use <a href="/contact-us">Contact</a> and choose <strong>Partnership enquiry</strong>.</p>
      </li>
      <li>
        <span class="partners-step-num" aria-hidden="true">02</span>
        <h3>We review your site</h3>
        <p>Lead Analyst <a href="/about-us#stephen-karuku">Stephen Karuku</a> reviews content quality, football/sports relevance, and backlink health. We aim to reply within 48 hours — approved or not.</p>
      </li>
      <li>
        <span class="partners-step-num" aria-hidden="true">03</span>
        <h3>Both links go live</h3>
        <p>We confirm pages and anchors first. Bao publishes the dofollow link to you, then verifies your link to Bao Predictions is live and dofollow. Once both sides check out, the exchange is complete.</p>
      </li>
    </ol>
    <p class="partners-cta-row">
      <a class="btn btn-primary" href="<?php echo bao_h($mailto); ?>">Email link exchange request</a>
      <a class="btn btn-outline" href="/contact-us">Partnership contact form</a>
    </p>
  </section>

  <article class="prose">
    <h2>Who we partner with</h2>
    <p>We look for original editorial content, a clear football / sports / betting-content connection, and a clean backlink profile. Welcome: football prediction sites, jackpot tip platforms, licensed sportsbook blogs, football news and stats sites, and fantasy football tools.</p>
    <p>We decline unlicensed gambling operators, link farms, unrelated niches, penalised domains, and adult or harmful content. Exchanges are editorial and monitored — contact us before removing or moving an agreed link.</p>
    <p><strong>Link exchange policy:</strong> Bao Predictions does not sell homepage footer spam packs. Placements are contextual and reviewed. <strong>18+ | Gamble responsibly.</strong></p>
  </article>
</div>

<?php
$faqs = [
  [
    'q' => 'Is the link exchange free?',
    'a' => 'Yes. Bao Predictions does not charge for editorial link exchanges. Both sides place dofollow links at no cost — no paid placement packages.

Exchanges are contextual and reviewed by Stephen Karuku, Lead Analyst, before approval.',
  ],
  [
    'q' => 'Will the links be dofollow?',
    'a' => 'Yes. Approved exchanges use editorial dofollow links. We expect the same on your side and verify before confirming the exchange complete.

Homepage footer spam packs are not part of this programme.',
  ],
  [
    'q' => 'How do I apply?',
    'a' => 'Email hello@baopredictions.com with subject “Link Exchange Request”, or use Contact with Partnership enquiry. Include your URL, niche, proposed page, and anchor text.

We aim to reply within 48 hours — approved or declined with a reason.',
  ],
  [
    'q' => 'What if one side removes the link?',
    'a' => 'We audit active exchanges periodically. Contact us before removing or moving an agreed link.

If a partner link disappears without notice, we remove ours after attempting to resolve it.',
  ],
  [
    'q' => 'Do partners influence picks?',
    'a' => 'No. Published leans follow the same methodology and publish floors (55% minimum, 85% cap) whether or not a partner link appears on site.

We decline unlicensed gambling operators, link farms, and unrelated niches.',
  ],
  [
    'q' => 'Can we republish Bao tips?',
    'a' => 'Ask first via Contact with scope and attribution plan. Unauthorised scraping or win-only rebrand of our cards is not permitted.

Quote track figures from Results — model leans are not the same as historical win rate.',
  ],
];
?>

<section class="section section-tight bao-faq">
  <div class="wrap">
    <h2 class="section-title">Link exchange FAQ</h2>
    <?php echo bao_faq_items_html($faqs); ?>

  </div>
</section>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_faq_schema($faqs);
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Partners', 'url' => '/partners'],
]);
echo bao_article_schema(
  'Link Exchange Partners',
  'Partner with Bao Predictions for a free editorial link exchange — football and sports sites only. Apply via hello@baopredictions.com.',
  '/partners'
);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
