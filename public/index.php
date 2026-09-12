<?php
/**
 * Front controller — same pattern as betsassurednewwebsite.
 *
 * Local:  php -S localhost:5000 -t public public/index.php
 * Apache: DocumentRoot → public/ (see .htaccess)
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Dynamic sitemap must not be short-circuited as a static file.
if ($uri === '/sitemap.xml') {
    require_once __DIR__ . '/../src/Facades/Router.php';
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    include __DIR__ . '/../pages/sitemap.php';
    return true;
}

// Let the built-in server serve real static files from /public
if ($uri !== '/' && $uri !== '' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

require_once __DIR__ . '/../src/Facades/Router.php';
require_once __DIR__ . '/../src/Api/bootstrap.php';

use App\Facades\Router;

$router = new Router();

// —— JSON API ——
$router->get('/api/health', function () {
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    require_once __DIR__ . '/../config/load-env.php';

    $envProbe = [
        'db_host_set' => bao_env('DB_HOST') !== null && (string) bao_env('DB_HOST') !== '',
        'db_name_set' => bao_env('DB_DATABASE') !== null && (string) bao_env('DB_DATABASE') !== '',
        'db_user_set' => bao_env('DB_USERNAME') !== null && (string) bao_env('DB_USERNAME') !== '',
        'has_dotenv_blob' => (isset($_ENV['.env']) && is_string($_ENV['.env']) && $_ENV['.env'] !== '')
            || (is_string(getenv('.env')) && getenv('.env') !== ''),
        'has_dotenv_file' => is_file(dirname(__DIR__) . '/.env'),
    ];

    try {
        $pdo = \App\Database::connection();
        $pdo->query('SELECT 1');
        $cacheDriver = class_exists(\App\Support\Cache::class)
            ? \App\Support\Cache::driver()
            : 'none';
        bao_api_json([
            'ok' => true,
            'db' => 'up',
            'cache' => $cacheDriver,
            'env' => $envProbe,
            'time' => date('c'),
        ]);
    } catch (Throwable $e) {
        bao_log_exception($e, 'Health check DB failed', $envProbe);
        bao_api_error('db down: ' . $e->getMessage(), 503, [
            'env' => $envProbe,
            'hint' => 'Set DB_* via flat secret keys, or a laravel-env ".env" blob/file. Image does not bake .env.',
        ]);
    }
});

$router->get('/api/pages', function () {
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    $api = new \App\Services\PageApiService();
    $pages = [];
    foreach ($api->pageKeys() as $key) {
        $pages[] = [
            'page' => $key,
            'endpoint' => '/api/' . $key,
        ];
    }
    bao_api_json(['ok' => true, 'count' => count($pages), 'pages' => $pages]);
});

$router->get('/api/stats', function () {
    require_once __DIR__ . '/../components/api-curl.php';
    try {
        $payload = bao_curl_api('/api/stats');
        if ($payload === null) {
            bao_api_error('stats unavailable', 500);
        }
        bao_api_json($payload);
    } catch (Throwable $e) {
        bao_api_error($e->getMessage(), 500);
    }
});

$router->get('/api/games', function () {
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    try {
        $filters = [
            'day' => $_GET['day'] ?? '',
            'date' => $_GET['date'] ?? '',
            'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 50,
            'league_id' => isset($_GET['league_id']) ? (int) $_GET['league_id'] : 0,
            'status' => $_GET['status'] ?? '',
            'source' => $_GET['source'] ?? 'fixtures',
            'category' => $_GET['category'] ?? '',
            'jackpot' => $_GET['jackpot'] ?? '',
            'market' => $_GET['market'] ?? '1x2',
            'min_confidence' => isset($_GET['min_confidence']) ? (int) $_GET['min_confidence'] : 0,
        ];
        $service = new \App\Services\GamesService();
        $games = $service->listGames($filters);
        bao_api_json([
            'ok' => true,
            'date' => $service->resolveDate($filters),
            'source' => $filters['source'] ?: 'fixtures',
            'count' => count($games),
            'games' => $games,
        ]);
    } catch (Throwable $e) {
        bao_api_error($e->getMessage(), 500);
    }
});

// One dedicated endpoint per tips page (from config/api-pages.php)
$pageApiKeys = array_keys(require __DIR__ . '/../config/api-pages.php');
foreach ($pageApiKeys as $pageKey) {
    $router->get('/api/' . $pageKey, function () use ($pageKey) {
        require_once __DIR__ . '/../components/api-curl.php';
        try {
            $hasOverrides = isset($_GET['limit']) || isset($_GET['date'])
                || isset($_GET['start_index']) || isset($_GET['end_index']);
            if (!$hasOverrides) {
                $payload = bao_curl_api('/api/' . $pageKey);
                if ($payload === null) {
                    bao_api_error('Unknown page', 404);
                }
                bao_api_json($payload);
                return;
            }
            $overrides = [];
            if (isset($_GET['limit'])) {
                $overrides['limit'] = (int) $_GET['limit'];
            }
            if (isset($_GET['date'])) {
                $overrides['date'] = (string) $_GET['date'];
            }
            if (isset($_GET['start_index'])) {
                $overrides['start_index'] = (int) $_GET['start_index'];
            }
            if (isset($_GET['end_index'])) {
                $overrides['end_index'] = (int) $_GET['end_index'];
            }
            $api = new \App\Services\PageApiService();
            $payload = $api->payload($pageKey, $overrides);
            if (($payload['source'] ?? '') !== 'jackpot_hub'
                && ($_GET['format'] ?? '') === 'html'
                && !empty($payload['games'])
                && is_array($payload['games'])) {
                require_once __DIR__ . '/../components/match-cards.php';
                $showDate = isset($_GET['show_date']) && (string) $_GET['show_date'] === '1';
                if (!$showDate) {
                    $showDate = bao_games_span_days($payload['games']);
                }
                $html = '';
                foreach ($payload['games'] as $g) {
                    if (!is_array($g)) {
                        continue;
                    }
                    $html .= bao_match_card($g + ['_show_date' => $showDate]);
                }
                $payload['html'] = $html;
            }
            bao_api_json($payload);
        } catch (Throwable $e) {
            bao_api_error($e->getMessage(), 500);
        }
    });
}

$router->get('/', function () {
    include __DIR__ . '/../pages/homepage.php';
});

$router->get('/sitemap.xml', function () {
    include __DIR__ . '/../pages/sitemap.php';
});

$router->get('/football-predictions-today', function () {
    include __DIR__ . '/../pages/football-predictions-today.php';
});

$router->get('/football-predictions-tomorrow', function () {
    include __DIR__ . '/../pages/football-predictions-tomorrow.php';
});

$router->get('/football-predictions-yesterday', function () {
    include __DIR__ . '/../pages/football-predictions-yesterday.php';
});

$router->get('/weekend-football-predictions', function () {
    include __DIR__ . '/../pages/weekend-football-predictions.php';
});

$router->get('/must-win-teams-today', function () {
    include __DIR__ . '/../pages/must-win-teams-today.php';
});

$router->get('/sure-bets-today', function () {
    include __DIR__ . '/../pages/sure-bets-today.php';
});

$router->get('/betnumbers-tips', function () {
    include __DIR__ . '/../pages/betnumbers-tips.php';
});

$router->get('/accumulator-tips', function () {
    include __DIR__ . '/../pages/accumulator-tips.php';
});

$router->get('/1x2-predictions', function () {
    include __DIR__ . '/../pages/1x2-predictions.php';
});

$router->get('/live-football-predictions', function () {
    include __DIR__ . '/../pages/live-football-predictions.php';
});

$router->get('/double-chance-predictions', function () {
    include __DIR__ . '/../pages/double-chance-predictions.php';
});

$router->get('/over-under-predictions', function () {
    include __DIR__ . '/../pages/over-under-predictions.php';
});

$router->get('/btts-predictions', function () {
    include __DIR__ . '/../pages/btts-predictions.php';
});

$router->get('/correct-score-predictions', function () {
    header('Location: /1x2-predictions', true, 301);
    exit;
});

$router->get('/ht-ft-predictions', function () {
    include __DIR__ . '/../pages/ht-ft-predictions.php';
});

$router->get('/jackpot-predictions', function () {
    include __DIR__ . '/../pages/jackpot-predictions.php';
});

$router->get('/jackpot-predictions/', function () {
    header('Location: /jackpot-predictions', true, 301);
    exit;
});

$jackpotPages = [
    'sportpesa-mega-jackpot-predictions',
    'sportpesa-midweek-jackpot-predictions',
    'betika-midweek-jackpot-predictions',
    'sportybet-daily-jackpot-predictions',
    'odibets-laki-tatu-predictions',
    'mozzart-super-daily-jackpot-predictions',
];
foreach ($jackpotPages as $jackpotSlug) {
    $router->get('/jackpots/' . $jackpotSlug, function () use ($jackpotSlug) {
        include __DIR__ . '/../pages/' . $jackpotSlug . '.php';
    });
    // Legacy flat URLs → nested /jackpots/{slug}
    $router->get('/' . $jackpotSlug, function () use ($jackpotSlug) {
        header('Location: /jackpots/' . $jackpotSlug, true, 301);
        exit;
    });
    // Legacy hub-nested URLs → /jackpots/{slug}
    $router->get('/jackpot-predictions/' . $jackpotSlug, function () use ($jackpotSlug) {
        header('Location: /jackpots/' . $jackpotSlug, true, 301);
        exit;
    });
}

$router->get('/how-we-predict', function () {
    include __DIR__ . '/../pages/how-we-predict.php';
});

$router->get('/sunpel-prediction', function () {
    include __DIR__ . '/../pages/sunpel-prediction.php';
});

$router->get('/results', function () {
    include __DIR__ . '/../pages/results.php';
});

$router->get('/blog', function () {
    include __DIR__ . '/../pages/blog.php';
});

$router->get('/blog/{slug}', function ($slug) {
    include __DIR__ . '/../pages/blog-post.php';
});

// —— Admin blog cache clear + footer sponsors (pitchpredictionsadmin) ——
$router->get('/api/blog-list', function () {
    require_once __DIR__ . '/../config/load-env.php';
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $category = (string) ($_GET['category'] ?? 'ALL');
    $payload = (new \App\Services\BlogService())->list($page, $category, 6);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: public, max-age=60, must-revalidate');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});

$router->get('/api/clear-blog-list-cache', function () {
    require_once __DIR__ . '/../config/load-env.php';
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    require_once __DIR__ . '/../components/blog-cache-auth.php';
    header('Content-Type: application/json; charset=utf-8');
    if (! bao_blog_cache_clear_key()) {
        http_response_code(503);
        echo json_encode(['error' => 'BLOG_CACHE_CLEAR_KEY must be set to exactly ' . BAO_BLOG_CACHE_CLEAR_KEY_LENGTH . ' characters']);
        return;
    }
    if (! bao_blog_cache_clear_authorized()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }
    $cleared = (new \App\Services\BlogService())->clearListCaches();
    echo json_encode($cleared + [
        'revalidated' => true,
        'message' => 'Blog list caches cleared. The next visit will fetch fresh posts.',
    ], JSON_UNESCAPED_SLASHES);
});

$router->get('/api/clear-blog-cache/{slug}', function ($slug) {
    require_once __DIR__ . '/../config/load-env.php';
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    require_once __DIR__ . '/../components/blog-cache-auth.php';
    header('Content-Type: application/json; charset=utf-8');
    if (! bao_blog_cache_clear_key()) {
        http_response_code(503);
        echo json_encode(['error' => 'BLOG_CACHE_CLEAR_KEY must be set to exactly ' . BAO_BLOG_CACHE_CLEAR_KEY_LENGTH . ' characters']);
        return;
    }
    if (! bao_blog_cache_clear_authorized()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }
    $slug = trim(rawurldecode((string) $slug));
    if ($slug === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Slug is required']);
        return;
    }
    $cleared = (new \App\Services\BlogService())->clearPostCache($slug);
    echo json_encode($cleared + [
        'revalidated' => true,
        'message' => 'Blog cache cleared. The next visit will fetch a fresh post.',
    ], JSON_UNESCAPED_SLASHES);
});

$handleFooterSponsors = function () {
    require_once __DIR__ . '/../config/load-env.php';
    require_once __DIR__ . '/../src/Api/bootstrap.php';
    require_once __DIR__ . '/../components/blog-cache-auth.php';
    header('Content-Type: application/json; charset=utf-8');
    $service = new \App\Services\FooterSponsorsService();
    $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

    if ($method === 'GET') {
        $wantAll = in_array(strtolower((string) ($_GET['all'] ?? '')), ['1', 'true'], true);
        if ($wantAll) {
            header('Cache-Control: no-store, no-cache, must-revalidate');
            echo json_encode([
                'success' => true,
                'data' => $service->readDocument(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            return;
        }
        $document = $service->readDocument();
        $links = $service->visibleLinks();
        header('Cache-Control: public, max-age=0, s-maxage=0, must-revalidate');
        echo json_encode([
            'success' => true,
            'updated_at' => $document['updated_at'],
            'links' => $links,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return;
    }

    if ($method === 'PUT' || $method === 'POST') {
        if (! bao_blog_cache_clear_key()) {
            http_response_code(503);
            echo json_encode([
                'error' => 'BLOG_CACHE_CLEAR_KEY must be set to exactly ' . BAO_BLOG_CACHE_CLEAR_KEY_LENGTH . ' characters on this host before footer links can be saved.',
            ]);
            return;
        }
        if (! bao_blog_cache_clear_authorized()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $rawBody = file_get_contents('php://input');
        $body = json_decode((string) $rawBody, true);
        if (! is_array($body)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Invalid JSON body.']);
            return;
        }
        $incoming = (isset($body['data']) && is_array($body['data'])) ? $body['data'] : $body;
        if (! isset($incoming['links']) || ! is_array($incoming['links'])) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => 'Body must include a links array.']);
            return;
        }

        try {
            $saved = $service->writeDocument($incoming);
            header('Cache-Control: no-store, no-cache, must-revalidate');
            echo json_encode([
                'success' => true,
                'message' => 'Footer sponsor links saved.',
                'data' => $saved,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage() ?: 'Failed to write footer-sponsors.json',
            ]);
        }
        return;
    }

    http_response_code(405);
    header('Allow: GET, PUT, POST');
    echo json_encode(['error' => 'Method not allowed']);
};

$router->get('/api/site-content/footer-sponsors', $handleFooterSponsors);
$router->put('/api/site-content/footer-sponsors', $handleFooterSponsors);
$router->post('/api/site-content/footer-sponsors', $handleFooterSponsors);

$router->get('/how-to-read-btts-odds', function () {
    include __DIR__ . '/../pages/how-to-read-btts-odds.php';
});

$router->get('/mega-jackpot-strategy-guide', function () {
    include __DIR__ . '/../pages/mega-jackpot-strategy-guide.php';
});

$router->get('/premier-league-form-guide-matchweek-4', function () {
    include __DIR__ . '/../pages/premier-league-form-guide-matchweek-4.php';
});

$router->get('/about-us', function () {
    include __DIR__ . '/../pages/about-us.php';
});

$router->get('/faq', function () {
    include __DIR__ . '/../pages/faq.php';
});

$router->get('/contact-us', function () {
    include __DIR__ . '/../pages/contact-us.php';
});
$router->post('/contact-us', function () {
    include __DIR__ . '/../pages/contact-us.php';
});

$router->get('/responsible-betting', function () {
    include __DIR__ . '/../pages/responsible-betting.php';
});

$router->get('/privacy-policy', function () {
    include __DIR__ . '/../pages/privacy-policy.php';
});

$router->get('/terms-of-service', function () {
    include __DIR__ . '/../pages/terms-of-service.php';
});

$router->handleRequest();
