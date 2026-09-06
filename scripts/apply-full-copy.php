<?php
/**
 * Apply Accuratetip-style Full Page Copy + SEO stack to pages/*.php
 * Run: php scripts/apply-full-copy.php
 */
require_once __DIR__ . '/../components/seo.php';

$copy = array_merge(
    require __DIR__ . '/../config/page-copy-core.php',
    require __DIR__ . '/../config/page-copy-markets.php',
    require __DIR__ . '/../config/page-copy-jackpots.php',
    require __DIR__ . '/../config/page-copy-static.php'
);

$articles = require __DIR__ . '/../config/seo-articles.php';
foreach ($articles as $slug => $extra) {
    $copy[$slug] = array_merge($copy[$slug] ?? [], $extra);
}

$pagesDir = __DIR__ . '/../pages';

$FOOTER_DISCLAIMER = <<<'HTML'
    <div class="footer-disclaimer">
      <p>Predictions are for informational purposes only and do not guarantee outcomes. Betting involves financial risk — please gamble responsibly and only with money you can afford to lose. Must be 18+ (or the legal age in your jurisdiction). If gambling is affecting your life, contact <a href="https://www.begambleaware.org/" rel="noopener noreferrer" target="_blank">BeGambleAware.org</a> or your local support service.</p>
      <p>© <?php echo date('Y'); ?> Bao Predictions. All rights reserved.</p>
    </div>
HTML;

function bao_date_php(bool $tomorrow = false): string {
    if ($tomorrow) {
        return "<?php echo date('l j F Y', strtotime('+1 day')); ?>";
    }
    return "<?php echo date('l j F Y'); ?>";
}

function bao_date_label(bool $tomorrow = false): string {
    return $tomorrow ? date('l j F Y', strtotime('+1 day')) : date('l j F Y');
}

function bao_ensure_18(string $desc): string {
    $desc = trim($desc);
    if ($desc === '') {
        return $desc;
    }
    if (!preg_match('/18\+\s*only\.?\s*$/i', $desc)) {
        $desc = rtrim($desc, '.') . '. 18+ only.';
    }
    return $desc;
}

function bao_canonical_path(string $slug): string {
    return $slug === 'homepage' ? '/' : '/' . $slug . '/';
}

