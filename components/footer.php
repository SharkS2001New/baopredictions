<footer class="site-footer">
  <div class="wrap">
<?php
    $baoBrandLandings = [];
    try {
        $baoBrandLandings = require dirname(__DIR__) . '/config/brand-landings.php';
        if (!is_array($baoBrandLandings)) {
            $baoBrandLandings = [];
        }
    } catch (Throwable $e) {
        $baoBrandLandings = [];
    }
?>
    <div class="footer-grid footer-grid--seo">
      <div>
        <h3>Jackpots</h3>
        <ul>
          <li><a href="/jackpot-predictions">Jackpot Predictions Hub</a></li>
          <li><a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a></li>
          <li><a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek Jackpot Predictions</a></li>
          <li><a href="/jackpots/betika-midweek-jackpot-predictions">Betika Midweek Jackpot Predictions</a></li>
          <li><a href="/jackpots/sportybet-daily-jackpot-predictions">SportyBet Daily Jackpot Predictions</a></li>
          <li><a href="/jackpots/odibets-laki-tatu-predictions">Odibets Laki Tatu Jackpot Predictions</a></li>
          <li><a href="/jackpots/mozzart-super-daily-jackpot-predictions">Mozzart Super Daily Jackpot Predictions</a></li>
          <li><a href="/mega-jackpot-strategy-guide">Mega Jackpot Strategy Guide</a></li>
        </ul>
      </div>
      <div>
        <h3>Free Predictions</h3>
        <ul>
          <li><a href="/football-predictions-today">Football Predictions Today</a></li>
          <li><a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a></li>
          <li><a href="/football-predictions-yesterday">Football Predictions Yesterday</a></li>
          <li><a href="/weekend-football-predictions">Weekend Football Predictions</a></li>
          <li><a href="/live-football-predictions">Live Football Predictions</a></li>
          <li><a href="/must-win-teams-today">Must Win Teams Today</a></li>
          <li><a href="/sure-bets-today">Sure Bets Today</a></li>
          <li><a href="/banker-of-the-day">Banker of the Day</a></li>
          <li><a href="/accumulator-tips">Accumulator Tips Today</a></li>
          <li><a href="/betnumbers-tips">Bet Numbers Tips Today</a></li>
          <li><a href="/sokafans-predictions">SokaFans Predictions</a></li>
          <li><a href="/cheerplex-tips">Cheerplex Predictions &amp; Tips</a></li>
          <li><a href="/sunpel-prediction">Sunpel Prediction</a></li>
          <li><a href="/venasbet-predictions">VenasBet Predictions</a></li>
<?php foreach ($baoBrandLandings as $baoBrandSlug => $baoBrandMeta):
  if (!is_string($baoBrandSlug) || $baoBrandSlug === '' || !is_array($baoBrandMeta)) {
      continue;
  }
  $baoBrandLabel = trim((string) ($baoBrandMeta['breadcrumb'] ?? $baoBrandMeta['brand'] ?? ''));
  if ($baoBrandLabel === '') {
      continue;
  }
?>
          <li><a href="/<?php echo htmlspecialchars($baoBrandSlug, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($baoBrandLabel, ENT_QUOTES, 'UTF-8'); ?></a></li>
<?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h3>Betting Markets</h3>
        <ul>
          <li><a href="/1x2-predictions">1X2 Predictions Today</a></li>
          <li><a href="/double-chance-predictions">Double Chance Predictions</a></li>
          <li><a href="/over-under-predictions">Over/Under Predictions</a></li>
          <li><a href="/btts-predictions">BTTS Predictions Today</a></li>
          <li><a href="/ht-ft-predictions">HT/FT Predictions</a></li>
          <li><a href="/how-to-read-btts-odds">How to Read BTTS Odds</a></li>
          <li><a href="/results">Football Prediction Results</a></li>
        </ul>
      </div>
      <div>
        <h3>Quick Links</h3>
        <ul>
          <li><a href="/how-we-predict">How We Predict</a></li>
          <li><a href="/about-us">About Bao Predictions</a></li>
          <li><a href="/blog">Football Predictions Blog</a></li>
          <li><a href="/faq">Football Predictions FAQ</a></li>
          <li><a href="/partners">Partners &amp; Link Exchange</a></li>
          <li><a href="/sitemaps">Sitemaps</a></li>
          <li><a href="/contact-us">Contact Us</a></li>
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
