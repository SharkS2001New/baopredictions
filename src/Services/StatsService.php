<?php
namespace App\Services;

use App\Database;
use PDO;

/**
 * Live track-record + sidebar market counts (no cooked numbers).
 */
final class StatsService
{
    private PDO $db;
    private GamesService $games;

    public function __construct(?PDO $db = null, ?GamesService $games = null)
    {
        $this->db = $db ?: Database::connection();
        $this->games = $games ?: new GamesService();
    }

    /**
     * Full payload for GET /api/stats
     *
     * @return array<string,mixed>
     */
    public function payload(): array
    {
        $prevTz = date_default_timezone_get();
        date_default_timezone_set('Africa/Nairobi');

        $track = $this->trackRecord(90, 800);
        $today = $this->todayPerformance();
        $yesterday = $this->yesterdayPerformance();
        $markets = $this->marketCounts();

        $payload = [
            'ok' => true,
            'last_updated' => date('c'),
            'win_rate' => $track['win_rate'],
            'roi' => $track['roi'],
            'settled_tips' => $track['settled_tips'],
            // Hero "Current win streak" = consecutive wins from yesterday's published tips.
            'win_streak' => $yesterday['win_streak'],
            'track' => $track,
            'today' => $today,
            'yesterday' => $yesterday,
            'markets' => $markets,
        ];

        date_default_timezone_set($prevTz);
        return $payload;
    }

    /**
     * Yesterday's published tips (same board as /football-predictions-yesterday).
     *
     * @return array<string,mixed>
     */
    public function yesterdayPerformance(): array
    {
        $games = $this->listPageGames('football-predictions-yesterday', [
            'day' => 'yesterday',
            'limit' => 80,
            'market' => '1x2',
            'status' => 'FT',
            'order' => 'kickoff_asc',
        ]);

        $settled = [];
        foreach ($games as $g) {
            if ($g['won'] === null) {
                continue;
            }
            $settled[] = $g;
        }

        // Newest kickoff first → streak is consecutive wins ending with the latest tip.
        usort($settled, static function (array $a, array $b): int {
            $ka = (string) ($a['kickoff'] ?? '');
            $kb = (string) ($b['kickoff'] ?? '');
            if ($ka !== $kb) {
                return $kb <=> $ka;
            }
            return ((int) ($b['fixture_id'] ?? 0)) <=> ((int) ($a['fixture_id'] ?? 0));
        });

        $streak = 0;
        foreach ($settled as $g) {
            if ($g['won'] === true) {
                $streak++;
            } else {
                break;
            }
        }

        $wins = 0;
        foreach ($settled as $g) {
            if ($g['won'] === true) {
                $wins++;
            }
        }
        $total = count($settled);

        return [
            'date' => $this->games->resolveDate(['day' => 'yesterday']),
            'predictions' => count($games),
            'settled_total' => $total,
            'settled_won' => $wins,
            'win_streak' => $streak,
            'accuracy' => $total > 0 ? round(100 * $wins / $total, 1) : null,
        ];
    }

    /**
     * Rolling 1X2 track record from settled FT fixtures with model probs.
     *
     * @return array<string,mixed>
     */
    public function trackRecord(int $lookbackDays = 90, int $limit = 2000): array
    {
        $rows = $this->fetchSettled1x2($lookbackDays, $limit);
        return $this->summarizeRows($rows);
    }

    /**
     * Today's settled + open board performance (1X2).
     *
     * @return array<string,mixed>
     */
    public function todayPerformance(): array
    {
        $today = date('Y-m-d');
        // Same game list the Today page API returns — not every DB fixture with a model row.
        $predictionsToday = $this->countDisplayedTodayTips();

        $settledRows = $this->fetchSettled1x2ForDate($today);
        $summary = $this->summarizeRows($settledRows);

        $settledTotal = $summary['settled_tips'];
        $settledWon = $summary['wins'];
        $accuracy = $settledTotal > 0
            ? round(100 * $settledWon / $settledTotal, 1)
            : null;

        return [
            'date' => $today,
            'predictions' => $predictionsToday,
            'accuracy' => $accuracy,
            'settled_won' => $settledWon,
            'settled_total' => $settledTotal,
            'settled_display' => $settledTotal > 0
                ? ($settledWon . '/' . $settledTotal)
                : '0/0',
            'win_rate' => $accuracy,
            'units' => $summary['units'],
            'avg_odds' => $summary['avg_odds'],
            'win_streak' => $summary['win_streak'],
        ];
    }

