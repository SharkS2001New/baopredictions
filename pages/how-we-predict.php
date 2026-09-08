<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>How We Predict | Bao Predictions</title>
  <meta name="description" content="How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean. 18+ only.">
  <link rel="canonical" href="https://www.baopredictions.com/how-we-predict">
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
  <link rel="alternate" hreflang="en" href="https://www.baopredictions.com/how-we-predict">
  <!--BAO_HEAD_EXTRA_END-->
  
  <meta property="og:title" content="How We Predict | Bao Predictions">
  <meta property="og:description" content="How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean. 18+ only.">
  <meta property="og:url" content="https://www.baopredictions.com/how-we-predict">
  <meta property="og:type" content="article">
  <meta property="og:site_name" content="Bao Predictions">
    <script>
  (function () {
    try {
      var t = localStorage.getItem('bao-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {}
  })();
  </script>
<?php require __DIR__ . '/../components/head-assets.php'; ?>
<?php require __DIR__ . '/../components/favicon.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../components/header.php'; ?>
<main id="main">

    
<div class="wrap">
  
  <nav aria-label="Breadcrumb">
  <ol class="breadcrumbs">
    <li><a href="/">Home</a></li>
    <li><span aria-current="page">How We Predict</span></li>
  </ol>
</nav>

  <header class="page-hero">
    <h1>How We Predict</h1>
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_last_updated_html(); ?>
<p class="lede">Data sources, human review, confidence ratings, and what we refuse to publish — the full methodology behind every tip on this site.</p>
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
  <li><strong>75–85%</strong> — our strongest published picks, where form, history, and team news all point the same direction. Published cards are hard-capped at 85%; we never show 100%, because that would read as a guarantee.</li>
  <li><strong>60–74%</strong> — solid predictions with good reasoning behind them, but not without risk</li>
  <li><strong>55–59%</strong> — thinner edges that still clear our publish floor; better as accumulator legs than heavy singles</li>
  <li><strong>Below 55%</strong> — we don't publish these on tip boards; if the data doesn't support a clear lean, we leave the fixture off rather than dressing up a guess</li>
</ul>
<p>Confidence is relative to our own process that day — it is not a promise of hit rate. Football is unpredictable; even our highest-confidence picks lose sometimes. Shortlists apply higher bars on top of this scale: <a href="/must-win-teams-today">Must-Win</a> is match-result (1X2) tips at <strong>75%+</strong>; <a href="/sure-bets-today">Sure Bets</a> is the mixed-market band at roughly <strong>78%+</strong>.</p>

<h2>Where our data comes from</h2>
<p>We pull fixture and statistical data from our fixtures feed, covering team form, head-to-head records, and league standings. This is combined with manually tracked team news — injuries, suspensions, and confirmed lineups — checked as close to kickoff as the data allows.</p>

<h2>What we don't do</h2>
<p>We don't publish a prediction just to have one for every match on the calendar. If the data doesn't point clearly in a direction, we either publish it as a genuinely low-confidence pick and say so, or we leave it off the site entirely rather than dress up a guess as analysis.</p>
<p>We are not a bookmaker. We do not take stakes. Tips are informational. If you bet, use a licensed operator, stay 18+, and read our <a href="/responsible-betting">responsible betting</a> guide. Full settled outcomes live on the <a href="/results">results page</a>.</p>
</article>
</div>

  
  <section class="section"><div class="wrap"><h2 class="section-title">FAQ</h2><ul class="faq-list"><li><details><summary>Is confidence a win probability?</summary><p>No. It is our internal strength score for publishing and filtering — for example Must-Win is 1X2 tips at 75%+, and Sure Bets is the mixed-market band at roughly 78%+. Cards never display above 85% or at 100%.</p></details></li><li><details><summary>Do humans review every tip?</summary><p>Yes — data starts the process; an analyst checks team news and publishes the final lean.</p></details></li><li><details><summary>Where can I see accuracy?</summary><p>On the Results page and Yesterday&#039;s Predictions — wins and losses both stay visible.</p></details></li></ul></div></section>
</main>
  <?php require __DIR__ . '/../components/footer.php'; ?>
<script src="/assets/js/theme.js" defer></script>
<!--BAO_SCHEMA_START-->
<?php require_once __DIR__ . '/../components/seo.php'; echo bao_faq_schema(array (
  0 => 
  array (
    'q' => 'Is confidence a win probability?',
    'a' => 'No. It is our internal strength score for publishing and filtering — for example Must-Win is 1X2 tips at 75%+, and Sure Bets is the mixed-market band at roughly 78%+. Cards never display above 85% or at 100%.',
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
