<?php
namespace App\Services;

/**
 * Resolve page-specific API payloads from config/api-pages.php
 */
final class PageApiService
{
    private GamesService $games;
    /** @var array<string,array<string,mixed>> */
    private array $pages;

    public function __construct(?GamesService $games = null)
    {
        $this->games = $games ?: new GamesService();
        $this->pages = require dirname(__DIR__, 2) . '/config/api-pages.php';
    }

    /** @return list<string> */
    public function pageKeys(): array
    {
        return array_keys($this->pages);
    }

    public function hasPage(string $key): bool
    {
        return isset($this->pages[$key]);
    }

    /**
     * @return array<string,mixed>
     */
    public function payload(string $key, array $overrides = []): array
    {
        if (!$this->hasPage($key)) {
            throw new \InvalidArgumentException('Unknown API page: ' . $key);
        }

        $def = array_merge($this->pages[$key], $overrides);
        $source = strtolower((string) ($def['source'] ?? 'fixtures'));

        $filters = $def;
        unset($filters['title'], $filters['extra']);

        $gamesOrHub = $this->games->listGames($filters);

        $payload = [
            'ok' => true,
            'page' => $key,
            'title' => $def['title'] ?? $key,
            'source' => $source,
            'market' => $def['market'] ?? null,
        ];

        if ($source === 'jackpot_hub') {
            $payload['count'] = count($gamesOrHub);
            $payload['jackpots'] = $gamesOrHub;
            return $payload;
        }

        $payload['date'] = isset($def['range'])
            ? $this->games->resolveDateRange($def)
            : $this->games->resolveDate($def);
        $payload['count'] = count($gamesOrHub);
        $payload['games'] = $gamesOrHub;

        if (($def['extra'] ?? '') === 'accumulators') {
            $payload['accumulators'] = $this->games->buildAccumulators($gamesOrHub);
        }

        return $payload;
    }

    /**
     * Flat games list for rendering match cards (not hub).
     *
     * @return list<array<string,mixed>>
     */
    public function gamesForPage(string $key, array $overrides = []): array
    {
        $payload = $this->payload($key, $overrides);
        return $payload['games'] ?? [];
    }
}
