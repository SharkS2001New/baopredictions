<?php
return [

'how-we-predict' => [
  'title' => 'How We Predict | Bao Predictions',
  'description' => 'How Bao Predictions builds every football prediction — our data sources, review process, and what our confidence ratings actually mean.',
  'h1' => 'How We Predict',
  'unique' => 'The canonical, most-detailed version of our methodology — data sources, human review, confidence ratings, and what we refuse to publish.',
  'body_html' => <<<'HTML'
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
HTML,
  'faqs' => [
    ['q' => 'Is confidence a win probability?', 'a' => 'No. It is our internal strength score for publishing and filtering (e.g. Must-Win at 85%+).'],
    ['q' => 'Do humans review every tip?', 'a' => 'Yes — data starts the process; an analyst checks team news and publishes the final lean.'],
    ['q' => 'Where can I see accuracy?', 'a' => 'On the Results page and Yesterday\'s Predictions — wins and losses both stay visible.'],
  ],
  'rg' => false,
  'related' => [['Results', '/results'], ['About us', '/about-us'], ['Responsible betting', '/responsible-betting']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'How We Predict', 'url' => '/how-we-predict']],
],

'about-us' => [
  'title' => 'About Us | Bao Predictions',
  'description' => 'About Bao Predictions — who we are, how we work, and why we publish our results transparently.',
  'h1' => 'About Bao Predictions',
  'unique' => 'Bao Predictions publishes daily football predictions backed by statistical analysis and human review — with a public track record that includes losses.',
  'body_html' => <<<'HTML'
<p>Bao Predictions publishes daily football predictions backed by statistical analysis and human review. We built this site because most prediction sites either hide their losses or bury their reasoning behind vague confidence claims — we do neither. Every pick we publish stays visible whether it wins or loses, and every prediction includes the reasoning behind it, not just a result.</p>
<p>We cover single-match predictions across major betting markets, plus the football jackpots run by Kenya's major bookmakers, with the same standard applied throughout: real reasoning, honest track record, no guarantees.</p>
<p><!--Add real founder/team bio once available — even a short one materially helps E-E-A-T.--></p>
<p>If you have questions about how we work, our <a href="/how-we-predict">How We Predict</a> page has the full breakdown, and our <a href="/results">results page</a> has the numbers.</p>
HTML,
  'faqs' => [
    ['q' => 'Are you a bookmaker?', 'a' => 'No. We publish analysis only.'],
    ['q' => 'Where are you focused?', 'a' => 'Kenya-facing bookmakers and readers, with major European leagues plus Kenyan Premier League coverage.'],
    ['q' => 'How do I contact you?', 'a' => 'Use the Contact page for partnerships and corrections.'],
  ],
  'rg' => false,
  'related' => [['How we predict', '/how-we-predict'], ['FAQ', '/faq'], ['Contact', '/contact-us']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'About', 'url' => '/about-us']],
],

'faq' => [
  'title' => 'FAQ | Bao Predictions',
  'description' => 'Frequently asked questions about Bao Predictions — how we work, how accurate we are, and how to use our predictions responsibly.',
  'h1' => 'Frequently Asked Questions',
  'unique' => 'Straight answers about how we work, how accurate we are, and how to use our predictions responsibly.',
  'faqs' => [
    ['q' => 'What makes Bao Predictions different from other prediction sites?', 'a' => 'We publish our full track record, including losses, and every prediction comes with the reasoning behind it rather than just a pick and a percentage.'],
    ['q' => 'Can any football prediction be guaranteed?', 'a' => 'No. Football has genuine unpredictability built in — a single goal, red card, or refereeing call can change a result regardless of how strong the underlying data looked beforehand. Treat every prediction, including our highest-confidence ones, as informed analysis, not a certainty.'],
    ['q' => 'Is Bao Predictions free to use?', 'a' => 'Yes, all predictions on the site are free to view.'],
    ['q' => 'How often are predictions updated?', 'a' => 'Daily, with jackpot pages updated according to each jackpot\'s own schedule (weekly for most, daily for SportyBet\'s).'],
    ['q' => 'Do you encourage betting?', 'a' => 'We publish analysis for people who are already choosing to bet. We\'re not encouraging anyone to start, and we take responsible gambling seriously — see our responsible betting page.'],
  ],
  'rg' => false,
  'related' => [['Responsible betting', '/responsible-betting'], ['How we predict', '/how-we-predict'], ['Results', '/results']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'FAQ', 'url' => '/faq']],
],

'responsible-betting' => [
  'title' => 'Responsible Betting | Bao Predictions',
  'description' => 'Responsible gambling guidance and support resources — read this before betting on any prediction published on this site.',
  'h1' => 'Responsible Betting',
  'unique' => 'Predictions on this site are informational, not a guarantee. Read this before betting on any tip we publish.',
  'body_html' => <<<'HTML'
<p>Predictions on this site are informational, not a guarantee of any outcome. Sports betting carries real financial risk, and we want anyone using this site to bet within their means, not beyond them.</p>
<h2>A few guidelines worth following</h2>
<ul>
  <li>Only bet money you can genuinely afford to lose</li>
  <li>Set a budget before you start, and stop when you reach it, win or lose</li>
  <li>Don't chase losses by increasing your stake to make up for a losing run</li>
  <li>Treat betting as entertainment, not as an income strategy</li>
  <li>Take breaks — if it stops being enjoyable, that's a sign to step away</li>
  <li>Only use licensed, regulated bookmakers</li>
</ul>
<p>If gambling is affecting your life, support is available. Contact <a href="https://www.begambleaware.org/" rel="noopener noreferrer" target="_blank">BeGambleAware.org</a>, GamCare, or your local gambling support service. <!--Add a Kenya-specific helpline if one becomes available at launch.--></p>
<p>This site is intended for users 18 and older, or the legal betting age in your jurisdiction if higher.</p>
HTML,
  'faqs' => [
    ['q' => 'Are tips a guarantee?', 'a' => 'No. Never stake money you cannot afford to lose.'],
    ['q' => 'What age is required?', 'a' => '18+ or the legal betting age in your jurisdiction if higher.'],
    ['q' => 'Where can I get help?', 'a' => 'BeGambleAware.org, GamCare, or your local support service.'],
  ],
  'rg' => false,
  'related' => [['FAQ', '/faq'], ['About us', '/about-us'], ['Home', '/']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Responsible Betting', 'url' => '/responsible-betting']],
],

'contact-us' => [
  'title' => 'Contact Us | Bao Predictions',
  'description' => 'Contact Bao Predictions — partnerships, corrections, and media enquiries.',
  'h1' => 'Contact Us',
  'unique' => 'For tip corrections, partnership enquiries, or press, use the form or email below. We read every message; response times vary on matchdays.',
  'faqs' => [
    ['q' => 'How fast do you reply?', 'a' => 'Usually within a few business days; slower on heavy match weekends.'],
    ['q' => 'Can I request a league?', 'a' => 'Yes — tell us which competition and why it matters to Kenyan bettors.'],
    ['q' => 'Where do I report a wrong score?', 'a' => 'Email us with the fixture, published tip, and correct result — we fix settled records promptly.'],
  ],
  'rg' => false,
  'related' => [['About us', '/about-us'], ['FAQ', '/faq'], ['How we predict', '/how-we-predict']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Contact', 'url' => '/contact-us']],
],

];
