<?php
/**
 * Finished page copy from the Full Page Copy brief.
 * API slots use <!--API:...--> comments next to mock values still in templates.
 */
return [

'homepage' => [
  'title' => 'Football Predictions Today | Bao Predictions',
  'description' => 'Free daily football predictions with confidence ratings, backed by form and head-to-head stats. Updated every matchday.',
  'h1' => 'Football Predictions Today',
  'lede' => 'Know the odds before kickoff. Data-backed predictions for today\'s biggest fixtures — form, head-to-head history, and clear confidence ratings, updated every matchday.',
  'section_intro' => 'Below are today\'s predictions across the leagues we cover, ranked by confidence. Every pick shows our reasoning, not just a result — click into any match for the full breakdown.',
  'unique' => 'Below are today\'s predictions across the leagues we cover, ranked by confidence. Every pick shows our reasoning, not just a result — open the full today\'s page for the complete breakdown and late team-news updates.',
  'methodology_html' => <<<'HTML'
<section class="section">
  <div class="wrap prose">
    <h2>How Bao Predictions works</h2>
    <p>We combine statistical modelling with human review, not one or the other. Every prediction starts with data — recent form, head-to-head history, home and away splits, and current squad availability — and is then checked by an analyst before it's published, because injury news and tactical changes don't always show up in a spreadsheet.</p>
    <h3>What goes into every prediction</h3>
    <ul>
      <li><strong>Recent form.</strong> We weight a team's last six results more heavily when they came in the same competition and at the same venue as the upcoming fixture — a team's away form in cup competitions doesn't tell you much about how they'll play at home in the league.</li>
      <li><strong>Head-to-head history.</strong> Past results between two sides, adjusted for the fact that squads and managers change — a rivalry's history matters less if half the players involved have moved on.</li>
      <li><strong>Team news.</strong> Confirmed injuries, suspensions, and rotation risk, checked as close to kickoff as possible so a prediction made on Tuesday still holds up on Saturday.</li>
      <li><strong>Market odds.</strong> We compare our internal confidence rating against opening odds from major bookmakers — when the two disagree significantly, that's often the most interesting match to look at closely, not the one to ignore.</li>
    </ul>
    <h3>What our confidence ratings mean</h3>
    <ul>
      <li><strong>85–100%</strong> — our strongest picks, where form, history, and team news all point the same direction</li>
      <li><strong>70–84%</strong> — solid predictions with good reasoning behind them, but not without risk</li>
      <li><strong>50–69%</strong> — genuine 50/50 territory where we still see an edge, best suited to accumulators rather than single bets</li>
      <li><strong>Below 50%</strong> — we generally don't publish these; if the data doesn't support a clear lean, we say so rather than guessing</li>
    </ul>
    <p>No prediction is a guarantee. Football is unpredictable by nature, and even our highest-confidence picks lose sometimes. We publish our full track record on the <a href="/results">results page</a> so you can judge our accuracy for yourself rather than take our word for it. For the longer write-up, see <a href="/how-we-predict">How we predict</a>.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'How many predictions do you publish each day?', 'a' => 'It varies by matchday — typically 15 to 40 predictions across the leagues we cover, more on weekends when more leagues play.'],
    ['q' => 'Are your predictions free?', 'a' => 'Yes. Every prediction on this page is free to view, with full reasoning behind each pick.'],
    ['q' => 'How accurate are your predictions?', 'a' => 'We publish our results transparently on our results page, including losses. Historical accuracy is shown there, updated after every matchday.'],
    ['q' => 'Do you cover all football leagues?', 'a' => 'We focus on the major European leagues and continental competitions, plus leagues most relevant to our audience such as the Kenya Premier League. Coverage expands as demand grows.'],
  ],
  'rg' => true,
  'related' => [
    ['Today\'s full list', '/football-predictions-today'],
    ['Jackpot predictions', '/jackpot-predictions'],
    ['Results', '/results'],
  ],
  'crumbs' => [['name' => 'Home', 'url' => '/']],
],

