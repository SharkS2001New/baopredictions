<?php
/**
 * Apply SEO content plan to pages/*.php
 * Run: php scripts/apply-seo.php
 */
require_once __DIR__ . '/../components/seo.php';

$core = require __DIR__ . '/../config/seo-core.php';
$more = require __DIR__ . '/../config/seo-markets-jackpots.php';
$seoMap = array_merge($core, $more);

$pagesDir = __DIR__ . '/../pages';

foreach ($seoMap as $slug => $seo) {
    $file = $pagesDir . '/' . $slug . '.php';
    if ($slug === 'homepage') {
        $file = $pagesDir . '/homepage.php';
    }
    if (!is_file($file)) {
        echo "skip missing $slug\n";
        continue;
    }

    $html = file_get_contents($file);

    // Title
    if (!empty($seo['title_tpl'])) {
        $titlePhp = "<?php echo str_replace('{date}', bao_today_label(), " . var_export($seo['title_tpl'], true) . "); ?>";
        // For files that aren't bootstrapping seo.php yet, use inline date
        $titleInline = str_replace('{date}', "' . date('l j F Y') . '", $seo['title_tpl']);
        // Simpler: embed PHP date directly in title tag
        $titleTag = preg_replace(
            '/\{date\}/',
            '<?php echo date(\'l j F Y\'); ?>',
            htmlspecialchars_decode($seo['title_tpl'])
        );
        // Build title with PHP
        $newTitle = str_replace('{date}', '<?php echo date(\'l j F Y\'); ?>', $seo['title_tpl']);
        $html = preg_replace('/<title>.*?<\/title>/s', '<title>' . $newTitle . '</title>', $html, 1);
        $html = preg_replace('/<meta property="og:title" content="[^"]*">/', '<meta property="og:title" content="' . htmlspecialchars(str_replace('{date}', date('l j F Y'), $seo['title_tpl']), ENT_QUOTES) . '">', $html, 1);
    } elseif (!empty($seo['title'])) {
        $html = preg_replace('/<title>.*?<\/title>/s', '<title>' . htmlspecialchars($seo['title']) . '</title>', $html, 1);
        $html = preg_replace('/<meta property="og:title" content="[^"]*">/', '<meta property="og:title" content="' . htmlspecialchars($seo['title'], ENT_QUOTES) . '">', $html, 1);
    }

    if (!empty($seo['description'])) {
        $d = htmlspecialchars($seo['description'], ENT_QUOTES);
        $html = preg_replace('/<meta name="description" content="[^"]*">/', '<meta name="description" content="' . $d . '">', $html, 1);
        $html = preg_replace('/<meta property="og:description" content="[^"]*">/', '<meta property="og:description" content="' . $d . '">', $html, 1);
    }

    if (!empty($seo['canonical'])) {
        $c = htmlspecialchars('https://www.baopredictions.com' . ($seo['canonical'] === '/' ? '/' : $seo['canonical']), ENT_QUOTES);
        $html = preg_replace('/<link rel="canonical" href="[^"]*">/', '<link rel="canonical" href="' . $c . '">', $html, 1);
        $html = preg_replace('/<meta property="og:url" content="[^"]*">/', '<meta property="og:url" content="' . $c . '">', $html, 1);
    }

    // H1
    if (!empty($seo['h1_tpl'])) {
        $h1 = str_replace('{date}', '<?php echo date(\'l j F Y\'); ?>', $seo['h1_tpl']);
        $html = preg_replace('/<h1>.*?<\/h1>/s', '<h1>' . $h1 . '</h1>', $html, 1);
    } elseif (!empty($seo['h1'])) {
        $html = preg_replace('/<h1>.*?<\/h1>/s', '<h1>' . htmlspecialchars($seo['h1']) . '</h1>', $html, 1);
    }

    // Inject SEO content block after first </h1> (inside page-hero if present)
    $relatedHtml = '';
    if (!empty($seo['related'])) {
        $relatedHtml = '<p class="seo-related"><strong>Related:</strong> ';
        $links = [];
        foreach ($seo['related'] as $r) {
            $links[] = '<a href="' . htmlspecialchars($r[1]) . '">' . htmlspecialchars($r[0]) . '</a>';
        }
        $relatedHtml .= implode(' · ', $links) . '</p>';
    }

    $block = "\n" . '<?php require_once __DIR__ . \'/../components/seo.php\'; echo bao_last_updated_html(); ?>' . "\n";
    $block .= '<p class="seo-unique">' . htmlspecialchars($seo['unique']) . '</p>' . "\n";
    if (!empty($seo['rg'])) {
        $block .= '<?php echo bao_rg_notice_html(); ?>' . "\n";
    }
    $block .= $relatedHtml . "\n";

    // Remove previous injected blocks if re-run
    $html = preg_replace('/<\?php require_once __DIR__ \. \'\/\.\.\/components\/seo\.php\'; echo bao_last_updated_html\(\); \?>\s*/', '', $html);
    $html = preg_replace('/<p class="seo-unique">.*?<\/p>\s*/s', '', $html);
    $html = preg_replace('/<\?php echo bao_rg_notice_html\(\); \?>\s*/', '', $html);
    $html = preg_replace('/<p class="rg-notice">.*?<\/p>\s*/s', '', $html);
    $html = preg_replace('/<p class="seo-related">.*?<\/p>\s*/s', '', $html);
    $html = preg_replace('/<p class="last-updated">.*?<\/p>\s*/s', '', $html);

    $html = preg_replace('/<\/h1>/', '</h1>' . $block, $html, 1);

    // FAQ section replace
    if (!empty($seo['faqs'])) {
        $faqHtml = bao_faq_html($seo['faqs']);
        if (preg_match('/<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">FAQ.*?<\/section>/s', $html)) {
            $html = preg_replace('/<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">FAQ.*?<\/section>/s', $faqHtml, $html, 1);
        } else {
            // insert before footer / before </main>
            $html = preg_replace('/<\/main>/', $faqHtml . "\n</main>", $html, 1);
        }
    }

    // Schema before </body>
    $schemas = '<?php require_once __DIR__ . \'/../components/seo.php\'; ';
    if (!empty($seo['faqs'])) {
        $schemas .= 'echo bao_faq_schema(' . var_export($seo['faqs'], true) . '); ';
    }
    if (!empty($seo['crumbs'])) {
        $schemas .= 'echo bao_breadcrumb_schema(' . var_export($seo['crumbs'], true) . '); ';
    }
    $schemas .= 'echo bao_organization_schema(); ?>';

    // Only strip schema injection blocks — never match from last_updated require_once
    $html = preg_replace(
        '/<\?php require_once __DIR__ \. \'\/\.\.\/components\/seo\.php\';\s*(?:echo bao_faq_schema\(.*?\);\s*)?(?:echo bao_breadcrumb_schema\(.*?\);\s*)?echo bao_organization_schema\(\); \?>\s*/s',
        '',
        $html
    );
    $html = preg_replace('/<!--BAO_SCHEMA_START-->.*?<!--BAO_SCHEMA_END-->\s*/s', '', $html);
    $html = preg_replace('/<script type="application\/ld\+json">\{[^{]*"@type":\s*"FAQPage".*?<\/script>\s*/s', '', $html);
    $schemas = "<!--BAO_SCHEMA_START-->\n" . $schemas . "\n<!--BAO_SCHEMA_END-->";
    $html = preg_replace('/<\/body>/', $schemas . "\n</body>", $html, 1);

    // Ensure require seo.php once at top for date helpers in title
    if (strpos($html, "require_once __DIR__ . '/../components/seo.php'") === false && (isset($seo['title_tpl']) || isset($seo['h1_tpl']))) {
        $html = preg_replace('/^<!DOCTYPE html>/', "<?php require_once __DIR__ . '/../components/seo.php'; ?>\n<!DOCTYPE html>", $html, 1);
    } elseif (strpos($html, 'date(\'l j F Y\')') !== false && strpos($html, "require_once __DIR__ . '/../components/seo.php'") === false) {
        // date() doesn't need seo.php but last-updated block does — already inline require before echo
    }

    // Fix double require in last-updated - the block already has require_once which is fine

    file_put_contents($file, $html);
    echo "updated $slug\n";
}

echo "Done.\n";