    /**
     * Sidebar market tip counts — one shared today pool (same filters as page APIs).
     *
     * @return array<string,int>
     */
    public function marketCounts(): array
    {
        $pool = $this->games->listGames([
            'day' => 'today',
            'limit' => 80,
            'market' => '1x2',
            'order' => 'confidence_desc',
        ]);
        $n = count($pool);

        $must = 0;
        $sure = 0;
        foreach ($pool as $g) {
            $c = (int) ($g['confidence'] ?? 0);
            if ($c >= 75) {
                $must++;
            }
            if ($c >= 70) {
                $sure++;
            }
        }

        $accPool = array_values(array_filter(
            $pool,
            static fn(array $g): bool => (int) ($g['confidence'] ?? 0) >= 70
        ));
        $acc = count($this->games->buildAccumulators(array_slice($accPool, 0, 24)));

        return [
            '1x2-predictions' => min(60, $n),
            'over-under-predictions' => min(60, $n),
            'btts-predictions' => min(60, $n),
            'double-chance-predictions' => min(60, $n),
            'ht-ft-predictions' => min(40, $n),
            'live-football-predictions' => count($this->games->listGames([
                'day' => 'today',
                'limit' => 80,
                'market' => 'best',
                'live_only' => true,
                'order' => 'kickoff_asc',
            ])),
            'must-win-teams-today' => min(30, $must),
            'sure-bets-today' => min(30, $sure),
            'betnumbers-tips' => min(40, $n),
            'accumulator-tips' => $acc,
        ];
    }

    /**
     * Tips shown on the Today page (/football-predictions-today) — same filters as that API.
     */
    private function countDisplayedTodayTips(): int
    {
        return count($this->listPageGames('football-predictions-today', [
            'day' => 'today',
            'limit' => 60,
            'market' => '1x2',
            'order' => 'kickoff_asc',
        ]));
    }

    /**
     * @param array<string,mixed> $fallback
     * @return list<array<string,mixed>>
     */
    private function listPageGames(string $pageKey, array $fallback): array
    {
        $pages = require dirname(__DIR__, 2) . '/config/api-pages.php';
        $def = is_array($pages[$pageKey] ?? null) ? $pages[$pageKey] : $fallback;
        $filters = $def;
        unset($filters['title'], $filters['extra']);

        return $this->games->listGames($filters);
    }

    /**
     * @return list<array{won:bool,odds:float,kickoff:string}>
     */
    private function fetchSettled1x2(int $lookbackDays, int $limit): array
    {
        $lookbackDays = max(1, min(365, $lookbackDays));
        $limit = max(50, min(5000, $limit));

        $sql = <<<SQL
SELECT
  f.date AS kickoff,
  f.goals_home,
  f.goals_away,
  pc.percent_pred_home,
  pc.percent_pred_draw,
  pc.percent_pred_away,
  o.bets_home,
  o.bets_draw,
  o.bets_away
FROM fixtures f
INNER JOIN predictions_computation pc ON pc.fixture_id = f.fixture_id
LEFT JOIN odds o ON o.id = (
  SELECT o2.id FROM odds o2
  WHERE o2.fixture_id = f.fixture_id
  ORDER BY
    CASE o2.bookmaker_name
      WHEN 'Bet365' THEN 0
      WHEN '10Bet' THEN 1
      WHEN 'William Hill' THEN 2
      ELSE 9
    END,
    o2.id ASC
  LIMIT 1
)
WHERE f.status_short = 'FT'
  AND f.goals_home IS NOT NULL
  AND f.goals_away IS NOT NULL
  AND DATE(f.date) >= DATE_SUB(CURDATE(), INTERVAL {$lookbackDays} DAY)
  AND (
    pc.percent_pred_home IS NOT NULL
    OR pc.percent_pred_draw IS NOT NULL
    OR pc.percent_pred_away IS NOT NULL
  )
ORDER BY f.date DESC, f.fixture_id DESC
LIMIT {$limit}
SQL;

        $stmt = $this->db->query($sql);
        $rows = $stmt ? $stmt->fetchAll() : [];
        return $this->mapSettledRows($rows);
    }

