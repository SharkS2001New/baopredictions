<?php
namespace App\Services;

use App\Database;
use App\Support\DateTimeHelper;
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

        // One shared today pool for hero tip count + sidebar market counts (avoids duplicate listGames).
        $todayPool = $this->listPageGames('football-predictions-today', [
            'day' => 'today',
            'limit' => 60,
            'market' => '1x2',
            'order' => 'kickoff_asc',
        ]);

        $track = $this->trackRecord(90, 800);
        $today = $this->todayPerformanceFromPool($todayPool);
        $yesterday = $this->yesterdayPerformance();
        $recent = $this->recentPerformance(3);
        $markets = $this->marketCountsFromPool($todayPool);

        // Hero accuracy + streak use last 3 days (larger settled sample).
        $today['accuracy'] = $recent['accuracy'];
        $today['win_rate'] = $recent['accuracy'];
        $today['accuracy_window_days'] = 3;
        $today['win_streak'] = $recent['win_streak'];

        $payload = [
            'ok' => true,
            'last_updated' => date('c'),
            'win_rate' => $track['win_rate'],
            'roi' => $track['roi'],
            'settled_tips' => $track['settled_tips'],
            'win_streak' => $recent['win_streak'],
            'track' => $track,
            'today' => $today,
            'yesterday' => $yesterday,
            'recent' => $recent,
            'markets' => $markets,
        ];

        date_default_timezone_set($prevTz);
        return $payload;
    }

    /**
     * Settled published tips over the last N Nairobi calendar days.
     * Uses one settled SQL query (not N× listGames).
     *
     * @return array<string,mixed>
     */
    public function recentPerformance(int $days = 3): array
    {
        $days = max(1, min(14, $days));
        $settled = $this->fetchSettled1x2($days, 400);

        // Newest kickoff first → current streak ends on the latest settled tip.
        usort($settled, static function (array $a, array $b): int {
            $ka = (string) ($a['kickoff'] ?? '');
            $kb = (string) ($b['kickoff'] ?? '');
            if ($ka !== $kb) {
                return $kb <=> $ka;
            }
            return 0;
        });

        $streak = 0;
        foreach ($settled as $g) {
            if (($g['won'] ?? null) === true) {
                $streak++;
            } else {
                break;
            }
        }

        $bestStreak = 0;
        $run = 0;
        $chrono = $settled;
        usort($chrono, static function (array $a, array $b): int {
            return ((string) ($a['kickoff'] ?? '')) <=> ((string) ($b['kickoff'] ?? ''));
        });
        foreach ($chrono as $g) {
            if (($g['won'] ?? null) === true) {
                $run++;
                $bestStreak = max($bestStreak, $run);
            } else {
                $run = 0;
            }
        }

        $wins = 0;
        foreach ($settled as $g) {
            if (($g['won'] ?? null) === true) {
                $wins++;
            }
        }
        $total = count($settled);

        return [
            'window_days' => $days,
            'settled_total' => $total,
            'settled_won' => $wins,
            'win_streak' => $bestStreak,
            'current_streak' => $streak,
            'best_streak' => $bestStreak,
            'accuracy' => $total > 0 ? round(100 * $wins / $total, 1) : null,
        ];
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
        // Cap the public sample so the strip stays readable/stable.
        if (count($rows) > 800) {
            $rows = array_slice($rows, 0, 800);
        }
        return $this->summarizeRows($rows);
    }

    /**
     * Today's settled + open board performance (1X2).
     *
     * @return array<string,mixed>
     */
    public function todayPerformance(): array
    {
        $todayPool = $this->listPageGames('football-predictions-today', [
            'day' => 'today',
            'limit' => 60,
            'market' => '1x2',
            'order' => 'kickoff_asc',
        ]);
        return $this->todayPerformanceFromPool($todayPool);
    }

    /**
     * @param list<array<string,mixed>> $todayPool
     * @return array<string,mixed>
     */
    private function todayPerformanceFromPool(array $todayPool): array
    {
        $today = date('Y-m-d');
        $predictionsToday = count($todayPool);

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
        return $this->marketCountsFromPool($pool);
    }

    /**
     * @param list<array<string,mixed>> $pool
     * @return array<string,int>
     */
    private function marketCountsFromPool(array $pool): array
    {
        $n = count($pool);

        $must = 0;
        $sure = 0;
        $accaEligible = 0;
        foreach ($pool as $g) {
            $c = (int) ($g['confidence'] ?? 0);
            if ($c >= 75) {
                $must++;
            }
            if ($c >= 70) {
                $sure++;
            }
            // Same usable band as buildAccumulators (odds + confidence proxy).
            $odds = isset($g['odds']) ? (float) $g['odds'] : 0;
            if ($c >= 58 && $odds >= 1.20 && $odds <= 3.50 && !empty($g['pick'])) {
                $accaEligible++;
            }
        }

        // Cheap ticket estimate: up to three tickets (3/5/8) when pool is deep enough.
        $acc = 0;
        if ($accaEligible >= 3) {
            $acc++;
        }
        if ($accaEligible >= 8) {
            $acc++;
        }
        if ($accaEligible >= 16) {
            $acc++;
        }

        return [
            '1x2-predictions' => min(60, $n),
            'over-under-predictions' => min(60, $n),
            'btts-predictions' => min(60, $n),
            'double-chance-predictions' => min(60, $n),
            'ht-ft-predictions' => min(40, $n),
            'live-football-predictions' => min(80, $this->countLiveFixturesToday()),
            'must-win-teams-today' => min(30, $must),
            'sure-bets-today' => min(30, $sure),
            'betnumbers-tips' => min(40, $n),
            'accumulator-tips' => $acc,
        ];
    }

    /**
     * @deprecated Prefer todayPerformanceFromPool — kept for callers.
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
     * Cheap COUNT for sidebar live badge (avoids full listGames mapping).
     */
    private function countLiveFixturesToday(): int
    {
        $today = DateTimeHelper::siteToday();
        $fromLocal = (new \DateTimeImmutable($today . ' 00:00:00', new \DateTimeZone(DateTimeHelper::SITE_TZ)))
            ->modify('-1 day');
        $toLocalExclusive = (new \DateTimeImmutable($today . ' 00:00:00', new \DateTimeZone(DateTimeHelper::SITE_TZ)))
            ->modify('+2 day');
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM fixtures f
             WHERE f.date >= :date_from AND f.date < :date_to
               AND UPPER(TRIM(f.status_short)) IN ('1H','2H','HT','ET','BT','P','LIVE','INT','BREAK','SUSP')"
        );
        $stmt->execute([
            ':date_from' => $fromLocal->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            ':date_to' => $toLocalExclusive->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
        ]);
        return (int) $stmt->fetchColumn();
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
  (
    SELECT MAX(o2.bets_home) FROM odds o2
    WHERE o2.fixture_id = f.fixture_id AND o2.bets_home > 1.01
  ) AS best_home,
  (
    SELECT MAX(o2.bets_draw) FROM odds o2
    WHERE o2.fixture_id = f.fixture_id AND o2.bets_draw > 1.01
  ) AS best_draw,
  (
    SELECT MAX(o2.bets_away) FROM odds o2
    WHERE o2.fixture_id = f.fixture_id AND o2.bets_away > 1.01
  ) AS best_away
