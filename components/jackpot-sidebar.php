<?php
/**
 * Jackpot sheet switcher — fixtures + this list only on jackpot pages (no tip of day).
 * Set $bao_jackpot_active to the current jackpot slug to highlight it.
 */
$baoJackpots = require __DIR__ . '/../config/jackpots.php';
$activeJackpot = isset($bao_jackpot_active) ? (string) $bao_jackpot_active : '';
?>
<aside class="sidebar sidebar--jackpots" aria-label="Jackpot predictions">
  <div class="sidebar-collapsible">
    <section class="panel">
      <header class="panel-header">
        <h2 class="panel-title">Jackpots</h2>
        <a class="panel-header-link" href="/jackpot-predictions">All</a>
      </header>
      <div class="panel-content">
        <nav class="market-list" aria-label="Jackpot sheets">
          <?php foreach ($baoJackpots as $slug => $meta):
            $label = (string) ($meta['label'] ?? 'Jackpot');
            $games = (int) ($meta['expected_games'] ?? 0);
            $isActive = $activeJackpot === $slug;
            ?>
          <a href="/jackpots/<?= htmlspecialchars($slug) ?>" class="market-item-link<?= $isActive ? ' is-active' : '' ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
            <span class="market-info">
              <span class="market-name"><?= htmlspecialchars($label) ?></span>
            </span>
            <?php if ($games > 0): ?>
            <span class="market-count"><?= $games ?></span>
            <?php endif; ?>
          </a>
          <?php endforeach; ?>
        </nav>
      </div>
    </section>
  </div>
</aside>
