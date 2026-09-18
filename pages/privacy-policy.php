<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Privacy Policy | Bao Predictions</title>
  <meta name="description" content="Privacy policy for Bao Predictions — how we handle site data, cookies and contact form information.">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://www.baopredictions.com/privacy-policy">

  <meta name="keywords" content="bao predictions privacy policy">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Privacy Policy | Bao Predictions">
  <meta name="twitter:description" content="Privacy policy for Bao Predictions — site data, cookies and contact form information.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/privacy-policy">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/privacy-policy">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Privacy Policy | Bao Predictions">
  <meta property="og:description" content="Privacy policy for Bao Predictions — site data, cookies and contact form information.">
  <meta property="og:url" content="https://www.baopredictions.com/privacy-policy">
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
$updatedIso = date('c');
$updatedDate = date('j F Y');
?>

<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Privacy Policy</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>Privacy Policy</h1>
<?php echo bao_last_updated_html($updatedIso); ?>
<p class="lede">What Bao Predictions collects when you browse or contact us — and what we do not do with that information.</p>
  </header>

  <article class="prose">
<p>Bao Predictions is a football tips and jackpot analysis site. We do not run user accounts or betting wallets. This policy explains the limited personal data we handle when you visit the site or send a message through <a href="/contact-us">Contact</a>.</p>

<h2>What we collect</h2>
<ul>
  <li><strong>Contact form</strong> — name, email address, subject, and message, so we can reply to corrections, partnerships, or other enquiries. We also apply basic anti-spam checks (including rate limits tied to IP).</li>
  <li><strong>Theme preference</strong> — light/dark mode stored in your browser via <code>localStorage</code> so the site remembers your choice. That value stays on your device.</li>
  <li><strong>Hosting and analytics</strong> — our host or analytics tools may log technical data such as pages viewed, approximate region, browser type, and IP address used to deliver and secure the site.</li>
</ul>
<p>We do not sell personal data. We do not ask for payment-card details on Bao Predictions, because we do not take stakes.</p>

<h2>How long we keep it and how to reach us</h2>
<p>Contact messages are kept long enough to handle the request and any follow-up, then discarded when they are no longer needed for that purpose. Server and analytics logs are retained according to our host's normal security and operational practice.</p>
<p>To ask about data we may hold from a contact submission, email <a href="mailto:hello@baopredictions.com">hello@baopredictions.com</a> or use the contact form. If we add accounts or other personal features later, we will update this policy before those features launch.</p>
<p>This policy was last reviewed on <strong><?php echo bao_h($updatedDate); ?></strong>. Related pages: <a href="/terms-of-service">Terms of Service</a> (also our terms and conditions) and <a href="/responsible-betting">Responsible Betting</a>.</p>
<p>Many tip sites paste a generic privacy template that still talks about “accounts” and “betting balances.” Bao states the narrower reality: no wallets here, contact-form data when you write to us, and browser theme preference on your own device.</p>
  </article>
</div>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
<!--BAO_SCHEMA_START-->
<?php
echo bao_breadcrumb_schema([
  ['name' => 'Home', 'url' => '/'],
  ['name' => 'Privacy Policy', 'url' => '/privacy-policy'],
]);
echo bao_organization_schema();
?>
<!--BAO_SCHEMA_END-->
</body>
</html>
