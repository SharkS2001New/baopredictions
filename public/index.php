<?php
/**
 * Front controller — same pattern as betsassurednewwebsite.
 *
 * Local:  php -S localhost:5000 -t public public/index.php
 * Apache: DocumentRoot → public/ (see .htaccess)
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Let the built-in server serve real static files from /public
if ($uri !== '/' && $uri !== '' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

require_once __DIR__ . '/../src/Facades/Router.php';

use App\Facades\Router;

$router = new Router();

// —— JSON API ——
$router->get('/api/health', function () {
    require_once __DIR__ . '/../src/Api/bootstrap.php';
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
            'time' => date('c'),
        ]);
    } catch (Throwable $e) {
        bao_api_error('db down: ' . $e->getMessage(), 503);
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
            $hasOverrides = isset($_GET['limit']) || isset($_GET['date']);
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
            $api = new \App\Services\PageApiService();
            bao_api_json($api->payload($pageKey, $overrides));
        } catch (Throwable $e) {
            bao_api_error($e->getMessage(), 500);
        }
    });
}

$router->get('/', function () {
    include __DIR__ . '/../pages/homepage.php';
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

$router->get('/sportpesa-mega-jackpot-predictions', function () {
    include __DIR__ . '/../pages/sportpesa-mega-jackpot-predictions.php';
});

$router->get('/sportpesa-midweek-jackpot-predictions', function () {
    include __DIR__ . '/../pages/sportpesa-midweek-jackpot-predictions.php';
});

$router->get('/betika-midweek-jackpot-predictions', function () {
    include __DIR__ . '/../pages/betika-midweek-jackpot-predictions.php';
});

$router->get('/sportybet-daily-jackpot-predictions', function () {
    include __DIR__ . '/../pages/sportybet-daily-jackpot-predictions.php';
});

$router->get('/odibets-laki-tatu-predictions', function () {
    include __DIR__ . '/../pages/odibets-laki-tatu-predictions.php';
});

$router->get('/how-we-predict', function () {
    include __DIR__ . '/../pages/how-we-predict.php';
});

$router->get('/results', function () {
    include __DIR__ . '/../pages/results.php';
});

$router->get('/2026-09-03', function () {
    include __DIR__ . '/../pages/2026-09-03.php';
});

$router->get('/blog', function () {
    include __DIR__ . '/../pages/blog.php';
});

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