foreach ($copy as $slug => $seo) {
    $file = $pagesDir . '/' . $slug . '.php';
    if (!is_file($file)) {
        echo "skip missing $slug\n";
        continue;
    }

    $html = file_get_contents($file);
    $tomorrow = !empty($seo['tomorrow']);
    $datePhp = bao_date_php($tomorrow);
    $dateLabel = bao_date_label($tomorrow);
    $canonical = bao_canonical_path($slug);

    if (!empty($seo['description'])) {
        $seo['description'] = bao_ensure_18($seo['description']);
    }

    // —— Title + OG title ——
    // For dated titles, keep PHP date() in the page source so OG/twitter never freeze on apply day.
    if (!empty($seo['title_tpl'])) {
        $newTitle = str_replace('{date}', $datePhp, $seo['title_tpl']);
        $html = preg_replace('/<title>.*?<\/title>/s', '<title>' . $newTitle . '</title>', $html, 1);
        $ogTitlePhp = str_replace('{date}', $datePhp, $seo['title_tpl']);
        $html = preg_replace(
            '/<meta property="og:title" content="[^"]*">/',
            '<meta property="og:title" content="' . $ogTitlePhp . '">',
            $html,
            1
        );
        // Runtime label for helpers that need a plain string (schema / head helper preview)
        $seo['title'] = str_replace('{date}', $dateLabel, $seo['title_tpl']);
        $seo['title_php'] = $ogTitlePhp;
    } elseif (!empty($seo['title'])) {
        $html = preg_replace('/<title>.*?<\/title>/s', '<title>' . htmlspecialchars($seo['title']) . '</title>', $html, 1);
        $html = preg_replace('/<meta property="og:title" content="[^"]*">/', '<meta property="og:title" content="' . htmlspecialchars($seo['title'], ENT_QUOTES) . '">', $html, 1);
    }

    // —— Description + OG description ——
    if (!empty($seo['description'])) {
        $d = htmlspecialchars($seo['description'], ENT_QUOTES);
        $html = preg_replace('/<meta name="description" content="[^"]*">/', '<meta name="description" content="' . $d . '">', $html, 1);
        $html = preg_replace('/<meta property="og:description" content="[^"]*">/', '<meta property="og:description" content="' . $d . '">', $html, 1);
    }

    // —— Accuratetip head extras (keywords, author, date, twitter, article times) ——
    $html = preg_replace('/<!--BAO_HEAD_EXTRA_START-->.*?<!--BAO_HEAD_EXTRA_END-->\s*/s', '', $html);
    $html = preg_replace('/\s*<meta name="title" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta name="keywords" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta name="author" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta name="date" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta name="twitter:card" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta name="twitter:title" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta name="twitter:description" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta property="article:published_time" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta property="article:modified_time" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<meta property="article:author" content="[^"]*">\s*/', "\n  ", $html);
    $html = preg_replace('/\s*<link rel="alternate" hreflang="en" href="[^"]*">\s*/', "\n  ", $html);

    if (!empty($seo['title']) || !empty($seo['keywords']) || !empty($seo['title_php'])) {
        $headSeo = [
            'title' => $seo['title'] ?? '',
            'title_php' => $seo['title_php'] ?? null,
            'description' => $seo['description'] ?? '',
            'keywords' => $seo['keywords'] ?? '',
            'canonical' => $canonical,
        ];
        $headBlock = "<!--BAO_HEAD_EXTRA_START-->\n  " . bao_head_meta_tags($headSeo) . "\n  <!--BAO_HEAD_EXTRA_END-->\n  ";
        if (preg_match('/<meta name="robots"[^>]*>/', $html)) {
            $html = preg_replace('/(<meta name="robots"[^>]*>)/', "$1\n  " . $headBlock, $html, 1);
        } else {
            $html = preg_replace('/(<link rel="canonical"[^>]*>)/', "$1\n  " . $headBlock, $html, 1);
        }
    }

    // Force og:type article on tip pages
    if ($slug !== 'homepage') {
        $html = preg_replace('/<meta property="og:type" content="[^"]*">/', '<meta property="og:type" content="article">', $html, 1);
    } else {
        $html = preg_replace('/<meta property="og:type" content="[^"]*">/', '<meta property="og:type" content="website">', $html, 1);
    }

    // —— H1 ——
    if (!empty($seo['h1_tpl'])) {
        $h1 = str_replace('{date}', $datePhp, $seo['h1_tpl']);
        $html = preg_replace('/<h1>.*?<\/h1>/s', '<h1>' . $h1 . '</h1>', $html, 1);
    } elseif (!empty($seo['h1'])) {
        $html = preg_replace('/<h1>.*?<\/h1>/s', '<h1>' . htmlspecialchars($seo['h1']) . '</h1>', $html, 1);
    }

    // Hero lede on homepage
    if ($slug === 'homepage' && !empty($seo['lede'])) {
        $html = preg_replace(
            '/<p class="lede">.*?<\/p>/s',
            '<p class="lede">' . htmlspecialchars($seo['lede']) . '</p>',
            $html,
            1
        );
        $html = preg_replace(
            '/(<li>\s*<strong>)72%(<\/strong>\s*<span>Today\'s accuracy<\/span>)/s',
            '$1<!--API: today\'s accuracy %-->72%$2',
            $html,
            1
        );
        $html = preg_replace(
            '/(<li>\s*<strong>)48(<\/strong>\s*<span>Predictions today<\/span>)/s',
            '$1<!--API: predictions published today-->48$2',
            $html,
            1
        );
        $html = preg_replace(
            '/(<li>\s*<strong>)5(<\/strong>\s*<span>Current win streak<\/span>)/s',
            '$1<!--API: current streak-->5$2',
            $html,
            1
        );
    }

    $unique = $seo['unique'] ?? null;
    $relatedHtml = '';
    if (!empty($seo['related'])) {
        $relatedHtml = '<p class="seo-related"><strong>Related:</strong> ';
        $links = [];
        foreach ($seo['related'] as $r) {
            $links[] = '<a href="' . htmlspecialchars($r[1]) . '">' . htmlspecialchars($r[0]) . '</a>';
        }
        $relatedHtml .= implode(' · ', $links) . '</p>';
    }

    // Strip prior featured / article blocks before re-inject
    $html = preg_replace('/<!--BAO_FEATURED_START-->.*?<!--BAO_FEATURED_END-->\s*/s', '', $html);
    $html = preg_replace('/<aside class="featured-banner".*?<\/aside>\s*/s', '', $html);
    $html = preg_replace('/<!--BAO_ARTICLE_START-->.*?<!--BAO_ARTICLE_END-->\s*/s', '', $html);
    $html = preg_replace('/<section class="section section-muted seo-article-section">.*?<\/section>\s*/s', '', $html);

    if ($unique !== null) {
        // Compact hero: last updated + RG only (fixtures first; write-up below)
        $heroBlock = "\n" . '<?php require_once __DIR__ . \'/../components/seo.php\'; echo bao_last_updated_html(); ?>' . "\n";
        if (!empty($seo['rg'])) {
            $heroBlock .= '<?php echo bao_rg_notice_html(); ?>' . "\n";
        }

        $writeParts = [];
        $writeParts[] = '<p class="seo-unique">' . htmlspecialchars($unique) . '</p>';
        if (!empty($seo['featured_title']) && !empty($seo['featured_text'])) {
            $writeParts[] = "<!--BAO_FEATURED_START-->";
            $writeParts[] = bao_featured_banner_html($seo['featured_title'], $seo['featured_text']);
            $writeParts[] = "<!--BAO_FEATURED_END-->";
        }
        if ($relatedHtml !== '') {
            $writeParts[] = $relatedHtml;
        }
        $writeupBlock = "\n<section class=\"section section-muted bao-writeup\">\n"
            . "  <div class=\"wrap prose\">\n"
            . implode("\n", $writeParts) . "\n"
            . "  </div>\n"
            . "</section>\n";

        $html = preg_replace('/<\?php require_once __DIR__ \. \'\/\.\.\/components\/seo\.php\'; echo bao_last_updated_html\(\); \?>\s*/', '', $html);
        $html = preg_replace('/<section class="section section-muted bao-writeup">.*?<\/section>\s*/s', '', $html);
        $html = preg_replace('/<p class="seo-unique">.*?<\/p>\s*/s', '', $html);
        $html = preg_replace('/<\?php echo bao_rg_notice_html\(\); \?>\s*/', '', $html);
        $html = preg_replace('/<p class="rg-notice">.*?<\/p>\s*/s', '', $html);
        $html = preg_replace('/<p class="seo-related">.*?<\/p>\s*/s', '', $html);
        $html = preg_replace('/<p class="last-updated">.*?<\/p>\s*/s', '', $html);
        $html = preg_replace('/<!--TRUST-->.*?<!--\/TRUST-->\s*/s', '', $html);

        $html = preg_replace('/<\/h1>/', '</h1>' . $heroBlock, $html, 1);

        // Place write-up after fixtures grid (games first)
        if (preg_match('/<\/div>\s*<!--\s*\/\.matches-area\s*-->[\s\S]*?<\/section>/', $html, $m, PREG_OFFSET_CAPTURE)) {
            $end = $m[0][1] + strlen($m[0][0]);
            $html = substr($html, 0, $end) . $writeupBlock . substr($html, $end);
        } else {
            $html = preg_replace('/<\/main>/', $writeupBlock . "</main>", $html, 1);
        }
    } elseif (!empty($seo['featured_title']) && !empty($seo['featured_text'])) {
        // Pages without unique still get featured under first h1 if present
        $feat = "\n<!--BAO_FEATURED_START-->\n"
            . bao_featured_banner_html($seo['featured_title'], $seo['featured_text']) . "\n"
            . "<!--BAO_FEATURED_END-->\n";
        $html = preg_replace('/<\/h1>/', '</h1>' . $feat, $html, 1);
    }

    // Replace methodology / how-we-predict section on homepage
    if (!empty($seo['methodology_html'])) {
        $html = preg_replace(
            '/<section class="section">\s*<div class="wrap prose">\s*<h2>How we predict<\/h2>.*?<\/section>/s',
            $seo['methodology_html'],
            $html,
            1
        );
        $html = preg_replace(
            '/<section class="section">\s*<div class="wrap prose">\s*<h2>How Bao Predictions works<\/h2>.*?<\/section>/s',
            $seo['methodology_html'],
            $html,
            1
        );
    }

    // Inject howto / explainer / headline sections before FAQ or </main>
    foreach (['howto_html', 'explainer_html', 'headline_html'] as $key) {
        if (empty($seo[$key])) {
            continue;
        }
        if ($key === 'howto_html' && preg_match('/<h2>How (the |Odibets )/i', $seo[$key], $m)) {
            $html = preg_replace('/<section class="section section-muted">\s*<div class="wrap prose">\s*<h2>How .*?<\/h2>.*?<\/section>/s', '', $html);
        }
        if ($key === 'explainer_html') {
            $html = preg_replace('/<section class="section section-muted">\s*<div class="wrap prose">\s*<h2>How accumulator odds work<\/h2>.*?<\/section>/s', '', $html);
        }
        if ($key === 'headline_html') {
            $html = preg_replace('/<section class="section section-muted">\s*<div class="wrap prose">\s*<h2>Today\'s headline fixture<\/h2>.*?<\/section>/s', '', $html);
        }
        if (preg_match('/<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">/s', $html)) {
            $html = preg_replace(
                '/(<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">)/s',
                $seo[$key] . "\n$1",
                $html,
                1
            );
        } else {
            $html = preg_replace('/<\/main>/', $seo[$key] . "\n</main>", $html, 1);
        }
    }

    // Long-form Accuratetip content article (before FAQ)
    if (!empty($seo['article_title']) && !empty($seo['article_html'])) {
        $articleBlock = "<!--BAO_ARTICLE_START-->\n"
            . bao_seo_article_html($seo['article_title'], $seo['article_html']) . "\n"
            . "<!--BAO_ARTICLE_END-->\n";
        if (preg_match('/<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">/s', $html)) {
            $html = preg_replace(
                '/(<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">)/s',
                $articleBlock . '$1',
                $html,
                1
            );
        } else {
            $html = preg_replace('/<\/main>/', $articleBlock . "</main>", $html, 1);
        }
    }

    // Trust paragraph on yesterday
    if (!empty($seo['trust_html'])) {
        $html = preg_replace('/<!--TRUST-->.*?<!--\/TRUST-->/s', '', $html);
        if (strpos($html, 'seo-unique') !== false) {
            $html = preg_replace(
                '/(<p class="seo-unique">.*?<\/p>)/s',
                '$1' . "\n<!--TRUST-->" . $seo['trust_html'] . "<!--/TRUST-->",
                $html,
                1
            );
        }
    }

    // Static body pages
    if (!empty($seo['body_html'])) {
        if (preg_match('/<article class="prose">.*?<\/article>/s', $html)) {
            $html = preg_replace(
                '/<article class="prose">.*?<\/article>/s',
                '<article class="prose">' . "\n" . $seo['body_html'] . "\n" . '</article>',
                $html,
                1
            );
        } elseif (preg_match('/<div class="wrap prose">.*?<\/div>\s*<\/section>/s', $html)) {
            $html = preg_replace(
                '/<div class="wrap prose">.*?<\/div>\s*<\/section>/s',
                '<div class="wrap prose">' . "\n" . $seo['body_html'] . "\n" . '</div></section>',
                $html,
                1
            );
        }
        if (in_array($slug, ['how-we-predict', 'about-us', 'responsible-betting'], true)) {
            $html = preg_replace('/\s*<p class="lede">.*?<\/p>/s', '', $html, 1);
        }
    }

    // FAQ page: replace bare faq-list inside main wrap
    if ($slug === 'faq' && !empty($seo['faqs'])) {
        $faqInner = '';
        foreach ($seo['faqs'] as $item) {
            $faqInner .= '<li><details><summary>' . htmlspecialchars($item['q']) . '</summary><p>' . htmlspecialchars($item['a']) . '</p></details></li>';
        }
        $html = preg_replace('/<ul class="faq-list">.*?<\/ul>/s', '<ul class="faq-list">' . $faqInner . '</ul>', $html, 1);
        $seo['_faq_done'] = true;
    }

    // FAQ section
    if (!empty($seo['faqs']) && empty($seo['_faq_done'])) {
        $faqHeading = $seo['faq_heading'] ?? 'FAQ';
        $faqHtml = bao_faq_html($seo['faqs'], $faqHeading);
        if (preg_match('/<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">.*?FAQ.*?<\/section>/s', $html)) {
            $html = preg_replace('/<section class="section">\s*<div class="wrap">\s*<h2 class="section-title">.*?FAQ.*?<\/section>/s', $faqHtml, $html, 1);
        } else {
            $html = preg_replace('/<\/main>/', $faqHtml . "\n</main>", $html, 1);
        }
    }

    // Schema — strip prior BAO schema / FAQ / Article ld+json we own
    $html = preg_replace(
        '/<\?php require_once __DIR__ \. \'\/\.\.\/components\/seo\.php\';\s*(?:echo bao_faq_schema\(.*?\);\s*)?(?:echo bao_breadcrumb_schema\(.*?\);\s*)?(?:echo bao_article_schema\(.*?\);\s*)?echo bao_organization_schema\(\); \?>\s*/s',
        '',
        $html
    );
    $html = preg_replace('/<!--BAO_SCHEMA_START-->.*?<!--BAO_SCHEMA_END-->\s*/s', '', $html);
    $html = preg_replace(
        '/<script type="application\/ld\+json">\{[^{]*"@type":\s*"FAQPage".*?<\/script>\s*/s',
        '',
        $html
    );
    $html = preg_replace(
        '/<script type="application\/ld\+json">\{[^{]*"@type":\s*"Article".*?<\/script>\s*/s',
        '',
        $html
    );

    $schemas = "<!--BAO_SCHEMA_START-->\n<?php require_once __DIR__ . '/../components/seo.php'; ";
    if (!empty($seo['faqs'])) {
        $faqsForSchema = array_map(function ($item) {
            $item['a'] = trim(preg_replace('/<!--.*?-->/s', '', $item['a']));
            return $item;
        }, $seo['faqs']);
        $schemas .= 'echo bao_faq_schema(' . var_export($faqsForSchema, true) . '); ';
    }
    if (!empty($seo['crumbs'])) {
        $schemas .= 'echo bao_breadcrumb_schema(' . var_export($seo['crumbs'], true) . '); ';
    }
    if (!empty($seo['article_title']) || !empty($seo['title']) || !empty($seo['h1'])) {
        $headline = $seo['article_title'] ?? ($seo['h1'] ?? ($seo['title'] ?? 'Bao Predictions'));
        // Strip PHP date tokens from headline for schema
        $headline = preg_replace('/<\?php echo date\(.*?\); \?>/', date('l j F Y'), $headline);
        $desc = $seo['description'] ?? ($seo['unique'] ?? '');
        $schemas .= 'echo bao_article_schema(' . var_export($headline, true) . ', ' . var_export($desc, true) . ', ' . var_export($canonical === '/' ? '/' : rtrim($canonical, '/'), true) . '); ';
    }
    $schemas .= "echo bao_organization_schema(); ?>\n<!--BAO_SCHEMA_END-->";

    $html = preg_replace('/<\/body>/', $schemas . "\n</body>", $html, 1);

    // Footer disclaimer
    $html = preg_replace(
        '/<div class="footer-disclaimer">.*?<\/div>/s',
        $FOOTER_DISCLAIMER,
        $html,
        1
    );

    file_put_contents($file, $html);
    echo "updated $slug\n";
}

// Footer-only pass for pages not in copy map
foreach (glob($pagesDir . '/*.php') as $file) {
    $slug = basename($file, '.php');
    if (isset($copy[$slug])) {
        continue;
    }
    $html = file_get_contents($file);
    if (strpos($html, 'footer-disclaimer') === false) {
        continue;
    }
    $new = preg_replace(
        '/<div class="footer-disclaimer">.*?<\/div>/s',
        $FOOTER_DISCLAIMER,
        $html,
        1
    );
    if ($new !== $html) {
        file_put_contents($file, $new);
        echo "footer $slug\n";
    }
}

echo "done\n";
