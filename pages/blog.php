<?php
require_once __DIR__ . '/../config/load-env.php';
require_once __DIR__ . '/../src/Api/bootstrap.php';

use App\Services\BlogService;

$page = max(1, (int) ($_GET['page'] ?? 1));
$category = trim((string) ($_GET['category'] ?? 'ALL'));
if ($category === '') {
    $category = 'ALL';
}
$perPage = 6; // at least 3 cards per page (matches Pitch list size)

$apiPayload = (new BlogService())->list($page, $category, $perPage);
$apiPosts = is_array($apiPayload['data'] ?? null) ? $apiPayload['data'] : [];

$currentPage = (int) ($apiPayload['current_page'] ?? $page);
$lastPage = max(1, (int) ($apiPayload['last_page'] ?? 1));
$total = (int) ($apiPayload['total'] ?? count($apiPosts));

/**
 * @param  array<string,mixed>  $row
 * @return array<string,mixed>|null
 */
function bao_blog_normalize_card(array $row): ?array
{
    $slug = trim((string) ($row['slug'] ?? ''));
    if ($slug === '' || ! preg_match('/^[a-z0-9][a-z0-9\-]{0,190}$/i', $slug)) {
        return null;
    }

    $categoryName = '';
    if (isset($row['category']) && is_array($row['category'])) {
        $categoryName = trim((string) ($row['category']['name'] ?? $row['category']['blogs_category_title'] ?? ''));
    } elseif (isset($row['category_name'])) {
        $categoryName = trim((string) $row['category_name']);
    }

    $author = 'Admin';
    if (isset($row['user']) && is_array($row['user'])) {
        $author = trim((string) ($row['user']['name'] ?? '')) ?: 'Admin';
    } elseif (! empty($row['author'])) {
        $author = trim((string) $row['author']);
    }

    return [
        'id' => (string) ($row['id'] ?? $slug),
        'title' => (string) ($row['title'] ?? 'Untitled'),
        'slug' => $slug,
        'url' => '/blog/' . rawurlencode($slug),
        'excerpt' => (string) ($row['excerpt'] ?? $row['meta_description'] ?? ''),
        'published_at' => (string) ($row['published_at'] ?? $row['created_at'] ?? ''),
        'category' => $categoryName !== '' ? $categoryName : 'Articles',
        'author' => $author,
        'read_time' => max(1, (int) ($row['read_time'] ?? 5)),
    ];
}

$posts = [];
foreach ($apiPosts as $row) {
    if (! is_array($row)) {
        continue;
    }
    $card = bao_blog_normalize_card($row);
    if ($card === null) {
        continue;
    }
    $posts[] = $card;
}

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

    return date('M j, Y', $ts);
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

function bao_blog_title_case(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return 'Articles';
    }

    return mb_strtoupper(mb_substr($value, 0, 1)) . mb_strtolower(mb_substr($value, 1));
}

/**
 * @return list<int|string>
 */
function bao_blog_pagination_window(int $current, int $last): array
{
    if ($last <= 1) {
        return [];
    }
    $window = [];
    $start = max(1, $current - 2);
    $end = min($last, $start + 4);
    $start = max(1, $end - 4);

    if ($start > 1) {
        $window[] = 1;
        if ($start > 2) {
            $window[] = '…';
        }
    }
    for ($i = $start; $i <= $end; $i++) {
        $window[] = $i;
    }
    if ($end < $last) {
        if ($end < $last - 1) {
            $window[] = '…';
        }
        $window[] = $last;
    }

    return $window;
}

$paginationPages = bao_blog_pagination_window($currentPage, $lastPage);
$canonical = 'https://www.baopredictions.com/blog';
if ($currentPage > 1) {
    $canonical .= '?page=' . $currentPage;
}