'football-predictions-today' => [
  'title_tpl' => 'Football Predictions Today, {date} | Bao Predictions',
  'description' => 'Today\'s football predictions — every fixture, pick, and confidence rating across the leagues we cover. Updated live.',
  'h1_tpl' => 'Football Predictions for {date}',
  'unique' => 'Here\'s every match we\'re covering today, sorted by confidence. Each pick includes the reasoning behind it — recent form, head-to-head record, and any team news that affects the outcome. Check back through the day; we update predictions if late team news changes the picture before kickoff.',
  'headline_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>Today's headline fixture</h2>
    <p><!--API: write 100–150 words daily on today's most-watched match — do not template this --></p>
    <p>Manchester City host Chelsea in the clearest percentage lean on today's card: City's home expected-goals profile remains elite, while Chelsea's away defensive structure has leaked chances against top-half sides. We have it as a high-confidence home selection — still not a lock, but the strongest single on the board before the evening fixtures. Der Klassiker is tagged for goals rather than a raw 1X2 hammer; the Gor Mahia–Tusker derby stays a low-scoring home lean typical of Kenyan Premier League intensity. Re-check lineups about an hour before kickoff.</p>
    <p><a href="/1x2-predictions">See all 1X2 predictions</a> · <a href="/must-win-teams-today">Must-win shortlist</a></p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'How many matches are predicted today?', 'a' => 'It varies by matchday — the tips table above lists every published pick across the leagues we cover today.'],
    ['q' => 'What\'s today\'s most confident pick?', 'a' => 'Scan the tips table for the highest confidence percentage, or open Must-Win Teams for 85%+ only.'],
    ['q' => 'When are predictions updated?', 'a' => 'We publish initial predictions the evening before, then review and update them through matchday if there\'s late team news.'],
  ],
  'rg' => true,
  'related' => [['Tomorrow', '/football-predictions-tomorrow'], ['Must-win today', '/must-win-teams-today'], ['1X2 predictions', '/1x2-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Today', 'url' => '/football-predictions-today']],
],

'football-predictions-tomorrow' => [
  'title_tpl' => 'Football Predictions Tomorrow, {date} | Bao Predictions',
  'description' => 'Tomorrow\'s football predictions — get ahead of kickoff with picks, confidence ratings, and reasoning for every fixture we cover.',
  'h1_tpl' => 'Football Predictions for Tomorrow, {date}',
  'unique' => 'Planning ahead of matchday? Here are our predictions for tomorrow\'s fixtures. These are based on the latest team news available today and will be reviewed again closer to kickoff — check the "last updated" time before you rely on any pick that involves a genuinely late fitness call.',
  'faqs' => [
    ['q' => 'Why might tomorrow\'s predictions change?', 'a' => 'Late injury news, suspensions, or lineup announcements can shift a prediction. We flag any pick that\'s likely to move once team news is confirmed.'],
    ['q' => 'Can I see the full week\'s fixtures at once?', 'a' => 'Yes — visit our weekend predictions page for Saturday/Sunday fixtures grouped together.'],
    ['q' => 'Are tomorrow\'s tips free?', 'a' => 'Yes. Every prediction is free to view with reasoning included.'],
  ],
  'rg' => true,
  'related' => [['Weekend predictions', '/weekend-football-predictions'], ['Today', '/football-predictions-today'], ['BTTS predictions', '/btts-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Tomorrow', 'url' => '/football-predictions-tomorrow']],
  'tomorrow' => true,
],

