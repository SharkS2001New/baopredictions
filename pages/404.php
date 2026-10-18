<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page Not Found | Bao Predictions</title>
  <meta name="description" content="That page is not on Bao Predictions. Try today's football predictions, results, or the jackpot hub.">
  <meta name="robots" content="noindex, follow">

  <meta name="keywords" content="bao predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Page Not Found | Bao Predictions">
  <meta name="twitter:description" content="Page not found — try today's football predictions, results, or the jackpot hub.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/">
  <link rel="alternate" hreflang="x-default" href="https://www.baopredictions.com/">

  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Page Not Found | Bao Predictions">
  <meta property="og:description" content="Page not found — try today's football predictions, results, or the jackpot hub.">
  <meta property="og:url" content="https://www.baopredictions.com/">
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
  <header class="page-hero">
    <h1>404 — Page not found</h1>
    <p class="lede">That URL is not on Bao Predictions. It may have moved, or the link is outdated.</p>
  </header>

  <article class="prose">
    <p>Use one of the live boards below instead of refreshing a dead link. Jackpot sheets and tip pages change URLs rarely, but operator product names and old blog paths can still break bookmarks.</p>
    <ul>
      <li><a href="/football-predictions-today">Football Predictions Today</a> — main daily board</li>
      <li><a href="/live-football-predictions">Livescores</a> — matches already underway</li>
      <li><a href="/results">Football Results</a> — settled tips from the last seven days</li>
      <li><a href="/jackpot-predictions">Jackpot Predictions</a> — SportPesa, Betika, SportyBet, Odibets, Mozzart</li>
      <li><a href="/faq">FAQ</a> · <a href="/contact-us">Contact</a></li>
    </ul>
    <p><a class="btn btn-primary" href="/">Back to homepage</a></p>
  </article>
</div>
</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js?v=20260913c" defer></script>
</body>
</html>
