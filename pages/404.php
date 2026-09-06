<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page not found | Bao Predictions</title>
    <script>
  (function () {
    try {
      var t = localStorage.getItem('bao-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {}
  })();
  </script>
  <link rel="stylesheet" href="/assets/css/main.css">
<?php require __DIR__ . '/../components/favicon.php'; ?>
</head>
<body>
  <main class="wrap" style="padding:4rem 1rem">
    <h1>404 — Page not found</h1>
    <p class="lede">That URL isn’t on Bao Predictions.</p>
    <p><a class="btn btn-primary" href="/">Back to homepage</a></p>
  </main>
  <script src="/assets/js/timezone.js" defer></script>
<script src="/assets/js/theme.js" defer></script>
</body>
</html>
