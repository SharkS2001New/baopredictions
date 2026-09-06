<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>How We Predict | Bao Predictions</title>
  <meta name="description" content="How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/how-we-predict/">
  <meta name="robots" content="index,follow">
  <!--BAO_HEAD_EXTRA_START-->
  <meta name="title" content="How We Predict | Bao Predictions">
  <meta name="author" content="Bao Predictions Analysis Team">
  <meta name="date" content="<?php echo date('Y-m-d'); ?>">
  <meta property="article:published_time" content="<?php echo date('c'); ?>">
  <meta property="article:modified_time" content="<?php echo date('c'); ?>">
  <meta property="article:author" content="Bao Predictions">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="How We Predict | Bao Predictions">
  <meta name="twitter:description" content="How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean. 18+ only.">
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/how-we-predict/">
  <!--BAO_HEAD_EXTRA_END-->
  
  <meta property="og:title" content="How We Predict | Bao Predictions">
  <meta property="og:description" content="How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/how-we-predict/">
  <meta property="og:type" content="article">
  <meta property="og:site_name" content="Bao Predictions">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Source+Sans+3:wght@400;500;600&display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
  (function () {
    try {
      var t = localStorage.getItem('bao-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {}
  })();
  </script>
  <link rel="stylesheet" href="/assets/css/main.css">
<?php require __DIR__ . '/../components/favicon.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../components/header.php'; ?>
<main id="main">

    
<div class="wrap">
  
  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    
    <li>
      
      <a href="/">Home</a>
      
    </li>
    
    <li>
      
      <span aria-current="page">How We Predict</span>
      
    </li>
    
  </ol>
</nav>

  <header class="page-hero">
    <h1>How We Predict</h1>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_last_updated_html(); ?>
<p class="seo-unique">The canonical, most-detailed version of our methodology — data sources, human review, confidence ratings, and what we refuse to publish.</p>
<p class="seo-related"><strong>Related:</strong> <a href="/results">Results</a> · <a href="/about-us">About us</a> · <a href="/responsible-betting">Responsible betting</a></p>

</header>
  <article class="prose">
<p>We combine statistical modelling with human review, not one or the other. Every prediction starts with data — recent form, head-to-head history, home and away splits, and current squad availability — and is then checked by an analyst before it's published, because injury news and tactical changes don't always show up in a spreadsheet.</p>

<h2>What goes into every prediction</h2>
<ul>
  <li><strong>Recent form.</strong> We weight a team's last six results more heavily when they came in the same competition and at the same venue as the upcoming fixture — a team's away form in cup competitions doesn't tell you much about how they'll play at home in the league. Home and away splits matter: a side that dominates at home but leaks goals on the road should not be treated the same in both venues.</li>
  <li><strong>Head-to-head history.</strong> Past results between two sides, adjusted for the fact that squads and managers change — a rivalry's history matters less if half the players involved have moved on. We still read patterns (low-scoring derbies, perennial home dominance) when the current squads still look similar.</li>
  <li><strong>Team news.</strong> Confirmed injuries, suspensions, and rotation risk, checked as close to kickoff as possible so a prediction made on Tuesday still holds up on Saturday. Cup midweeks and international breaks raise rotation risk; we flag when a pick is fragile until the lineup is out.</li>
  <li><strong>Market odds.</strong> We compare our internal confidence rating against opening odds from major bookmakers — when the two disagree significantly, that's often the most interesting match to look at closely, not the one to ignore. Price movement after team news is part of the review, not a reason to flip a tip without a football reason.</li>
  <li><strong>Competition context.</strong> Title races, relegation scraps, European qualification, and "nothing to play for" change motivation. That is why Must-Win Teams is a separate shortlist from raw favourites.</li>
</ul>

<h2>What our confidence ratings mean</h2>
<ul>
  <li><strong>85–100%</strong> — our strongest picks, where form, history, and team news all point the same direction</li>
  <li><strong>70–84%</strong> — solid predictions with good reasoning behind them, but not without risk</li>
  <li><strong>50–69%</strong> — genuine 50/50 territory where we still see an edge, best suited to accumulators rather than single bets</li>
  <li><strong>Below 50%</strong> — we generally don't publish these; if the data doesn't support a clear lean, we say so rather than guessing</li>
</ul>
<p>Confidence is relative to our own process that day — it is not a promise of hit rate. Football is unpredictable; even our highest-confidence picks lose sometimes.</p>

<h2>Where our data comes from</h2>
<p>We pull fixture and statistical data from our fixtures feed <!--API: name your data provider, e.g. API-Football or SportMonks once integrated-->, covering team form, head-to-head records, and league standings. This is combined with manually tracked team news — injuries, suspensions, and confirmed lineups — checked as close to kickoff as the data allows.</p>

<h2>What we don't do</h2>
<p>We don't publish a prediction just to have one for every match on the calendar. If the data doesn't point clearly in a direction, we either publish it as a genuinely low-confidence pick and say so, or we leave it off the site entirely rather than dress up a guess as analysis.</p>
<p>We are not a bookmaker. We do not take stakes. Tips are informational. If you bet, use a licensed operator, stay 18+, and read our <a href="/responsible-betting">responsible betting</a> guide. Full settled outcomes live on the <a href="/results">results page</a>.</p>
</article>
</div>

  
  <section class="section"><div class="wrap"><h2 class="section-title">FAQ</h2><ul class="faq-list"><li><details><summary>Is confidence a win probability?</summary><p>No. It is our internal strength score for publishing and filtering (e.g. Must-Win at 85%+).</p></details></li><li><details><summary>Do humans review every tip?</summary><p>Yes — data starts the process; an analyst checks team news and publishes the final lean.</p></details></li><li><details><summary>Where can I see accuracy?</summary><p>On the Results page and Yesterday&#039;s Predictions — wins and losses both stay visible.</p></details></li></ul></div></section>
</main>
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
          <li><a href="/sportpesa-mega-jackpot-predictions">SportPesa Mega</a></li>
          <li><a href="/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a></li>
          <li><a href="/betika-midweek-jackpot-predictions">Betika Midweek</a></li>
          <li><a href="/sportybet-daily-jackpot-predictions">SportyBet Daily</a></li>
          <li><a href="/odibets-laki-tatu-predictions">Odibets Laki Tatu</a></li>
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
                    <div class="footer-disclaimer">
      <p>Predictions are for informational purposes only and do not guarantee outcomes. Betting involves financial risk — please gamble responsibly and only with money you can afford to lose. Must be 18+ (or the legal age in your jurisdiction). If gambling is affecting your life, contact <a href="https://www.begambleaware.org/" rel="noopener noreferrer" target="_blank">BeGambleAware.org</a> or your local support service.</p>
      <p>© <?php echo date('Y'); ?> Bao Predictions. All rights reserved.</p>
    </div>
  </div>
</footer>
<script src="/assets/js/timezone.js" defer></script>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_faq_schema(array (
  0 => 
  array (
    'q' => 'Is confidence a win probability?',
    'a' => 'No. It is our internal strength score for publishing and filtering (e.g. Must-Win at 85%+).',
  ),
  1 => 
  array (
    'q' => 'Do humans review every tip?',
    'a' => 'Yes — data starts the process; an analyst checks team news and publishes the final lean.',
  ),
  2 => 
  array (
    'q' => 'Where can I see accuracy?',
    'a' => 'On the Results page and Yesterday\'s Predictions — wins and losses both stay visible.',
  ),
)); echo bao_breadcrumb_schema(array (
  0 => 
  array (
    'name' => 'Home',
    'url' => '/',
  ),
  1 => 
  array (
    'name' => 'How We Predict',
    'url' => '/how-we-predict',
  ),
)); echo bao_article_schema('How We Predict', 'How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean. 18+ only.', '/how-we-predict'); echo bao_organization_schema(); ?>
<!--BAO_SCHEMA_END-->
</body>
</html>
