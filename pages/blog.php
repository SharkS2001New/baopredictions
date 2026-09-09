<?php
require_once __DIR__ . '/../config/load-env.php';
require_once __DIR__ . '/../src/Api/bootstrap.php';

use App\Services\BlogService;

$posts = [];
$apiPayload = (new BlogService())->list(1, 'ALL', 50);
foreach (($apiPayload['data'] ?? []) as $row) {
    if (! is_array($row)) {
        continue;
    }
    $slug = trim((string) ($row['slug'] ?? ''));
    if ($slug === '') {
        continue;
    }
    $published = (string) ($row['published_at'] ?? $row['created_at'] ?? '');
    $posts[$slug] = [
        'title' => (string) ($row['title'] ?? 'Untitled'),
        'slug' => $slug,
        'url' => '/blog/' . rawurlencode($slug),
        'excerpt' => (string) ($row['excerpt'] ?? $row['meta_description'] ?? ''),
        'published_at' => $published,
        'source' => 'api',
    ];
}

$static = require __DIR__ . '/../config/static-blog-posts.php';
if (is_array($static)) {
    foreach ($static as $row) {
        if (! is_array($row)) {
            continue;
        }
        $slug = trim((string) ($row['slug'] ?? ''));
        if ($slug === '' || isset($posts[$slug])) {
            continue;
        }
        $posts[$slug] = [
            'title' => (string) ($row['title'] ?? 'Untitled'),
            'slug' => $slug,
            'url' => (string) ($row['url'] ?? ('/blog/' . rawurlencode($slug))),
            'excerpt' => (string) ($row['excerpt'] ?? ''),
            'published_at' => (string) ($row['published_at'] ?? ''),
            'source' => 'static',
        ];
    }
}

uasort($posts, static function (array $a, array $b): int {
    return strcmp((string) ($b['published_at'] ?? ''), (string) ($a['published_at'] ?? ''));
});

function bao_blog_list_format_date(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    $ts = strtotime($raw);
    if ($ts === false) {
        return $raw;
    }

    return date('j M Y', $ts);
}

function bao_blog_list_datetime_attr(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    $ts = strtotime($raw);
    if ($ts === false) {
        return htmlspecialchars(substr($raw, 0, 10), ENT_QUOTES, 'UTF-8');
    }

    return date('Y-m-d', $ts);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Football Betting Blog | Bao Predictions</title>
  <meta name="description" content="Bao Predictions blog — jackpot strategy, BTTS odds, Premier League form guides, and practical betting education.">
  <link rel="canonical" href="https://www.baopredictions.com/blog">
  <meta name="robots" content="index,follow">
  <meta property="og:title" content="Football Betting Blog | Bao Predictions">
  <meta property="og:description" content="Bao Predictions blog — jackpot strategy, BTTS odds, Premier League form guides, and practical betting education.">
  <meta property="og:url" content="https://www.baopredictions.com/blog">
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
<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Blog</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>Blog</h1>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_last_updated_html(); ?>
    <p class="lede">Editorial guides that build topical authority — strategy, markets, and matchweek form.</p>
  </header>
  <ul class="blog-list">
<?php if ($posts === []): ?>
    <li>
      <p class="text-muted mb-0">No posts published yet. Check back soon.</p>
    </li>
<?php else: ?>
<?php foreach ($posts as $post): ?>
    <li>
<?php
  $dt = bao_blog_list_datetime_attr((string) ($post['published_at'] ?? ''));
  $label = bao_blog_list_format_date((string) ($post['published_at'] ?? ''));
?>
<?php if ($dt !== ''): ?>
      <time datetime="<?php echo htmlspecialchars($dt, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></time>
<?php endif; ?>
      <h2 style="margin:0.35rem 0"><a href="<?php echo htmlspecialchars((string) $post['url'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $post['title'], ENT_QUOTES, 'UTF-8'); ?></a></h2>
<?php if (trim((string) ($post['excerpt'] ?? '')) !== ''): ?>
      <p class="text-muted mb-0"><?php echo htmlspecialchars((string) $post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
    </li>
<?php endforeach; ?>
<?php endif; ?>
  </ul>
</div>

  </main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
</body>
</html>
