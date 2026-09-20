<?php
/**
 * Dynamic XML sitemap — lastmod from live stats / content freshness.
 */

require_once __DIR__ . '/../components/api-curl.php';

header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=3600');

$stats = bao_api_stats();
$liveIso = is_array($stats) && !empty($stats['last_updated'])
    ? (string) $stats['last_updated']
    : date('c');
$liveDay = date('Y-m-d', strtotime($liveIso) ?: time());
$today = date('Y-m-d');
$staticDay = '2026-09-12'; // last editorial pass for policy/about pages

/**
 * @param list<array{0:string,1:string,2:string,3:string}> $rows loc, lastmod Y-m-d, changefreq, priority
 */
$rows = [
    ['https://www.baopredictions.com/', $liveDay, 'hourly', '1.0'],
    ['https://www.baopredictions.com/football-predictions-today', $liveDay, 'hourly', '0.9'],
    ['https://www.baopredictions.com/live-football-predictions', $liveDay, 'always', '0.9'],
    ['https://www.baopredictions.com/results', $liveDay, 'hourly', '0.8'],
    ['https://www.baopredictions.com/football-predictions-yesterday', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/football-predictions-tomorrow', $today, 'daily', '0.8'],
    ['https://www.baopredictions.com/weekend-football-predictions', $today, 'daily', '0.7'],
    ['https://www.baopredictions.com/must-win-teams-today', $liveDay, 'hourly', '0.7'],
    ['https://www.baopredictions.com/sure-bets-today', $liveDay, 'hourly', '0.7'],
    ['https://www.baopredictions.com/banker-of-the-day', $liveDay, 'hourly', '0.8'],
    ['https://www.baopredictions.com/accumulator-tips', $liveDay, 'daily', '0.7'],
    ['https://www.baopredictions.com/betnumbers-tips', $liveDay, 'daily', '0.6'],
    ['https://www.baopredictions.com/sokafans-predictions', $liveDay, 'daily', '0.6'],
    ['https://www.baopredictions.com/cheerplex-tips', $liveDay, 'daily', '0.6'],
    ['https://www.baopredictions.com/venasbet-predictions', $liveDay, 'daily', '0.6'],
    ['https://www.baopredictions.com/1x2-predictions', $liveDay, 'hourly', '0.8'],
    ['https://www.baopredictions.com/double-chance-predictions', $liveDay, 'daily', '0.7'],
    ['https://www.baopredictions.com/over-under-predictions', $liveDay, 'daily', '0.7'],
    ['https://www.baopredictions.com/btts-predictions', $liveDay, 'daily', '0.7'],
    ['https://www.baopredictions.com/ht-ft-predictions', $liveDay, 'daily', '0.7'],
    ['https://www.baopredictions.com/jackpot-predictions', $liveDay, 'daily', '0.9'],
    ['https://www.baopredictions.com/jackpots/sportpesa-mega-jackpot-predictions', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/jackpots/sportpesa-midweek-jackpot-predictions', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/jackpots/betika-midweek-jackpot-predictions', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/jackpots/sportybet-daily-jackpot-predictions', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/jackpots/odibets-laki-tatu-predictions', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/jackpots/mozzart-super-daily-jackpot-predictions', $liveDay, 'daily', '0.8'],
    ['https://www.baopredictions.com/sunpel-prediction', $today, 'weekly', '0.5'],
    ['https://www.baopredictions.com/sitemaps', $today, 'weekly', '0.5'],
    ['https://www.baopredictions.com/blog', $today, 'weekly', '0.6'],
    ['https://www.baopredictions.com/how-we-predict', $staticDay, 'monthly', '0.6'],
    ['https://www.baopredictions.com/how-to-read-btts-odds', $staticDay, 'monthly', '0.5'],
    ['https://www.baopredictions.com/mega-jackpot-strategy-guide', $staticDay, 'monthly', '0.5'],
    ['https://www.baopredictions.com/about-us', $staticDay, 'yearly', '0.5'],
    ['https://www.baopredictions.com/partners', $staticDay, 'monthly', '0.5'],
    ['https://www.baopredictions.com/faq', $staticDay, 'monthly', '0.5'],
    ['https://www.baopredictions.com/contact-us', $staticDay, 'yearly', '0.5'],
    ['https://www.baopredictions.com/responsible-betting', $staticDay, 'yearly', '0.5'],
    ['https://www.baopredictions.com/privacy-policy', $staticDay, 'yearly', '0.3'],
    ['https://www.baopredictions.com/terms-of-service', $staticDay, 'yearly', '0.3'],
    ['https://www.baopredictions.com/llms.txt', $staticDay, 'monthly', '0.3'],
];

$brandLandings = require __DIR__ . '/../config/brand-landings.php';
if (is_array($brandLandings)) {
    foreach (array_keys($brandLandings) as $brandSlug) {
        if (!is_string($brandSlug) || $brandSlug === '') {
            continue;
        }
        $rows[] = ['https://www.baopredictions.com/' . $brandSlug, $liveDay, 'daily', '0.6'];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($rows as [$loc, $lastmod, $freq, $priority]) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
    echo '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
    echo '    <changefreq>' . htmlspecialchars($freq, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</changefreq>\n";
    echo '    <priority>' . htmlspecialchars($priority, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
