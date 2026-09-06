<?php
namespace App\Services;

use App\Database;
use App\Support\DateTimeHelper;
use PDO;

/**
 * Fetch games from pitchnewdb for page-specific APIs.
 * Schema: fixtures, leagues, predictions, predictions_computation, odds, pp_fixtures_selections
 */
final class GamesService
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?: Database::connection();
    }

    /**
     * @param array<string,mixed> $filters
     * @return list<array<string,mixed>>
     */
    public function listGames(array $filters = []): array
    {
        $source = strtolower((string) ($filters['source'] ?? 'fixtures'));
        if ($source === 'selections' || $source === 'tips') {
            return $this->listFromSelections($filters);
        }
        if ($source === 'jackpot_hub') {
            return $this->listJackpotHub($filters);
        }
        return $this->listFromFixtures($filters);
    }

    public function resolveDate(array $filters): string
    {
        $date = trim((string) ($filters['date'] ?? ''));
        if ($date !== '') {
            return $date;
        }
        $day = strtolower((string) ($filters['day'] ?? 'today'));
        return match ($day) {
            'tomorrow' => DateTimeHelper::siteDate('tomorrow'),
            'yesterday' => DateTimeHelper::siteDate('yesterday'),
            default => DateTimeHelper::siteDate('today'),
        };
    }

    /**
     * @param array<string,mixed> $filters
     * @return array{from:string,to:string}
     */
    public function resolveDateRange(array $filters): array
    {
        $range = strtolower((string) ($filters['range'] ?? ''));
        if ($range === 'weekend') {
            return DateTimeHelper::siteWeekendRange();
        }

        $date = $this->resolveDate($filters);
        return ['from' => $date, 'to' => $date];
    }

    /**
     * @param array<string,mixed> $filters
     * @return list<array<string,mixed>>
     */
    public function listFromFixtures(array $filters = []): array
    {
        $range = $this->resolveDateRange($filters);
        $limit = max(1, min(200, (int) ($filters['limit'] ?? 50)));
        $leagueId = isset($filters['league_id']) ? (int) $filters['league_id'] : 0;
        $status = trim((string) ($filters['status'] ?? ''));
        $market = strtolower((string) ($filters['market'] ?? '1x2'));
        $minConf = isset($filters['min_confidence']) ? (int) $filters['min_confidence'] : 0;
        $order = (string) ($filters['order'] ?? 'kickoff_asc');

        $sql = <<<SQL
SELECT
  f.fixture_id,
  f.league_id,
  f.home_team_name,
  f.away_team_name,
  f.home_team_logo,
  f.away_team_logo,
  f.date AS kickoff,
  f.timezone,
  f.status_short,
  f.status_long,
  f.goals_home,
  f.goals_away,
  f.ht_goals_home,
  f.ht_goals_away,
  f.option_picked,
  f.prediction_type,
  l.league_name,
  l.country_name,
  COALESCE(l.popular_status, 0) AS popular_status,
  pc.percent_pred_home,
  pc.percent_pred_draw,
  pc.percent_pred_away,
  pc.hf_percent_pred_home,
  pc.hf_percent_pred_draw,
  pc.hf_percent_pred_away,
  pc.both_team_to_score,
  pc.both_teams_percentage_prob,
  pc.pred_group,
  pc.active_status,
  p.advice,
  p.winner_team_name,
  p.under_over,
  p.win_or_draw,
  p.avg_goals,
  o.bookmaker_name,
  o.bets_home,
  o.bets_draw,
  o.bets_away,
  o.over_2_5,
  o.under_2_5,
  o.both_teams_to_score_yes,
  o.both_teams_to_score_no,
  o.double_chance_home_draw,
  o.double_chance_draw_away,
  o.double_chance_home_away,
  o.cs_best_score_1,
  o.cs_best_odd_1,
  o.ht_home,
  o.ht_draw,
  o.ht_away
FROM fixtures f
LEFT JOIN leagues l ON l.league_id = f.league_id
LEFT JOIN predictions_computation pc ON pc.fixture_id = f.fixture_id
LEFT JOIN predictions p ON p.fixture_id = f.fixture_id
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
WHERE f.date >= :date_from AND f.date < :date_to
SQL;

        // Nairobi calendar day D → UTC [D 00:00 EAT, D+1 00:00 EAT) = [D-1 21:00 UTC, D 21:00 UTC).
        $fromLocal = new \DateTimeImmutable($range['from'] . ' 00:00:00', new \DateTimeZone(DateTimeHelper::SITE_TZ));
        $toLocalExclusive = (new \DateTimeImmutable($range['to'] . ' 00:00:00', new \DateTimeZone(DateTimeHelper::SITE_TZ)))
            ->modify('+1 day');
        // Live board: widen to yesterday→tomorrow so late/ET matches are not clipped by calendar day.
        if (!empty($filters['live_only'])) {
            $fromLocal = $fromLocal->modify('-1 day');
            $toLocalExclusive = $toLocalExclusive->modify('+1 day');
        }
        $params = [
            ':date_from' => $fromLocal->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            ':date_to' => $toLocalExclusive->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
        ];

        if ($leagueId > 0) {
            $sql .= ' AND f.league_id = :league_id';
            $params[':league_id'] = $leagueId;
        }
        if ($status !== '') {
            $sql .= ' AND f.status_short = :status';
            $params[':status'] = $status;
        }
        if (!empty($filters['live_only'])) {
            $sql .= " AND UPPER(TRIM(f.status_short)) IN ('1H','2H','HT','ET','BT','P','LIVE','INT','BREAK','SUSP')";
        }
        if (!empty($filters['upcoming_only'])) {
            $sql .= " AND f.status_short NOT IN ('FT','AET','PEN','PST','CANC','ABD','AWD','WO')";
        }

        // Prefer popular leagues in the fetch pool; final order still applied in sortGames.
        // Keep the SQL window proportional to the page limit (avoid mapping 200 rows for an 18-card board).
        $fetchLimit = min(500, max($limit * 3, $limit + 40));
        $sql .= ' ORDER BY COALESCE(l.popular_status, 0) DESC, f.date ASC, f.fixture_id ASC LIMIT ' . $fetchLimit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $game = $this->mapFixtureRow($row, $market);
            if ($minConf > 0 && (int) $game['confidence'] < $minConf) {
                continue;
            }
            // Accuracy gate for straight 1X2 pages: skip soft long-priced leans.
            if ($market === '1x2' || $market === '') {
                $odd = $this->oddFloat($game['odds'] ?? null);
                $conf = (int) ($game['confidence'] ?? 0);
                if ($odd !== null) {
                    if ($odd >= 3.0) {
                        continue;
                    }
                    if ($odd >= 2.60 && $conf < 55) {
                        continue;
                    }
                }
            }
            $out[] = $game;
        }

        $out = $this->sortGames($out, $order);
        return array_slice($out, 0, $limit);
    }

    /**
     * @param array<string,mixed> $filters
     * @return list<array<string,mixed>>
     */
    public function listFromSelections(array $filters = []): array
    {
        $limit = max(1, min(200, (int) ($filters['limit'] ?? 50)));
        $category = trim((string) ($filters['category'] ?? ''));
        $jackpot = trim((string) ($filters['jackpot'] ?? ''));
        $latestRound = !empty($filters['latest_round']);

        $params = [];
        $sql = <<<SQL
SELECT
  s.id,
  s.fixture_id,
  s.home_team_name,
  s.away_team_name,
  s.fixture_date AS kickoff,
  s.tip,
  s.revised_tip,
  s.odd,
  s.category,
  s.`group` AS tip_group,
  s.jackpot_name,
  s.jackpot_tips_id,
  s.jackpot_position,
  s.prediction_confidence,
  s.research_confidence,
  s.home_prob,
  s.draw_prob,
  s.away_prob,
  s.match_type,
  s.status,
  s.manual_status,
  f.home_team_logo,
  f.away_team_logo,
  f.status_short,
  f.status_long,
  f.goals_home,
  f.goals_away,
  f.timezone,
  l.league_name,
  l.country_name
FROM pp_fixtures_selections s
LEFT JOIN fixtures f ON f.fixture_id = CAST(s.fixture_id AS UNSIGNED)
LEFT JOIN leagues l ON l.league_id = f.league_id
WHERE s.status = 1
SQL;

        if ($jackpot !== '') {
            $sql .= ' AND s.jackpot_name = :jackpot';
            $params[':jackpot'] = $jackpot;
        }
        if ($category !== '') {
            $sql .= ' AND s.category = :category';
            $params[':category'] = $category;
        }

        if ($latestRound && $jackpot !== '') {
            $roundId = $this->latestJackpotTipsId($jackpot);
            if ($roundId !== null) {
                $sql .= ' AND s.jackpot_tips_id = :tips_id';
                $params[':tips_id'] = $roundId;
            } else {
                $sql .= ' AND s.fixture_date >= CURDATE() - INTERVAL 2 DAY';
            }
        } else {
            $range = $this->resolveDateRange($filters);
            $sql .= ' AND DATE(s.fixture_date) BETWEEN :date_from AND :date_to';
            $params[':date_from'] = $range['from'];
            $params[':date_to'] = $range['to'];
        }

        $sql .= ' ORDER BY s.jackpot_position ASC, s.fixture_date ASC, s.id ASC LIMIT ' . $limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $out[] = $this->mapSelectionRow($row);
        }
        return $out;
    }

    /**
     * Hub cards: latest round per major jackpot product.
     *
     * @param array<string,mixed> $filters
     * @return list<array<string,mixed>>
     */
    public function listJackpotHub(array $filters = []): array
    {
        $names = [
            'Sportpesa Mega Jackpot',
            'Sportpesa Midweek Jackpot',
            'Betika Midweek Jackpot',
            'Sporty bet Jackpot',
            'Odibet Laki Tatu Jackpot',
        ];

        $out = [];
        foreach ($names as $name) {
            $games = $this->listFromSelections([
                'jackpot' => $name,
                'latest_round' => true,
                'limit' => (int) ($filters['limit'] ?? 20),
            ]);
            $out[] = [
                'jackpot_name' => $name,
                'slug' => $this->jackpotSlug($name),
                'count' => count($games),
                'games' => $games,
            ];
        }
        return $out;
    }

    /**
     * Build 3/5/8-fold accumulator tickets from high-confidence games.
     * Prioritises main leagues and avoids repeating fixtures across tickets.
     *
     * @param list<array<string,mixed>> $games
     * @return list<array<string,mixed>>
     */
    public function buildAccumulators(array $games): array
    {
        $usable = array_values(array_filter($games, function ($g) {
            $odds = isset($g['odds']) ? (float) $g['odds'] : 0;
            if ($odds < 1.20 || $odds > 3.50 || empty($g['pick'])) {
                return false;
            }
            // Skip settled matches — accas are for upcoming / live tips
            $status = strtoupper((string) ($g['status'] ?? ''));
            if (in_array($status, ['FT', 'AET', 'PEN', 'PST', 'CANC', 'ABD', 'AWD', 'WO'], true)) {
                return false;
            }
            // Skip reserves / youth / most women's sides for published tickets
            if ($this->isLowTierFixture($g)) {
                return false;
            }
            return true;
        }));

        usort($usable, function (array $a, array $b): int {
            $pa = $this->leaguePriority($a);
            $pb = $this->leaguePriority($b);
            if ($pa !== $pb) {
                return $pb <=> $pa;
            }
            $ca = (int) ($a['confidence'] ?? 0);
            $cb = (int) ($b['confidence'] ?? 0);
            if ($ca !== $cb) {
                return $cb <=> $ca;
            }
            return ((float) ($a['odds'] ?? 99)) <=> ((float) ($b['odds'] ?? 99));
        });

        $pool = $usable;
        $tickets = [];
        foreach ([3, 5, 8] as $legs) {
            $ticket = $this->takeAccumulatorLegs($pool, $legs);
            if ($ticket === null) {
                continue;
            }
            [$picks, $pool] = $ticket;
            $combined = 1.0;
            $confSum = 0;
            foreach ($picks as $g) {
                $combined *= (float) $g['odds'];
                $confSum += (int) $g['confidence'];
            }
            $tickets[] = [
                'name' => $legs . '-Fold Acca',
                'legs' => $legs,
                'combined_odds' => round($combined, 2),
                'blended_confidence' => (int) round($confSum / $legs),
                'picks' => array_map(static function (array $g): array {
                    return [
                        'home' => $g['home'],
                        'away' => $g['away'],
                        'pick' => $g['pick'],
                        'odds' => $g['odds'],
                        'confidence' => $g['confidence'],
                        'league' => $g['league'] ?? '',
                        'fixture_id' => $g['fixture_id'] ?? null,
                    ];
                }, $picks),
            ];
        }
        return $tickets;
    }

    /**
     * @param array<string,mixed> $g
     */
    private function isLowTierFixture(array $g): bool
    {
        $league = (string) ($g['league'] ?? '');
        $home = (string) ($g['home'] ?? '');
        $away = (string) ($g['away'] ?? '');
        $blob = $league . ' ' . $home . ' ' . $away;
        if (preg_match('/\b(U17|U18|U19|U20|U21|U23|II|III|Reserves?|Youth|Women|Womens|Femenil|Femenina|Feminine|Ladies|WSL)\b/i', $blob)) {
            return true;
        }
        if (preg_match('/\b(3\. Liga|4\. Liga|Esiliiga|III Liga|NB III|Segunda División RFEF|Campeonato De Portugal|Division 2|C League|3\. SNL|2\. SNL)\b/i', $league)) {
            return true;
        }
        // Obscure "Premier League" labels outside big football nations
        if (preg_match('/premier league/i', $league)
            && !in_array(strtolower((string) ($g['country'] ?? '')), ['england', 'scotland', 'wales', 'northern-ireland', 'kenya', 'nigeria', 'egypt', 'ghana', 'south-africa'], true)) {
            return true;
        }
        return false;
    }

    /**
     * Take the next $legs games from $pool (already priority-sorted), remove them from pool.
     * Diversifies tip types so tickets are not all Home Win (or any single market).
     *
     * @param list<array<string,mixed>> $pool
     * @return array{0:list<array<string,mixed>>,1:list<array<string,mixed>>}|null
     */
    private function takeAccumulatorLegs(array $pool, int $legs): ?array
    {
        if (count($pool) < $legs) {
            return null;
        }

        $maxSameCode = max(1, (int) ceil($legs / 2));
        $chosen = [];
        $codeCounts = [];

        // Pass 1: prefer main leagues with tip diversity
        // Pass 2: any remaining with tip diversity
        // Pass 3: fill without diversity if still short
        for ($pass = 1; $pass <= 3; $pass++) {
            foreach ($pool as $g) {
                if (count($chosen) >= $legs) {
                    break 2;
                }
                $id = (int) ($g['fixture_id'] ?? 0);
                foreach ($chosen as $c) {
                    if ($id !== 0 && (int) ($c['fixture_id'] ?? 0) === $id) {
                        continue 2;
                    }
                }

                $prio = $this->leaguePriority($g);
                if ($pass === 1 && $prio < 70) {
                    continue;
                }

                $code = strtoupper((string) ($g['pick_code'] ?? $g['pick'] ?? ''));
                $same = $codeCounts[$code] ?? 0;
                if ($pass < 3 && $same >= $maxSameCode) {
                    continue;
                }

                $chosen[] = $g;
                $codeCounts[$code] = $same + 1;
            }
        }

        if (count($chosen) < $legs) {
            return null;
        }

        $chosenIds = [];
        foreach ($chosen as $g) {
            $chosenIds[(int) ($g['fixture_id'] ?? 0)] = true;
        }
        $remaining = array_values(array_filter(
            $pool,
            static function (array $g) use ($chosenIds): bool {
                $id = (int) ($g['fixture_id'] ?? 0);
                return $id === 0 || !isset($chosenIds[$id]);
            }
        ));

        return [$chosen, $remaining];
    }

    /**
     * Higher = more preferred for accumulator legs.
     *
     * @param array<string,mixed> $g
     */
    private function leaguePriority(array $g): int
    {
        // DB flag from leagues.popular_status
        if ((int) ($g['popular'] ?? 0) > 0) {
            return 100;
        }

        $id = (int) ($g['league_id'] ?? 0);
        $league = strtolower((string) ($g['league'] ?? ''));
        $country = strtolower((string) ($g['country'] ?? ''));

        // Top European + UEFA club competitions + strong domestic leagues
        $eliteIds = [
            2 => 100,   // UEFA Champions League
            3 => 95,    // UEFA Europa League
            848 => 90,  // UEFA Conference League
            39 => 100,  // England Premier League
            140 => 98,  // Spain La Liga
            135 => 97,  // Italy Serie A
            78 => 96,   // Germany Bundesliga
            61 => 95,   // France Ligue 1
            40 => 80,   // England Championship
            88 => 78,   // Netherlands Eredivisie
            94 => 78,   // Portugal Primeira Liga
            203 => 75,  // Turkey Süper Lig
            253 => 72,  // MLS
            71 => 74,   // Brazil Serie A
            128 => 74,  // Argentina Liga Profesional (approx — may vary)
            262 => 73,  // Liga MX
            98 => 72,   // Japan J1
            292 => 70,  // K League 1
            144 => 76,  // Belgium Jupiler (if present)
            179 => 76,  // Scotland Premiership (if present)
        ];
        if (isset($eliteIds[$id])) {
            return $eliteIds[$id];
        }

        // Kenya / East & Southern Africa focus
        if (str_contains($country, 'kenya') || str_contains($league, 'kenya')) {
            return 85;
        }
        if (in_array($country, ['nigeria', 'egypt', 'ghana', 'south-africa', 'south africa', 'uganda', 'tanzania'], true)
            || str_contains($league, 'npfl')
            || str_contains($league, 'premier soccer')) {
            return 70;
        }

        // Name heuristics for major competitions (when IDs differ)
        if (preg_match('/champions league|europa league|conference league/i', $league)) {
            return 92;
        }
        if ($country === 'england' && str_contains($league, 'premier')) {
            return 100;
        }
        if ($country === 'spain' && (str_contains($league, 'la liga') || $league === 'laliga')) {
            return 98;
        }
        if ($country === 'italy' && str_contains($league, 'serie a')) {
            return 97;
        }
        if ($country === 'germany' && $league === 'bundesliga') {
            return 96;
        }
        if ($country === 'france' && str_contains($league, 'ligue 1')) {
            return 95;
        }
        if (in_array($country, ['brazil', 'argentina', 'mexico', 'japan', 'south-korea', 'portugal', 'netherlands', 'turkey', 'usa'], true)
            && preg_match('/serie a|liga mx|j1|k league 1|primeira|eredivisie|süper|super lig|major league soccer|mls|liga profesional/i', $league)) {
            return 72;
        }

        // Generic top-flight names only for notable football countries
        if (preg_match('/^(premier league|primera división|serie a)$/i', trim($league))
            && in_array($country, ['england', 'spain', 'italy', 'scotland', 'belgium', 'portugal', 'netherlands'], true)) {
            return 80;
        }

        return 10;
    }

    private function latestJackpotTipsId(string $jackpot): ?string
    {
        $stmt = $this->db->prepare(
            'SELECT jackpot_tips_id FROM pp_fixtures_selections
             WHERE jackpot_name = :j AND status = 1 AND jackpot_tips_id IS NOT NULL AND jackpot_tips_id != ""
             ORDER BY fixture_date DESC, id DESC LIMIT 1'
        );
        $stmt->execute([':j' => $jackpot]);
        $id = $stmt->fetchColumn();
        return $id !== false && $id !== null && $id !== '' ? (string) $id : null;
    }

    private function jackpotSlug(string $name): string
    {
        return match ($name) {
            'Sportpesa Mega Jackpot' => 'sportpesa-mega-jackpot-predictions',
            'Sportpesa Midweek Jackpot' => 'sportpesa-midweek-jackpot-predictions',
            'Betika Midweek Jackpot' => 'betika-midweek-jackpot-predictions',
            'Sporty bet Jackpot' => 'sportybet-daily-jackpot-predictions',
            'Odibet Laki Tatu Jackpot' => 'odibets-laki-tatu-predictions',
            default => strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name) ?? 'jackpot'),
        };
    }

    /**
     * @param list<array<string,mixed>> $games
     * @return list<array<string,mixed>>
     */
    private function sortGames(array $games, string $order): array
    {
        usort($games, static function ($a, $b) use ($order) {
            // leagues.popular_status = 1 always ranks above everything else (all tip pages)
            $pa = (int) ($a['popular'] ?? 0) > 0 ? 1 : 0;
            $pb = (int) ($b['popular'] ?? 0) > 0 ? 1 : 0;
            if ($pa !== $pb) {
                return $pb <=> $pa;
            }
            if ($order === 'confidence_desc') {
                $ca = (int) ($a['confidence'] ?? 0);
                $cb = (int) ($b['confidence'] ?? 0);
                if ($ca !== $cb) {
                    return $cb <=> $ca;
                }
            }
            $ka = (string) ($a['kickoff'] ?? '');
            $kb = (string) ($b['kickoff'] ?? '');
            if ($ka !== $kb) {
                return $ka <=> $kb;
            }
            return ((int) ($a['fixture_id'] ?? 0)) <=> ((int) ($b['fixture_id'] ?? 0));
        });
        return $games;
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    private function mapFixtureRow(array $row, string $market = '1x2'): array
    {
        $pickMeta = $this->deriveMarketPick($row, $market);
        $resolvedMarket = (string) ($pickMeta['market'] ?? $market);
        if (in_array($resolvedMarket, ['best', 'mixed', 'betnumbers'], true)) {
            $resolvedMarket = '1x2';
        }
        $kickoffRaw = (string) ($row['kickoff'] ?? '');
        $sourceTz = (string) ($row['timezone'] ?? DateTimeHelper::SOURCE_TZ);
        $when = DateTimeHelper::formatKickoff($kickoffRaw, $sourceTz);
        $goalsHome = $row['goals_home'];
        $goalsAway = $row['goals_away'];
        $score = ($goalsHome !== null && $goalsAway !== null)
            ? ((int) $goalsHome . '-' . (int) $goalsAway)
            : null;
        $won = $this->evaluateWon($row, $pickMeta['code'], $resolvedMarket, (string) ($row['status_short'] ?? ''));
        $statusShort = strtoupper(trim((string) ($row['status_short'] ?? '')));
        $isLive = $this->isLiveStatus($statusShort);
        $winning = null;
        if ($won === null && $isLive && $goalsHome !== null && $goalsAway !== null) {
            $winning = $this->tipMatchesScore(
                (string) ($pickMeta['code'] ?? ''),
                $resolvedMarket,
                (int) $goalsHome,
                (int) $goalsAway,
                $row
            );
        }

        return [
            'fixture_id' => (int) $row['fixture_id'],
            'league_id' => (int) ($row['league_id'] ?? 0),
            'league' => (string) ($row['league_name'] ?? 'Football'),
            'country' => (string) ($row['country_name'] ?? ''),
            'popular' => (int) ($row['popular_status'] ?? 0),
            'home' => (string) $row['home_team_name'],
            'away' => (string) $row['away_team_name'],
            'home_logo' => (string) ($row['home_team_logo'] ?? ''),
            'away_logo' => (string) ($row['away_team_logo'] ?? ''),
            'date' => $when['date'],
            'time' => $when['time'],
            'kickoff' => $when['kickoff'] !== '' ? $when['kickoff'] : $kickoffRaw,
            'kickoff_utc' => $when['kickoff_utc'],
            'kickoff_iso' => $when['iso'],
            'timezone' => $when['display_tz'],
            'timezone_source' => $sourceTz !== '' ? $sourceTz : DateTimeHelper::SOURCE_TZ,
            'status' => $statusShort,
            'status_long' => (string) ($row['status_long'] ?? ''),
            'is_live' => $isLive,
            'score' => $score,
            'won' => $won,
            'winning' => $winning,
            'pick' => $pickMeta['pick'],
            'pick_code' => $pickMeta['code'],
            'odds' => $pickMeta['odds'],
            'confidence' => $pickMeta['confidence'],
            'reason' => $this->alignedAdvice($row, $pickMeta, $resolvedMarket),
            'market' => $resolvedMarket,
            'market_label' => $this->marketLabel($resolvedMarket),
            'btts' => $row['both_team_to_score'] ?? null,
            'btts_prob' => isset($row['both_teams_percentage_prob']) ? (int) $row['both_teams_percentage_prob'] : null,
            'probs' => [
                'home' => isset($row['percent_pred_home']) ? (int) $row['percent_pred_home'] : null,
                'draw' => isset($row['percent_pred_draw']) ? (int) $row['percent_pred_draw'] : null,
                'away' => isset($row['percent_pred_away']) ? (int) $row['percent_pred_away'] : null,
            ],
            'bookmaker' => $row['bookmaker_name'] ?? null,
            'source' => 'fixtures',
        ];
    }

    /**
     * Settle tip vs FT score. null = not settled / unknown.
     *
     * @param array<string,mixed> $row
     */
    private function evaluateWon(array $row, string $code, string $market, string $status): ?bool
    {
        $status = strtoupper(trim($status));
        if (!in_array($status, ['FT', 'AET', 'PEN', 'AWD', 'WO'], true)) {
            return null;
        }
        if ($row['goals_home'] === null || $row['goals_away'] === null) {
            return null;
        }

        return $this->tipMatchesScore(
            $code,
            $market,
            (int) $row['goals_home'],
            (int) $row['goals_away'],
            $row
        );
    }

    /**
     * Whether the tip is currently correct for a given scoreline.
     *
     * @param array<string,mixed> $row
     */
    private function tipMatchesScore(string $code, string $market, int $gh, int $ga, array $row = []): ?bool
    {
        $code = $this->normalizePickCode($code, $market);

        return match ($market) {
            'double_chance' => $this->wonDoubleChance($code, $gh, $ga),
            'over_under' => $this->wonOverUnder($code, $gh, $ga),
            'btts' => $this->wonBtts($code, $gh, $ga),
            'correct_score' => $this->wonCorrectScore($code, $gh, $ga),
            'ht_ft' => $this->wonHtFt($code, $row, $gh, $ga),
            default => $this->won1x2($code, $gh, $ga),
        };
    }

    private function normalizePickCode(string $code, string $market = '1x2'): string
    {
        $c = strtoupper(trim($code));
        $c = str_replace(['_', '-'], ' ', $c);
        $c = preg_replace('/\s+/', ' ', $c) ?? $c;

        if (in_array($c, ['1', 'HOME', 'HOME WIN', 'W1', 'HW'], true) || str_starts_with($c, 'HOME WIN')) {
            return '1';
        }
        if (in_array($c, ['2', 'AWAY', 'AWAY WIN', 'W2', 'AW'], true) || str_starts_with($c, 'AWAY WIN')) {
            return '2';
        }
        if (in_array($c, ['X', 'DRAW', 'D'], true) || $c === 'DRAW') {
            return 'X';
        }
        if (in_array($c, ['1X', 'X2', '12'], true)) {
            return $c;
        }
        if (in_array($c, ['O2.5', 'OVER 2.5', 'OVER 2.5 GOALS', 'OVER'], true) || str_starts_with($c, 'O2')) {
            return 'O2.5';
        }
        if (in_array($c, ['U2.5', 'UNDER 2.5', 'UNDER 2.5 GOALS', 'UNDER'], true) || str_starts_with($c, 'U2')) {
            return 'U2.5';
        }
        if (str_contains($c, 'BTTS') && (str_contains($c, 'YES') || str_ends_with($c, 'Y'))) {
            return 'BTTS_YES';
        }
        if (str_contains($c, 'BTTS') && (str_contains($c, 'NO') || str_ends_with($c, 'N'))) {
            return 'BTTS_NO';
        }

        return strtoupper(trim($code));
    }

    private function isLiveStatus(string $status): bool
    {
        return in_array($status, ['1H', '2H', 'HT', 'ET', 'BT', 'P', 'LIVE', 'INT', 'BREAK', 'SUSP'], true);
    }

    private function won1x2(string $code, int $gh, int $ga): bool
    {
        $code = $this->normalizePickCode($code, '1x2');
        $actual = $gh > $ga ? '1' : ($gh < $ga ? '2' : 'X');
        return $code === $actual;
    }

    private function wonDoubleChance(string $code, int $gh, int $ga): bool
    {
        $code = $this->normalizePickCode($code, 'double_chance');
        $actual = $gh > $ga ? '1' : ($gh < $ga ? '2' : 'X');
        return match ($code) {
            '1X' => $actual === '1' || $actual === 'X',
            'X2' => $actual === 'X' || $actual === '2',
            '12' => $actual === '1' || $actual === '2',
            // Plain 1X2 codes sometimes land on DC pages — treat as exact result.
            '1', '2', 'X' => $code === $actual,
            default => false,
        };
    }

    private function wonOverUnder(string $code, int $gh, int $ga): bool
    {
        $code = $this->normalizePickCode($code, 'over_under');
        $total = $gh + $ga;
        if ($code === 'O2.5' || str_starts_with($code, 'O')) {
            return $total > 2;
        }
        return $total < 3;
    }

    private function wonBtts(string $code, int $gh, int $ga): bool
    {
        $code = $this->normalizePickCode($code, 'btts');
        $both = $gh > 0 && $ga > 0;
        if ($code === 'BTTS_YES' || str_contains($code, 'YES')) {
            return $both;
        }
        return !$both;
    }

    private function wonCorrectScore(string $code, int $gh, int $ga): bool
    {
        $expected = preg_replace('/^CS_/', '', $code) ?? '';
        return $expected === ($gh . '-' . $ga);
    }

    /** @param array<string,mixed> $row */
    private function wonHtFt(string $code, array $row, int $gh, int $ga): ?bool
    {
        if ($row['ht_goals_home'] === null || $row['ht_goals_away'] === null) {
            return null;
        }
        $hh = (int) $row['ht_goals_home'];
        $ha = (int) $row['ht_goals_away'];
        $ht = $hh > $ha ? '1' : ($hh < $ha ? '2' : 'X');
        $ft = $gh > $ga ? '1' : ($gh < $ga ? '2' : 'X');
        return $code === ($ht . '/' . $ft);
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int,market?:string}
     */
    private function deriveMarketPick(array $row, string $market): array
    {
        return match ($market) {
            'best', 'mixed', 'betnumbers' => $this->deriveBestMarketPick($row),
            'double_chance' => $this->deriveDoubleChance($row) + ['market' => 'double_chance'],
            'over_under' => $this->deriveOverUnder($row) + ['market' => 'over_under'],
            'btts' => $this->deriveBtts($row) + ['market' => 'btts'],
            'correct_score' => $this->deriveCorrectScore($row) + ['market' => 'correct_score'],
            'ht_ft' => $this->deriveHtFt($row) + ['market' => 'ht_ft'],
            default => $this->derive1x2($row) + ['market' => '1x2'],
        };
    }

    /**
     * Pick the strongest lean across 1X2, BTTS, Over/Under 2.5, and Double Chance.
     * Accuracy-first: use model home/draw/away % + odds fit; prefer shorter prices
     * that the model actually supports (avoid home-win @ 3.0 style traps).
     *
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int,market:string}
     */
    private function deriveBestMarketPick(array $row): array
    {
        $candidates = [
            $this->derive1x2($row) + ['market' => '1x2'],
            $this->deriveBtts($row) + ['market' => 'btts'],
            $this->deriveOverUnder($row) + ['market' => 'over_under'],
            $this->deriveDoubleChance($row) + ['market' => 'double_chance'],
        ];

        $scored = [];
        foreach ($candidates as $c) {
            $odd = $this->oddFloat($c['odds'] ?? null);
            if ($odd === null || $odd < 1.18 || $odd > 3.10) {
                continue;
            }

            $modelPct = $this->modelWinPercent($row, $c);
            if ($modelPct < 52) {
                continue;
            }

            // Long prices need a clear model majority — otherwise prefer DC / O/U / BTTS.
            if ($odd >= 2.50 && $modelPct < 58) {
                continue;
            }
            if ($odd >= 2.80 && $modelPct < 62) {
                continue;
            }
            if ($odd >= 3.0 && $c['market'] === '1x2') {
                continue;
            }

            $fair = $modelPct > 0 ? (100.0 / $modelPct) : 99.0;
            // Book much longer than the model → poor accuracy candidate.
            if ($odd > $fair * 1.28 && $odd >= 2.15) {
                continue;
            }

            // Hit-rate score: model chance first, then prefer shorter fitting odds.
            $score = (float) $modelPct;
            $score += max(0.0, (2.35 - min($odd, 2.35)) * 7.0);
            $score -= max(0.0, ($odd - $fair) * 18.0);

            // Demote soft long 1X2; boost strong short-priced favourites.
            if ($c['market'] === '1x2' && $odd >= 2.40) {
                $score -= 10.0;
            }
            if ($c['market'] === '1x2' && $odd <= 2.05 && $modelPct >= 60) {
                $score += 8.0;
            }

            // Ultra-short DC pads hit rate but crowds out clearer singles / goals tips.
            if ($c['market'] === 'double_chance') {
                $score -= 6.0;
                if ($odd < 1.28) {
                    $score -= 8.0;
                }
            }

            // Goals markets: promote when price is stakeable and avg-goals lean is solid.
            if ($c['market'] === 'over_under' && $odd <= 1.95 && $modelPct >= 58) {
                $score += 9.0;
            }
            if ($c['market'] === 'btts' && $odd <= 2.05 && $modelPct >= 60) {
                $score += 5.0;
            }

            // Publish tempered model lean (not a win-rate promise). Cap below the old
            // 88 ceiling so Double Chance cards do not all stamp the same number.
            $c['confidence'] = $this->publishConfidence($modelPct, (string) ($c['market'] ?? ''));

            $scored[] = [
                'pick' => $c,
                'score' => $score,
                'model' => $modelPct,
                'odd' => $odd,
                'fit' => abs($odd - $fair),
            ];
        }

        if ($scored === []) {
            return $this->saferFallbackPick($row);
        }

        usort($scored, static function (array $a, array $b): int {
            if (abs($a['score'] - $b['score']) > 0.35) {
                return $b['score'] <=> $a['score'];
            }
            if ($a['model'] !== $b['model']) {
                return $b['model'] <=> $a['model'];
            }
            if (abs($a['fit'] - $b['fit']) > 0.05) {
                return $a['fit'] <=> $b['fit'];
            }
            return $a['odd'] <=> $b['odd'];
        });

        /** @var array{pick:string,code:string,odds:?string,confidence:int,market:string} $best */
        $best = $scored[0]['pick'];
        return $best;
    }

    /**
     * Model win % for a candidate from predictions_computation home/draw/away (and peers).
     *
     * @param array<string,mixed> $row
     * @param array{code?:string,market?:string,confidence?:int} $c
     */
    private function modelWinPercent(array $row, array $c): int
    {
        $h = (int) ($row['percent_pred_home'] ?? 0);
        $d = (int) ($row['percent_pred_draw'] ?? 0);
        $a = (int) ($row['percent_pred_away'] ?? 0);
        $code = strtoupper((string) ($c['code'] ?? ''));
        $market = (string) ($c['market'] ?? '');

        return match ($market) {
            '1x2' => match ($code) {
                '1' => $h,
                '2' => $a,
                'X' => $d,
                default => (int) ($c['confidence'] ?? 0),
            },
            // Do not publish home+draw sums (they pile up at a fake ~88% ceiling).
            'double_chance' => match ($code) {
                '1X' => $this->doubleChanceLeanPercent($h, $d, $a),
                'X2' => $this->doubleChanceLeanPercent($d, $a, $h),
                '12' => $this->doubleChanceLeanPercent($h, $a, $d),
                default => (int) ($c['confidence'] ?? 0),
            },
            'btts', 'over_under' => (int) ($c['confidence'] ?? 0),
            default => (int) ($c['confidence'] ?? 0),
        };
    }

    /**
     * Realistic DC lean for cards/ranking.
     * Uses the stronger covered outcome + a small cover bonus — not the two-way sum.
     */
    private function doubleChanceLeanPercent(int $coveredA, int $coveredB, int $excluded): int
    {
        $primary = max($coveredA, $coveredB);
        $coverBonus = (int) round(min(8, max(0, min($coveredA, $coveredB) * 0.08)));
        $excludeBonus = (int) round(min(5, max(0, (42 - $excluded) * 0.18)));

        return max(52, min(76, $primary + $coverBonus + $excludeBonus));
    }

    /**
     * Card-facing % band — honest lean, not a near-certainty stamp.
     */
    private function publishConfidence(int $modelPct, string $market): int
    {
        $cap = match ($market) {
            'double_chance' => 76,
            'over_under', 'btts' => 78,
            'ht_ft', 'correct_score' => 72,
            default => 82,
        };

        return max(50, min($cap, $modelPct));
    }

    /**
     * When no candidate clears the accuracy gates, prefer short DC / O/U over long 1X2.
     *
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int,market:string}
     */
    private function saferFallbackPick(array $row): array
    {
        $pool = [
            $this->deriveDoubleChance($row) + ['market' => 'double_chance'],
            $this->deriveOverUnder($row) + ['market' => 'over_under'],
            $this->deriveBtts($row) + ['market' => 'btts'],
            $this->derive1x2($row) + ['market' => '1x2'],
        ];

        $best = null;
        $bestScore = -1.0;
        foreach ($pool as $c) {
            $odd = $this->oddFloat($c['odds'] ?? null);
            if ($odd === null || $odd < 1.18 || $odd > 2.60) {
                continue;
            }
            $model = $this->modelWinPercent($row, $c);
            $score = $model - ($odd * 8.0);
            if ($score > $bestScore) {
                $bestScore = $score;
                $c['confidence'] = $this->publishConfidence($model, (string) ($c['market'] ?? ''));
                $best = $c;
            }
        }

        if ($best !== null) {
            return $best;
        }

        $fallback = $this->deriveDoubleChance($row);
        $fallback['market'] = 'double_chance';
        return $fallback;
    }

    private function oddFloat(mixed $odds): ?float
    {
        if ($odds === null || $odds === '' || $odds === '-') {
            return null;
        }
        $n = (float) $odds;
        return $n > 1.0 ? $n : null;
    }

    private function marketLabel(string $market): string
    {
        return match ($market) {
            'double_chance' => 'DC',
            'over_under' => 'O/U',
            'btts' => 'BTTS',
            'ht_ft' => 'HT/FT',
            'correct_score' => 'CS',
            default => '1X2',
        };
    }

    /**
     * One-line tip reason: soft YMYL tone + varied templates so cards do not all read the same.
     *
     * @param array<string,mixed> $row
     * @param array{pick?:string,code?:string,odds?:?string,confidence?:int} $pickMeta
     */
    private function alignedAdvice(array $row, array $pickMeta, string $market): string
    {
        $home = trim((string) ($row['home_team_name'] ?? 'Home'));
        $away = trim((string) ($row['away_team_name'] ?? 'Away'));
        $code = strtoupper(trim((string) ($pickMeta['code'] ?? '')));
        $odd = $this->oddFloat($pickMeta['odds'] ?? null);
        $h = (int) ($row['percent_pred_home'] ?? 0);
        $d = (int) ($row['percent_pred_draw'] ?? 0);
        $a = (int) ($row['percent_pred_away'] ?? 0);
        $avg = isset($row['avg_goals']) ? (float) $row['avg_goals'] : 0.0;
        $bttsProb = isset($row['both_teams_percentage_prob']) ? (int) $row['both_teams_percentage_prob'] : 0;
        $price = $odd !== null ? number_format($odd, 2) : null;
        $seed = (int) ($row['fixture_id'] ?? 0)
            + strlen($home) * 7
            + strlen($away) * 13
            + ord($code[0] ?? '1');

        $line = match ($market) {
            'double_chance' => $this->doubleChanceAdviceLine($code, $home, $away, $h, $d, $a, $price, $seed),
            'over_under' => $this->overUnderAdviceLine($code, $avg, $price, $seed),
            'btts' => $this->bttsAdviceLine($code, $bttsProb, $price, $seed),
            'ht_ft', 'correct_score' => trim((string) ($pickMeta['pick'] ?? '')),
            default => match ($code) {
                '1' => $this->winnerAdviceLine($home, 'home', $h, $d, $a, $price, $seed),
                '2' => $this->winnerAdviceLine($away, 'away', $a, $d, $h, $price, $seed),
                'X' => $this->drawAdviceLine($h, $d, $a, $price, $seed),
                default => trim((string) ($pickMeta['pick'] ?? '')),
            },
        };

        $line = trim(preg_replace('/\s+/', ' ', $line) ?? $line);
        if (strlen($line) > 170) {
            $line = rtrim(substr($line, 0, 167)) . '…';
        }
        return $line;
    }

    /** Stable 0..$n-1 pick from fixture seed (same tip keeps same wording). */
    private function adviceVariant(int $seed, int $n): int
    {
        if ($n <= 1) {
            return 0;
        }
        return abs($seed) % $n;
    }

    private function priceClause(?string $price, int $variant): string
    {
        if ($price === null) {
            return '';
        }
        return match ($variant % 3) {
            1 => '; available around ' . $price,
            2 => '; shortlist price near ' . $price,
            default => '; priced around ' . $price,
        };
    }

    private function cautionClause(int $variant): string
    {
        return match ($variant % 4) {
            1 => ' — opinion only, not a win rate.',
            2 => ' — margins can flip on the day.',
            3 => ' — informational lean, not certainty.',
            default => ' — still not a guarantee.',
        };
    }

    /**
     * Soft 1X2 winner line — model lean, not a win promise.
     */
    private function winnerAdviceLine(
        string $team,
        string $side,
        int $leanPct,
        int $drawPct,
        int $otherPct,
        ?string $price,
        int $seed
    ): string {
        $sideLabel = $side === 'away' ? 'away' : 'home';
        $gap = $leanPct - max($drawPct, $otherPct);
        $v = $this->adviceVariant($seed, 4);
        $tail = $this->priceClause($price, $seed) . $this->cautionClause($seed);

        if ($leanPct >= 62 && $gap >= 12) {
            $templates = [
                'Model lean: %s (%s) at %d%%, ahead of draw %d%% / other %d%%%s',
                '%s favoured on the %s side in our model (%d%%) versus draw %d%% and reverse %d%%%s',
                'Stronger model share sits with %s %s (%d%%); draw %d%% and other %d%% trail%s',
                'Data lean toward %s to take it %s — model %d%% with draw %d%% / reverse %d%%%s',
            ];
            return sprintf($templates[$v], $team, $sideLabel, $leanPct, $drawPct, $otherPct, $tail);
        }

        if ($leanPct >= 55) {
            $templates = [
                'Model lean: %s at %s (%d%%) — modest edge over draw %d%% / other %d%%%s',
                '%s carry a mild %s edge in the model (%d%%); draw %d%% and reverse %d%% keep risk in play%s',
                'Slight model tilt to %s (%s, %d%%) rather than draw %d%% or the other side %d%%%s',
                'Working lean is %s %s at %d%% model share — not a lock with draw %d%% / other %d%%%s',
            ];
            return sprintf($templates[$v], $team, $sideLabel, $leanPct, $drawPct, $otherPct, $tail);
        }

        $templates = [
            'Narrow model lean on %s (%s, %d%%) — draw %d%% and reverse %d%% make this a tight call%s',
            '%s only shade it %s in the model (%d%%); treat draw %d%% / other %d%% as live threats%s',
            'Soft lean for %s (%s, %d%%) — margins are thin vs draw %d%% and reverse %d%%%s',
            'Provisional lean: %s %s (%d%%). Draw %d%% and other %d%% leave little room for error%s',
        ];
        return sprintf($templates[$v], $team, $sideLabel, $leanPct, $drawPct, $otherPct, $tail);
    }

    private function drawAdviceLine(int $h, int $d, int $a, ?string $price, int $seed): string
    {
        $v = $this->adviceVariant($seed, 3);
        $tail = $this->priceClause($price, $seed) . $this->cautionClause($seed);
        $templates = [
            'Model lean toward a draw (%d%%) with home %d%% / away %d%%%s',
            'Split looks balanced enough for a draw lean — model draw %d%% vs home %d%% and away %d%%%s',
            'Draw is the working lean at %d%% model share; home %d%% and away %d%% stay close%s',
        ];
        return sprintf($templates[$v], $d, $h, $a, $tail);
    }

    private function doubleChanceAdviceLine(
        string $code,
        string $home,
        string $away,
        int $h,
        int $d,
        int $a,
        ?string $price,
        int $seed
    ): string {
        $v = $this->adviceVariant($seed, 3);
        $tail = $this->priceClause($price, $seed) . ' — cover only, not certainty.';

        if ($code === '1X') {
            $templates = [
                'Model lean: %s or draw as a two-way cover; away win is the softest share (%d%%)%s',
                'Safer read is %s / draw — model rates the away win lowest at %d%%%s',
                'Double-chance cover on %s or draw while the away outcome sits near %d%%%s',
            ];
            return sprintf($templates[$v], $home, $a, $tail);
        }
        if ($code === 'X2') {
            $templates = [
                'Model lean: draw or %s; home win is the weakest share (%d%%)%s',
                'Two-way cover on draw / %s while home sits near %d%% in the model%s',
                'Working cover is draw or %s — model keeps the home win softest (%d%%)%s',
            ];
            return sprintf($templates[$v], $away, $h, $tail);
        }
        if ($code === '12') {
            $templates = [
                'Model lean against a draw — either side to win while draw share is %d%%%s',
                'Prefer a winner either way; model draw probability sits around %d%%%s',
                '12 cover: model sees the draw as the softer path at %d%%%s',
            ];
            return sprintf($templates[$v], $d, $tail);
        }
        return 'Double-chance lean' . ($price !== null ? ' around ' . $price : '') . ' — cover only, not certainty.';
    }

    private function overUnderAdviceLine(string $code, float $avg, ?string $price, int $seed): string
    {
        $v = $this->adviceVariant($seed, 3);
        $tail = $this->priceClause($price, $seed) . ' — goals markets swing.';

        if ($code === 'O2.5') {
            if ($avg > 0) {
                $templates = [
                    'Model lean Over 2.5 with expected goals near %.1f%s',
                    'Goals lean over the 2.5 line — projected total around %.1f%s',
                    'Working over tip: model expected goals sit near %.1f%s',
                ];
                return sprintf($templates[$v], $avg, $tail);
            }
            $templates = [
                'Model lean Over 2.5 goals%s',
                'Goals profile supports an over 2.5 lean%s',
                'Working tip is Over 2.5 on this matchup%s',
            ];
            return sprintf($templates[$v], $tail);
        }

        if ($avg > 0) {
            $templates = [
                'Model lean Under 2.5 with expected goals near %.1f%s',
                'Lower-event lean: projected total around %.1f supports under 2.5%s',
                'Working under tip — model expected goals sit near %.1f%s',
            ];
            return sprintf($templates[$v], $avg, $tail);
        }
        $templates = [
            'Model lean Under 2.5 goals%s',
            'Goals profile supports an under 2.5 lean%s',
            'Working tip is Under 2.5 on this matchup%s',
        ];
        return sprintf($templates[$v], $tail);
    }

    private function bttsAdviceLine(string $code, int $bttsProb, ?string $price, int $seed): string
    {
        $v = $this->adviceVariant($seed, 3);
        $tail = $this->priceClause($price, $seed) . ' — not a lock.';

        if ($code === 'BTTS_YES') {
            if ($bttsProb > 0) {
                $templates = [
                    'Model lean BTTS Yes — both-teams share near %d%%%s',
                    'Both to score is the working lean (model BTTS ~%d%%)%s',
                    'Data tilt to BTTS Yes with model probability around %d%%%s',
                ];
                return sprintf($templates[$v], $bttsProb, $tail);
            }
            return 'Model lean Both teams to score Yes' . $tail;
        }

        if ($bttsProb > 0) {
            $templates = [
                'Model lean BTTS No — both-teams share still around %d%%%s',
                'Working lean against both scoring (model BTTS ~%d%%)%s',
                'BTTS No lean while the model keeps both-score chance near %d%%%s',
            ];
            return sprintf($templates[$v], $bttsProb, $tail);
        }
        return 'Model lean Both teams to score No' . $tail;
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int}
     */
    private function derive1x2(array $row): array
    {
        $h = (int) ($row['percent_pred_home'] ?? 0);
        $d = (int) ($row['percent_pred_draw'] ?? 0);
        $a = (int) ($row['percent_pred_away'] ?? 0);

        $max = max($h, $d, $a);
        if ($max <= 0) {
            // Missing model % — fall back to shortest 1X2 price, not a blind Home Win.
            $oh = $this->oddFloat($row['bets_home'] ?? null) ?? 99.0;
            $od = $this->oddFloat($row['bets_draw'] ?? null) ?? 99.0;
            $oa = $this->oddFloat($row['bets_away'] ?? null) ?? 99.0;
            $shortest = min($oh, $od, $oa);
            if ($shortest >= 99.0) {
                $code = '1';
                $conf = 50;
            } elseif ($oa === $shortest) {
                $code = '2';
                $conf = 50;
            } elseif ($od === $shortest) {
                $code = 'X';
                $conf = 50;
            } else {
                $code = '1';
                $conf = 50;
            }
        } elseif ($h === $max && $h >= $d && $h >= $a) {
            $code = '1';
            $conf = $h;
        } elseif ($a === $max && $a >= $h && $a >= $d) {
            $code = '2';
            $conf = $a;
        } else {
            $code = 'X';
            $conf = $d;
        }

        // Align with predictions.winner_team_name only when percents agree.
        $winner = trim((string) ($row['winner_team_name'] ?? ''));
        $home = trim((string) ($row['home_team_name'] ?? ''));
        $away = trim((string) ($row['away_team_name'] ?? ''));
        if ($winner !== '' && $home !== '' && strcasecmp($winner, $home) === 0 && $h >= $d && $h >= $a) {
            $code = '1';
            $conf = $h;
        } elseif ($winner !== '' && $away !== '' && strcasecmp($winner, $away) === 0 && $a >= $d && $a >= $h) {
            $code = '2';
            $conf = $a;
        }

        // Soft / three-way coin flips → do not overstate confidence.
        $second = $code === '1' ? max($d, $a) : ($code === '2' ? max($h, $d) : max($h, $a));
        if ($max - $second < 4) {
            $conf = min($conf, 52);
        }

        $oddsMap = [
            '1' => $row['bets_home'] ?? null,
            'X' => $row['bets_draw'] ?? null,
            '2' => $row['bets_away'] ?? null,
        ];
        $oddStr = $this->oddStr($oddsMap[$code] ?? null);
        $odd = $this->oddFloat($oddStr);

        // Accuracy guard: long 1X2 prices need a real model majority.
        if ($odd !== null) {
            if ($odd >= 3.0 && $conf < 62) {
                $conf = min($conf, 45);
            } elseif ($odd >= 2.70 && $conf < 55) {
                $conf = min($conf, 48);
            } elseif ($odd >= 2.40 && $conf < 52) {
                $conf = min($conf, 50);
            }
        }

        return [
            'code' => $code,
            'pick' => $this->tipLabel($code),
            'odds' => $oddStr,
            'confidence' => max(45, min(85, $conf > 0 ? $conf : 50)),
        ];
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int}
     */
    private function deriveDoubleChance(array $row): array
    {
        $h = (int) ($row['percent_pred_home'] ?? 0);
        $d = (int) ($row['percent_pred_draw'] ?? 0);
        $a = (int) ($row['percent_pred_away'] ?? 0);

        if ($a < $h && $a < $d) {
            $code = '1X';
            $odds = $row['double_chance_home_draw'] ?? null;
            $conf = $this->doubleChanceLeanPercent($h, $d, $a);
        } elseif ($h < $a && $h < $d) {
            $code = 'X2';
            $odds = $row['double_chance_draw_away'] ?? null;
            $conf = $this->doubleChanceLeanPercent($d, $a, $h);
        } else {
            $code = '12';
            $odds = $row['double_chance_home_away'] ?? null;
            $conf = $this->doubleChanceLeanPercent($h, $a, $d);
        }

        return [
            'code' => $code,
            'pick' => $this->tipLabel($code),
            'odds' => $this->oddStr($odds),
            'confidence' => $conf,
        ];
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int}
     */
    private function deriveOverUnder(array $row): array
    {
        $underOver = strtolower(trim((string) ($row['under_over'] ?? '')));
        $avg = isset($row['avg_goals']) ? (float) $row['avg_goals'] : 0;
        $over = $row['over_2_5'] ?? null;
        $under = $row['under_2_5'] ?? null;

        $leanOver = str_contains($underOver, 'over')
            || (!str_contains($underOver, 'under') && $avg >= 2.5);

        // Conservative lean score from expected goals — not a promised win rate.
        // Keep within a modest band so cards do not imply near-certainty.
        if ($leanOver) {
            $conf = (int) round(52 + max(0, $avg - 2.5) * 12);
            return [
                'code' => 'O2.5',
                'pick' => 'Over 2.5 Goals',
                'odds' => $this->oddStr($over),
                'confidence' => max(55, min(78, $conf)),
            ];
        }
        $conf = (int) round(52 + max(0, 2.5 - $avg) * 14);
        return [
            'code' => 'U2.5',
            'pick' => 'Under 2.5 Goals',
            'odds' => $this->oddStr($under),
            'confidence' => max(55, min(78, $conf)),
        ];
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int}
     */
    private function deriveBtts(array $row): array
    {
        $raw = strtolower(trim((string) ($row['both_team_to_score'] ?? '')));
        $prob = (int) ($row['both_teams_percentage_prob'] ?? 50);
        $yes = str_starts_with($raw, 'y') || $raw === '1';
        if ($raw === '' && $prob >= 55) {
            $yes = true;
        }
        if ($yes) {
            return [
                'code' => 'BTTS_YES',
                'pick' => 'BTTS Yes',
                'odds' => $this->oddStr($row['both_teams_to_score_yes'] ?? null),
                'confidence' => max(50, min(85, $prob)),
            ];
        }
        return [
            'code' => 'BTTS_NO',
            'pick' => 'BTTS No',
            'odds' => $this->oddStr($row['both_teams_to_score_no'] ?? null),
            'confidence' => max(50, min(85, 100 - $prob)),
        ];
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int}
     */
    private function deriveCorrectScore(array $row): array
    {
        $score = trim((string) ($row['cs_best_score_1'] ?? ''));
        $odd = $row['cs_best_odd_1'] ?? null;
        $base = $this->derive1x2($row);
        if ($score === '') {
            // Fallback from 1X2 lean
            $score = match ($base['code']) {
                '2' => '0-1',
                'X' => '1-1',
                default => '1-0',
            };
        }
        return [
            'code' => 'CS_' . $score,
            'pick' => 'Correct Score ' . $score,
            'odds' => $this->oddStr($odd),
            'confidence' => max(40, min(70, (int) $base['confidence'] - 15)),
        ];
    }

    /**
     * @param array<string,mixed> $row
     * @return array{pick:string,code:string,odds:?string,confidence:int}
     */
    private function deriveHtFt(array $row): array
    {
        $hh = (int) ($row['hf_percent_pred_home'] ?? 0);
        $hd = (int) ($row['hf_percent_pred_draw'] ?? 0);
        $ha = (int) ($row['hf_percent_pred_away'] ?? 0);
        $ft = $this->derive1x2($row);

        $htMax = max($hh, $hd, $ha);
        if ($htMax <= 0 || $hh === $htMax) {
            $ht = '1';
        } elseif ($ha === $htMax) {
            $ht = '2';
        } else {
            $ht = 'X';
        }
        // Classic slow-starter favourite → X/1 when FT is home and HT drawish
        if ($ft['code'] === '1' && $hd >= $hh) {
            $ht = 'X';
        }

        $code = $ht . '/' . $ft['code'];
        return [
            'code' => $code,
            'pick' => 'HT/FT ' . str_replace(['1', '2', 'X'], ['Home', 'Away', 'Draw'], $code),
            'odds' => null,
            'confidence' => max(45, min(72, (int) round(($htMax + (int) $ft['confidence']) / 2))),
        ];
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    private function mapSelectionRow(array $row): array
    {
        $isJackpot = trim((string) ($row['jackpot_name'] ?? '')) !== '';
        // Jackpot sheets must publish pp_fixtures_selections.tip (not revised_tip overrides).
        if ($isJackpot) {
            $tip = trim((string) ($row['tip'] ?? ''));
            if ($tip === '') {
                $tip = trim((string) ($row['revised_tip'] ?? ''));
            }
        } else {
            $tip = trim((string) ($row['revised_tip'] ?: $row['tip'] ?: ''));
        }

        // Normalise occasional DB labels like DC1X / DCX2 on tip/revised.
        $upper = strtoupper(str_replace([' ', '-'], '', $tip));
        if (preg_match('/^DC?(1X|X2|12)$/', $upper, $m)) {
            $code = $m[1];
            $market = 'double_chance';
        } elseif (in_array($upper, ['1X', 'X2', '12'], true)) {
            $code = $upper;
            $market = 'double_chance';
        } else {
            $code = $this->normalizePickCode($tip !== '' ? $tip : '1', '1x2');
            $market = in_array($code, ['1X', 'X2', '12'], true) ? 'double_chance' : '1x2';
        }

        $conf = $row['research_confidence'] ?? $row['prediction_confidence'] ?? 50;
        $kickoffRaw = (string) ($row['kickoff'] ?? '');
        $when = DateTimeHelper::formatKickoff($kickoffRaw, (string) ($row['timezone'] ?? DateTimeHelper::SOURCE_TZ));
        $goalsHome = $row['goals_home'] ?? null;
        $goalsAway = $row['goals_away'] ?? null;
        $score = ($goalsHome !== null && $goalsAway !== null)
            ? ((int) $goalsHome . '-' . (int) $goalsAway)
            : null;
        $odd = (string) ($row['odd'] ?? '');
        if ($odd === '-' || $odd === '') {
            $odd = null;
        }

        $pos = isset($row['jackpot_position']) ? (int) $row['jackpot_position'] : 0;
        $league = (string) ($row['league_name'] ?? '');
        if ($pos > 0) {
            $league = '#' . $pos . ($league !== '' ? ' · ' . $league : '');
        } elseif ($league === '') {
            $league = (string) ($row['jackpot_name'] ?? 'Football');
        }

        $statusShort = strtoupper(trim((string) ($row['status_short'] ?? '')));
        $status = $statusShort !== '' ? $statusShort : strtoupper(trim((string) ($row['manual_status'] ?? '')));
        $isLive = $this->isLiveStatus($statusShort);
        $won = null;
        $winning = null;
        if ($goalsHome !== null && $goalsAway !== null) {
            if (in_array($statusShort, ['FT', 'AET', 'PEN', 'AWD', 'WO'], true)) {
                $won = $this->tipMatchesScore($code, $market, (int) $goalsHome, (int) $goalsAway, $row);
            } elseif ($isLive) {
                $winning = $this->tipMatchesScore($code, $market, (int) $goalsHome, (int) $goalsAway, $row);
            }
        }

        return [
            'id' => (int) $row['id'],
            'fixture_id' => (int) $row['fixture_id'],
            'league' => $league,
            'country' => (string) ($row['country_name'] ?? ''),
            'home' => (string) $row['home_team_name'],
            'away' => (string) $row['away_team_name'],
            'home_logo' => (string) ($row['home_team_logo'] ?? ''),
            'away_logo' => (string) ($row['away_team_logo'] ?? ''),
            'date' => $when['date'],
            'time' => $when['time'],
            'kickoff' => $when['kickoff'] !== '' ? $when['kickoff'] : $kickoffRaw,
            'kickoff_utc' => $when['kickoff_utc'],
            'kickoff_iso' => $when['iso'],
            'timezone' => $when['display_tz'],
            'status' => $status,
            'status_long' => (string) ($row['status_long'] ?? ''),
            'is_live' => $isLive,
            'score' => $score,
            'won' => $won,
            'winning' => $winning,
            'pick' => $this->tipLabel($code),
            'pick_code' => $code,
            'odds' => $odd,
            'confidence' => (int) $conf,
            'reason' => $this->selectionAdviceLine($row, $code, $market, $odd, $pos),
            'category' => (string) ($row['category'] ?? ''),
            'jackpot_name' => (string) ($row['jackpot_name'] ?? ''),
            'jackpot_tips_id' => (string) ($row['jackpot_tips_id'] ?? ''),
            'source' => 'selections',
        ];
    }

    /**
     * Jackpot/selection card reason — aligned to published tip, never upstream
     * "revised / research vs system" winner_reason strings.
     */
    private function selectionAdviceLine(
        array $row,
        string $code,
        string $market,
        ?string $odd,
        int $pos
    ): string {
        $home = trim((string) ($row['home_team_name'] ?? 'Home'));
        $away = trim((string) ($row['away_team_name'] ?? 'Away'));
        $h = (int) ($row['home_prob'] ?? 0);
        $d = (int) ($row['draw_prob'] ?? 0);
        $a = (int) ($row['away_prob'] ?? 0);
        $price = $this->oddFloat($odd) !== null ? number_format((float) $odd, 2) : null;
        $seed = (int) ($row['fixture_id'] ?? 0) + $pos * 17 + ord($code[0] ?? '1');

        if ($market === 'double_chance') {
            return $this->doubleChanceAdviceLine($code, $home, $away, $h, $d, $a, $price, $seed);
        }

        if ($h + $d + $a >= 50) {
            return match ($code) {
                '1' => $this->winnerAdviceLine($home, 'home', $h, $d, $a, $price, $seed),
                '2' => $this->winnerAdviceLine($away, 'away', $a, $d, $h, $price, $seed),
                'X' => $this->drawAdviceLine($h, $d, $a, $price, $seed),
                default => $this->jackpotFallbackAdvice($code, $home, $away, $price, $pos, $seed),
            };
        }

        return $this->jackpotFallbackAdvice($code, $home, $away, $price, $pos, $seed);
    }

    private function jackpotFallbackAdvice(
        string $code,
        string $home,
        string $away,
        ?string $price,
        int $pos,
        int $seed
    ): string {
        $v = $this->adviceVariant($seed, 3);
        $game = $pos > 0 ? ('Game ' . $pos) : 'This row';
        $tail = $this->priceClause($price, $seed) . $this->cautionClause($seed);

        return match ($code) {
            '1' => match ($v) {
                1 => sprintf('%s sheet lean is %s at home%s', $game, $home, $tail),
                2 => sprintf('Jackpot lean: favour %s at home on %s%s', $home, strtolower($game), $tail),
                default => sprintf('%s: model lean Home Win — %s on the sheet%s', $game, $home, $tail),
            },
            '2' => match ($v) {
                1 => sprintf('%s sheet lean is %s away%s', $game, $away, $tail),
                2 => sprintf('Jackpot lean: favour %s away on %s%s', $away, strtolower($game), $tail),
                default => sprintf('%s: model lean Away Win — %s on the sheet%s', $game, $away, $tail),
            },
            'X' => match ($v) {
                1 => sprintf('%s sheet lean is a draw between %s and %s%s', $game, $home, $away, $tail),
                2 => sprintf('Jackpot lean: draw on %s (%s vs %s)%s', strtolower($game), $home, $away, $tail),
                default => sprintf('%s: model lean Draw — %s vs %s%s', $game, $home, $away, $tail),
            },
            '1X' => sprintf('%s: double-chance cover %s or draw%s', $game, $home, $tail),
            'X2' => sprintf('%s: double-chance cover draw or %s%s', $game, $away, $tail),
            '12' => sprintf('%s: either side to win preferred over a draw%s', $game, $tail),
            default => sprintf('%s: published jackpot lean%s', $game, $tail),
        };
    }

    private function oddStr(mixed $odds): ?string
    {
        if ($odds === null || $odds === '' || $odds === '-') {
            return null;
        }
        return (string) $odds;
    }

    private function tipLabel(string $code): string
    {
        $code = strtoupper(trim($code));
        return match ($code) {
            '1' => 'Home Win',
            'X' => 'Draw',
            '2' => 'Away Win',
            '1X' => 'Double Chance 1X',
            'X2' => 'Double Chance X2',
            '12' => 'Double Chance 12',
            default => $code !== '' ? $code : 'Home Win',
        };
    }
}
