<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <h3>Predictions</h3>
        <ul>
          <li><a href="/football-predictions-today">Today</a></li>
          <li><a href="/football-predictions-tomorrow">Tomorrow</a></li>
          <li><a href="/football-predictions-yesterday">Yesterday</a></li>
          <li><a href="/weekend-football-predictions">Weekend</a></li>
          <li><a href="/must-win-teams-today">Must-Win</a></li>
          <li><a href="/sure-bets-today">Sure Bets</a></li>
          <li><a href="/accumulator-tips">Accumulators</a></li>
        </ul>
      </div>
      <div>
        <h3>Markets</h3>
        <ul>
          <li><a href="/1x2-predictions">1X2</a></li>
          <li><a href="/double-chance-predictions">Double Chance</a></li>
          <li><a href="/over-under-predictions">Over/Under</a></li>
          <li><a href="/btts-predictions">BTTS</a></li>
          <li><a href="/ht-ft-predictions">HT/FT</a></li>
        </ul>
      </div>
      <div>
        <h3>Jackpots</h3>
        <ul>
          <li><a href="/jackpot-predictions">All Jackpots</a></li>
          <li><a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega</a></li>
          <li><a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a></li>
          <li><a href="/jackpots/betika-midweek-jackpot-predictions">Betika Midweek</a></li>
          <li><a href="/jackpots/sportybet-daily-jackpot-predictions">SportyBet Daily</a></li>
          <li><a href="/jackpots/odibets-laki-tatu-predictions">Odibets Laki Tatu</a></li>
        </ul>
      </div>
      <div>
        <h3>Site</h3>
        <ul>
          <li><a href="/how-we-predict">How We Predict</a></li>
          <li><a href="/results">Results</a></li>
          <li><a href="/blog">Blog</a></li>
          <li><a href="/about-us">About</a></li>
          <li><a href="/faq">FAQ</a></li>
          <li><a href="/contact-us">Contact</a></li>
        </ul>
      </div>
      <div>
        <h3>Legal</h3>
        <ul>
          <li><a href="/responsible-betting">Responsible Betting</a></li>
          <li><a href="/privacy-policy">Privacy Policy</a></li>
          <li><a href="/terms-of-service">Terms of Service</a></li>
        </ul>
      </div>
    </div>
<?php
    try {
        $baoFooterRoot = dirname(__DIR__);
        $baoAutoload = $baoFooterRoot . '/vendor/autoload.php';
        if (is_file($baoAutoload)) {
            require_once $baoAutoload;
        }
        if (! class_exists(\App\Services\FooterSponsorsService::class, false)) {
            require_once $baoFooterRoot . '/src/Services/FooterSponsorsService.php';
        }
        $baoFooterSponsors = (new \App\Services\FooterSponsorsService())->visibleLinks();
    } catch (Throwable $e) {
        $baoFooterSponsors = [];
    }
?>
    <div class="footer-disclaimer">
      <p>Predictions are for informational purposes only and do not guarantee outcomes. Betting involves financial risk — please gamble responsibly and only with money you can afford to lose. Must be 18+ (or the legal age in your jurisdiction). If gambling is affecting your life, contact <a href="https://www.begambleaware.org/" rel="noopener noreferrer" target="_blank">BeGambleAware.org</a> or your local support service.</p>
    </div>
<?php if (!empty($baoFooterSponsors)): ?>
    <div class="footer-sponsors">
      <p class="footer-sponsors-label">Our Partners &amp; Sponsors</p>
      <div class="footer-sponsor-links">
<?php foreach ($baoFooterSponsors as $sponsor): ?>
<?php
  $sponsorUrl = trim((string) ($sponsor['url'] ?? ''));
  $sponsorUrl = preg_replace('#\./+#', '/', $sponsorUrl) ?? $sponsorUrl;
  $sponsorUrl = rtrim($sponsorUrl, " \t.");
  $sponsorLabel = trim((string) ($sponsor['label'] ?? ''));
  $sponsorLabel = preg_replace('#\./+#', '/', $sponsorLabel) ?? $sponsorLabel;
  $sponsorLabel = rtrim($sponsorLabel, " \t.");
  if ($sponsorLabel === '') {
      $sponsorLabel = $sponsorUrl;
  }
  // Prefer a clean hostname when the admin label is just the raw URL.
  if ($sponsorLabel === $sponsorUrl || preg_match('#^https?://#i', $sponsorLabel)) {
      $host = parse_url($sponsorUrl !== '' ? $sponsorUrl : $sponsorLabel, PHP_URL_HOST);
      if (is_string($host) && $host !== '') {
          $sponsorLabel = $host;
      }
  }
?>
        <a
          href="<?php echo htmlspecialchars($sponsorUrl !== '' ? $sponsorUrl : (string) ($sponsor['url'] ?? '#'), ENT_QUOTES, 'UTF-8'); ?>"
          rel="<?php echo htmlspecialchars(implode(' ', $sponsor['rel'] ?? ['noopener', 'noreferrer']), ENT_QUOTES, 'UTF-8'); ?>"
          target="_blank"
        ><?php echo htmlspecialchars($sponsorLabel, ENT_QUOTES, 'UTF-8'); ?></a>
<?php endforeach; ?>
      </div>
    </div>
<?php endif; ?>
    <p class="footer-copy">&copy; <?php echo date('Y'); ?> Bao Predictions. All rights reserved.</p>
  </div>
</footer>