'football-predictions-yesterday' => [
  'title' => 'Yesterday\'s Football Predictions — Results | Bao Predictions',
  'description' => 'See how yesterday\'s football predictions performed — every pick, the actual result, and whether it won. Full transparency, no hidden losses.',
  'h1' => 'Yesterday\'s Predictions & Results',
  'unique' => 'This is where you can check our track record before trusting today\'s picks. Every prediction we made yesterday is listed below alongside the actual result — wins and losses both. If a pick didn\'t work out, we don\'t remove it from this page.',
  'trust_html' => '<p class="seo-unique">We track every published prediction from the moment it goes live, and this page updates automatically once matches finish. If you want the longer-term picture rather than a single day, our full <a href="/results">results and statistics page</a> breaks down accuracy by market and over time.</p>',
  'faqs' => [
    ['q' => 'Do you remove predictions that lose?', 'a' => 'No. Every published prediction stays visible on this page regardless of outcome. A tips site that only shows its wins isn\'t one you should trust.'],
    ['q' => 'How is win rate calculated?', 'a' => 'We count a prediction as won if the specific outcome predicted matched what actually happened. Postponed or abandoned matches are excluded and noted separately.'],
    ['q' => 'Where is the longer track record?', 'a' => 'See the Results page for win rate, ROI, and history beyond a single day.'],
  ],
  'rg' => true,
  'related' => [['Full results', '/results'], ['Today\'s predictions', '/football-predictions-today'], ['How we predict', '/how-we-predict']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Yesterday', 'url' => '/football-predictions-yesterday']],
],

'must-win-teams-today' => [
  'title' => 'Must-Win Teams Today | Bao Predictions',
  'description' => 'Today\'s must-win teams — sides under real pressure to get a result, with our analysis of why and our prediction for each match.',
  'h1' => 'Must-Win Teams Today',
  'unique' => 'A "must-win" team isn\'t just a favourite — it\'s a side under specific pressure to get a result today: fighting relegation with a shrinking run-in, chasing a European qualification spot with rivals closing the gap, or needing a win to save a manager\'s job. We flag these matches separately because motivation genuinely affects performance in ways raw form data doesn\'t always capture, and it\'s a different kind of bet than a simple form-based favourite. On Bao we also require 85%+ confidence before a pick appears here.',
  'faqs' => [
    ['q' => 'What makes a team "must-win" rather than just a favourite?', 'a' => 'Table pressure — relegation, European qualification, or a title race — combined with strong underlying form. A top team can be a betting favourite without being under this kind of pressure; we only tag matches where both apply.'],
    ['q' => 'Are must-win picks higher risk or lower risk?', 'a' => 'Generally lower risk in terms of motivation (a desperate team tries harder), but pressure can also cause a side to underperform. We factor this into the confidence rating rather than treating "must-win" as automatically safe.'],
    ['q' => 'Can I build an acca from must-wins?', 'a' => 'Yes — start here, then check Accumulator Tips for pre-built tickets.'],
  ],
  'rg' => true,
  'related' => [['Sure bets today', '/sure-bets-today'], ['Today\'s full list', '/football-predictions-today'], ['Accumulator tips', '/accumulator-tips']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Must-Win', 'url' => '/must-win-teams-today']],
],

'sure-bets-today' => [
  'title' => 'Sure Bets Today — High Confidence Football Picks | Bao Predictions',
  'description' => 'Today\'s highest-confidence football predictions, backed by form and stats. No prediction is guaranteed — see our reasoning for every pick.',
  'h1' => 'Today\'s Highest-Confidence Picks',
  'unique' => '"Sure bet" is a phrase people search for, so it\'s the page title — but we want to be upfront: nothing in football is actually sure. What you\'ll find below are our highest-confidence predictions (80%+), meaning the ones where recent form, head-to-head history, and team news all point the same direction most strongly. High confidence isn\'t a guarantee, and even our strongest picks lose sometimes. Check our results page to see exactly how often.',
  'faqs' => [
    ['q' => 'What does "sure bet" mean on this page?', 'a' => 'It reflects search habits, not a promise — we use it because it\'s how people look for this kind of content, but every pick below carries real risk. See our confidence-rating explanation on the homepage for what the percentages actually mean.'],
    ['q' => 'How often do your highest-confidence picks win?', 'a' => 'See our transparent results page for the actual historical rate — we don\'t want to state a number here that goes stale.'],
    ['q' => 'Do you offer fixed odds yourself?', 'a' => 'No. Bao Predictions is not a bookmaker.'],
  ],
  'rg' => true,
  'related' => [['Must-win teams today', '/must-win-teams-today'], ['Results', '/results'], ['Responsible betting', '/responsible-betting']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Sure Bets', 'url' => '/sure-bets-today']],
],

'betnumbers-tips' => [
  'title' => 'BetNumbers Tips — Mixed Market Picks | Bao Predictions',
  'description' => 'BetNumbers tips mix 1X2, BTTS, Over/Under 2.5, and Double Chance — best winning chance with a fitting price on each game.',
  'h1' => 'BetNumbers Tips Today',
  'unique' => 'BetNumbers tips are not locked to match result. Some games are clearer on goals or both teams to score than on 1X2. We score those four markets and keep one tip per fixture: strongest win chance first, then odds that fit.',
  'faqs' => [
    ['q' => 'Which markets are compared?', 'a' => '1X2, BTTS, Over/Under 2.5, and Double Chance.'],
    ['q' => 'How is the market chosen?', 'a' => 'Highest model chance first; if close, the best-fitting book odds win.'],
    ['q' => 'Same as Sure Bets?', 'a' => 'Same engine; Sure Bets and Must-Win use higher confidence filters.'],
  ],
  'rg' => true,
  'related' => [['Sure bets today', '/sure-bets-today'], ['Must-win teams today', '/must-win-teams-today'], ['1X2 predictions', '/1x2-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'BetNumbers Tips', 'url' => '/betnumbers-tips']],
],

];