function bao_blog_page_url(int $pageNum, string $category): string
{
    $params = [];
    if ($pageNum > 1) {
        $params['page'] = $pageNum;
    }
    if (strtoupper($category) !== 'ALL') {
        $params['category'] = $category;
    }
    $qs = http_build_query($params);

    return $qs !== '' ? '/blog?' . $qs : '/blog';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bao Predictions Blog – Expert Tips, Predictions &amp; Football Insights</title>
  <meta name="description" content="Bao Predictions blog — jackpot strategy, BTTS odds, Premier League form guides, and practical betting education.">
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="robots" content="index,follow">
  <meta property="og:title" content="Bao Predictions Blog – Expert Tips, Predictions &amp; Football Insights">
  <meta property="og:description" content="Bao Predictions blog — jackpot strategy, BTTS odds, Premier League form guides, and practical betting education.">
  <meta property="og:url" content="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
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
<main id="main" class="blogs-page">
<div class="wrap">

  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">Blog</span></li>
  </ol>
</nav>

  <header class="blogs-page-title">
    <h1>Bao Predictions Blog – Expert Tips, Predictions &amp; Football Insights</h1>
  </header>

<?php if ($posts === []): ?>
  <div class="blogs-empty">
    <p>No blogs available.</p>
  </div>
<?php else: ?>
  <div class="blog-list-grid">
<?php foreach ($posts as $post): ?>
<?php
  $dt = bao_blog_list_datetime_attr((string) ($post['published_at'] ?? ''));
  $dateLabel = bao_blog_list_format_date((string) ($post['published_at'] ?? ''));
  $href = htmlspecialchars((string) $post['url'], ENT_QUOTES, 'UTF-8');
?>
    <article class="blog-card">
      <div class="blog-content">
        <small class="blog-category"><?php echo htmlspecialchars(bao_blog_title_case((string) $post['category']), ENT_QUOTES, 'UTF-8'); ?></small>
        <a href="<?php echo $href; ?>" class="blog-title"><?php echo htmlspecialchars((string) $post['title'], ENT_QUOTES, 'UTF-8'); ?></a>
        <div class="blog-meta">
          <?php echo htmlspecialchars((string) $post['author'], ENT_QUOTES, 'UTF-8'); ?>
<?php if ($dateLabel !== ''): ?>
          &nbsp;/&nbsp;
          <time datetime="<?php echo htmlspecialchars($dt, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dateLabel, ENT_QUOTES, 'UTF-8'); ?></time>
<?php endif; ?>
        </div>
<?php if (trim((string) ($post['excerpt'] ?? '')) !== ''): ?>
        <p class="blog-excerpt"><?php echo htmlspecialchars((string) $post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
      </div>
      <div class="blog-footer">
        <a href="<?php echo $href; ?>" class="read-more-btn" rel="bookmark">Read More →</a>
        <div class="blog-read-time">
          <span><?php echo (int) $post['read_time']; ?> Minutes</span>
        </div>
      </div>
    </article>
<?php endforeach; ?>
  </div>

<?php if ($lastPage > 1): ?>
  <nav class="pagination-container" aria-label="Blog pages">
<?php if ($currentPage > 1): ?>
    <a class="page-btn" href="<?php echo htmlspecialchars(bao_blog_page_url($currentPage - 1, $category), ENT_QUOTES, 'UTF-8'); ?>">Previous</a>
<?php endif; ?>
<?php foreach ($paginationPages as $p): ?>
<?php if ($p === '…'): ?>
    <span class="page-ellipsis" aria-hidden="true">…</span>
<?php else: ?>
    <a
      class="page-btn<?php echo ((int) $p === $currentPage) ? ' active' : ''; ?>"
      href="<?php echo htmlspecialchars(bao_blog_page_url((int) $p, $category), ENT_QUOTES, 'UTF-8'); ?>"
      <?php echo ((int) $p === $currentPage) ? 'aria-current="page"' : ''; ?>
    ><?php echo (int) $p; ?></a>
<?php endif; ?>
<?php endforeach; ?>
<?php if ($currentPage < $lastPage): ?>
    <a class="page-btn" href="<?php echo htmlspecialchars(bao_blog_page_url($currentPage + 1, $category), ENT_QUOTES, 'UTF-8'); ?>">Next</a>
<?php endif; ?>
  </nav>
<?php endif; ?>
<?php endif; ?>
</div>

  </main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
</body>
</html>
