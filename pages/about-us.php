<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us | Bao Predictions</title>
  <meta name="description" content="About Bao Predictions — who we are, how we work, and why we publish our results transparently. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/about-us">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="About Us | Bao Predictions">
  <meta name="author" content="Bao Predictions Analysis Team">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Bao Predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="About Us | Bao Predictions">
  <meta name="twitter:description" content="About Bao Predictions — who we are, how we work, and why we publish our results transparently. 18+ only.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/about-us">
  <!--BAO_HEAD_EXTRA_END-->
  
  <meta property="og:title" content="About Us | Bao Predictions">
  <meta property="og:description" content="About Bao Predictions — who we are, how we work, and why we publish our results transparently. 18+ only.">
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
<div class="wrap">
  
  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">About Us</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>About Bao Predictions</h1>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_last_updated_html(); ?>
<p class="lede">Daily football predictions backed by statistical analysis and human review — with a public track record that includes losses.</p>
<p class="seo-related"><strong>Related:</strong> <a href="/how-we-predict">How we predict</a> · <a href="/faq">FAQ</a> · <a href="/contact-us">Contact</a></p>

</header>
  <article class="prose">
<p>Bao Predictions publishes daily football predictions backed by statistical analysis and human review. We built this site because most prediction sites either hide their losses or bury their reasoning behind vague confidence claims — we do neither. Every pick we publish stays visible whether it wins or loses, and every prediction includes the reasoning behind it, not just a result.</p>
<p>We cover single-match predictions across major betting markets, plus the football jackpots run by Kenya's major bookmakers, with the same standard applied throughout: real reasoning, honest track record, no guarantees.</p>
<p>If you have questions about how we work, our <a href="/how-we-predict">How We Predict</a> page has the full breakdown, and our <a href="/results">results page</a> has the numbers.</p>
</article>
</div>

  <section class="section"><div class="wrap"><h2 class="section-title">FAQ</h2><ul class="faq-list"><li><details><summary>Are you a bookmaker?</summary><p>No. We publish analysis only.</p></details></li><li><details><summary>Where are you focused?</summary><p>Kenya-facing bookmakers and readers, with major European leagues plus Kenyan Premier League coverage.</p></details></li><li><details><summary>How do I contact you?</summary><p>Use the Contact page for partnerships and corrections.</p></details></li></ul></div></section>
</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_faq_schema(array (
  0 => 
  array (
    'q' => 'Are you a bookmaker?',
    'a' => 'No. We publish analysis only.',
  ),
  1 => 
  array (
    'q' => 'Where are you focused?',
    'a' => 'Kenya-facing bookmakers and readers, with major European leagues plus Kenyan Premier League coverage.',
  ),
  2 => 
  array (
    'q' => 'How do I contact you?',
    'a' => 'Use the Contact page for partnerships and corrections.',
  ),
)); echo bao_breadcrumb_schema(array (
  0 => 
  array (
    'name' => 'Home',
    'url' => '/',
  ),
  1 => 
  array (
    'name' => 'About',
    'url' => '/about-us',
  ),
)); echo bao_article_schema('About Bao Predictions', 'About Bao Predictions — who we are, how we work, and why we publish our results transparently. 18+ only.', '/about-us'); echo bao_organization_schema(); ?>
<!--BAO_SCHEMA_END-->
</body>
</html>