FROM fixtures f
INNER JOIN predictions_computation pc ON pc.fixture_id = f.fixture_id
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
  (
    SELECT MAX(o2.bets_home) FROM odds o2
    WHERE o2.fixture_id = f.fixture_id AND o2.bets_home > 1.01
  ) AS best_home,
  (
    SELECT MAX(o2.bets_draw) FROM odds o2
    WHERE o2.fixture_id = f.fixture_id AND o2.bets_draw > 1.01
  ) AS best_draw,
  (
    SELECT MAX(o2.bets_away) FROM odds o2
    WHERE o2.fixture_id = f.fixture_id AND o2.bets_away > 1.01
  ) AS best_away
FROM fixtures f
INNER JOIN predictions_computation pc ON pc.fixture_id = f.fixture_id
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
            if ($code === '') {
                continue;
            }
            $odds = $this->pickOdds($row, $code);
            // Never invent odds — synthetic 1.85 was dragging ROI negative on losses.
            if ($odds <= 1.01) {
                continue;
            }
            $gh = (int) $row['goals_home'];
            $ga = (int) $row['goals_away'];
            $actual = $gh > $ga ? '1' : ($gh < $ga ? '2' : 'X');
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
        $streak = 0;
        $streaking = true;

        foreach ($rows as $r) {
            $odd = (float) $r['odds'];
            $oddsSum += $odd;
            if ($r['won']) {
                $wins++;
                // Flat 1u stake: win returns (odds - 1) profit; loss loses the 1u stake.
                $units += ($odd - 1.0);
                if ($streaking) {
                    $streak++;
                }
            } else {
                $units -= 1.0;
                $streaking = false;
            }
        }

        $winRate = $n > 0 ? round(100 * $wins / $n, 1) : 0.0;
        // ROI% = net profit / total stakes (1u × settled tips)
        $roi = $n > 0 ? round(100 * $units / $n, 1) : 0.0;
        $avgOdds = $n > 0 ? round($oddsSum / $n, 2) : null;

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
        // Skip coin-flip leans — they are not published tips and inflate losing ROI.
        if ($max < 55) {
            return '';
        }
        if ($h === $max) {
            return '1';
        }
        if ($a === $max) {
            return '2';
        }
        return 'X';
    }

    /**
     * Best available decimal odds for the tipped 1X2 outcome.
     *
     * @param array<string,mixed> $row
     */
    private function pickOdds(array $row, string $code): float
    {
        $primary = match ($code) {
            '2' => $row['best_away'] ?? $row['bets_away'] ?? null,
            'X' => $row['best_draw'] ?? $row['bets_draw'] ?? null,
            default => $row['best_home'] ?? $row['bets_home'] ?? null,
        };
        $odd = is_numeric($primary) ? (float) $primary : 0.0;
        return $odd > 1.01 ? $odd : 0.0;
    }
}