    /**
     * @return list<array{won:bool,odds:float,kickoff:string}>
     */
    private function fetchSettled1x2ForDate(string $date): array
    {
        $sql = <<<SQL
SELECT
  f.date AS kickoff,
  f.goals_home,
  f.goals_away,
  pc.percent_pred_home,
  pc.percent_pred_draw,
  pc.percent_pred_away,
  o.bets_home,
  o.bets_draw,
  o.bets_away
FROM fixtures f
INNER JOIN predictions_computation pc ON pc.fixture_id = f.fixture_id
LEFT JOIN odds o ON o.id = (
  SELECT o2.id FROM odds o2
  WHERE o2.fixture_id = f.fixture_id
  ORDER BY
    CASE o2.bookmaker_name
      WHEN 'Bet365' THEN 0
      WHEN '10Bet' THEN 1
      WHEN 'William Hill' THEN 2
      ELSE 9
    END,
    o2.id ASC
  LIMIT 1
)
WHERE f.status_short = 'FT'
  AND f.goals_home IS NOT NULL
  AND f.goals_away IS NOT NULL
  AND DATE(f.date) = :d
  AND (
    pc.percent_pred_home IS NOT NULL
    OR pc.percent_pred_draw IS NOT NULL
    OR pc.percent_pred_away IS NOT NULL
  )
ORDER BY f.date DESC, f.fixture_id DESC
LIMIT 500
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':d' => $date]);
        return $this->mapSettledRows($stmt->fetchAll());
    }

    /**
     * @param list<array<string,mixed>> $rows
     * @return list<array{won:bool,odds:float,kickoff:string}>
     */
    private function mapSettledRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $code = $this->pick1x2Code($row);
            $gh = (int) $row['goals_home'];
            $ga = (int) $row['goals_away'];
            $actual = $gh > $ga ? '1' : ($gh < $ga ? '2' : 'X');
            $odds = $this->pickOdds($row, $code);
            $out[] = [
                'won' => $code === $actual,
                'odds' => $odds,
                'kickoff' => (string) ($row['kickoff'] ?? ''),
            ];
        }
        return $out;
    }

    /**
     * @param list<array{won:bool,odds:float,kickoff:string}> $rows newest-first
     * @return array<string,mixed>
     */
    private function summarizeRows(array $rows): array
    {
        $n = count($rows);
        $wins = 0;
        $units = 0.0;
        $oddsSum = 0.0;
        $oddsN = 0;
        $streak = 0;
        $streaking = true;

        foreach ($rows as $r) {
            $odd = (float) $r['odds'];
            if ($odd > 1.01) {
                $oddsSum += $odd;
                $oddsN++;
            }
            if ($r['won']) {
                $wins++;
                $units += ($odd > 1.01 ? $odd - 1.0 : 0.85);
                if ($streaking) {
                    $streak++;
                }
            } else {
                $units -= 1.0;
                $streaking = false;
            }
        }

        $winRate = $n > 0 ? round(100 * $wins / $n, 1) : 0.0;
        $roi = $n > 0 ? round(100 * $units / $n, 1) : 0.0;
        $avgOdds = $oddsN > 0 ? round($oddsSum / $oddsN, 2) : null;

        return [
            'settled_tips' => $n,
            'wins' => $wins,
            'losses' => max(0, $n - $wins),
            'win_rate' => $winRate,
            'roi' => $roi,
            'units' => round($units, 1),
            'avg_odds' => $avgOdds,
            'win_streak' => $streak,
        ];
    }

    /** @param array<string,mixed> $row */
    private function pick1x2Code(array $row): string
    {
        $h = (int) ($row['percent_pred_home'] ?? 0);
        $d = (int) ($row['percent_pred_draw'] ?? 0);
        $a = (int) ($row['percent_pred_away'] ?? 0);
        $max = max($h, $d, $a);
        if ($max <= 0 || $h === $max) {
            return '1';
        }
        if ($a === $max) {
            return '2';
        }
        return 'X';
    }

    /** @param array<string,mixed> $row */
    private function pickOdds(array $row, string $code): float
    {
        $raw = match ($code) {
            '2' => $row['bets_away'] ?? null,
            'X' => $row['bets_draw'] ?? null,
            default => $row['bets_home'] ?? null,
        };
        $odd = is_numeric($raw) ? (float) $raw : 0.0;
        return $odd > 1.01 ? $odd : 1.85;
    }
}
