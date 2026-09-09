<?php
/**
 * Dynamic admin-published blog post at /blog/{slug}.
 * @var string $slug
 */
require_once __DIR__ . '/../config/load-env.php';
require_once __DIR__ . '/../src/Api/bootstrap.php';

use App\Services\BlogService;

$slug = trim((string) ($slug ?? ''));
if ($slug === '' || ! preg_match('/^[a-z0-9][a-z0-9\-]{0,190}$/i', $slug)) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    return;
}

$blog = (new BlogService())->post($slug);
if ($blog === null) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    return;
}

$title = trim((string) ($blog['meta_title'] ?? $blog['title'] ?? 'Blog'));
$description = trim((string) ($blog['meta_description'] ?? $blog['excerpt'] ?? ''));
$keywords = trim((string) ($blog['meta_keywords'] ?? ''));
$content = (string) ($blog['content'] ?? '');
$publishedRaw = (string) ($blog['published_at'] ?? $blog['created_at'] ?? '');
$publishedTs = $publishedRaw !== '' ? strtotime($publishedRaw) : false;
$publishedLabel = $publishedTs ? date('j M Y', $publishedTs) : '';
$publishedIso = $publishedTs ? date('Y-m-d', $publishedTs) : '';
$canonical = 'https://www.baopredictions.com/blog/' . rawurlencode($slug);
$pageTitle = $title . (stripos($title, 'Bao Predictions') === false ? ' | Bao Predictions' : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<?php if ($description !== ''): ?>
  <meta name="description" content="<?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
<?php if ($keywords !== ''): ?>
  <meta name="keywords" content="<?php echo htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="robots" content="index,follow">
  <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($description !== ''): ?>
  <meta property="og:description" content="<?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
  <meta property="og:url" content="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
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
    <li><a href="/blog">Blog</a></li>
    <li><span aria-current="page"><?php echo htmlspecialchars((string) ($blog['title'] ?? 'Post'), ENT_QUOTES, 'UTF-8'); ?></span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1><?php echo htmlspecialchars((string) ($blog['title'] ?? 'Post'), ENT_QUOTES, 'UTF-8'); ?></h1>
<?php if ($publishedIso !== ''): ?>
    <p class="text-muted"><time datetime="<?php echo htmlspecialchars($publishedIso, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($publishedLabel, ENT_QUOTES, 'UTF-8'); ?></time></p>
<?php endif; ?>
<?php if (trim((string) ($blog['excerpt'] ?? '')) !== ''): ?>
    <p class="lede"><?php echo htmlspecialchars((string) $blog['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
  </header>
  <article class="prose">
    <?php
      // Admin-authored HTML — same trust model as Pitch / Free Winning Tips.
      echo $content;
    ?>
  </article>
</div>

</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
</body>
</html>
