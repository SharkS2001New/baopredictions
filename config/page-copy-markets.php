<?php
return [

'accumulator-tips' => [
  'title' => 'Accumulator Tips Today | Bao Predictions',
  'description' => 'Today\'s best accumulator tips — pre-built 3-fold, 5-fold, and 8-fold accas with combined odds, plus how accumulator odds actually work.',
  'h1' => 'Accumulator Tips Today',
  'unique' => 'An accumulator combines multiple picks into one bet — all of them need to win for the bet to pay out, but the combined odds multiply, so a small stake can return a lot more than a single bet. Below are three pre-built accumulators at different risk levels, plus an explainer if you\'re building your own.',
  'explainer_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>How accumulator odds work</h2>
    <p>Each selection's odds multiply together to give the combined odds for the whole accumulator. Two picks at odds of 1.80 and 2.00 combine to 3.60 (1.80 × 2.00) — a $10 stake returns $36 if both win, compared to $18 and $20 if you'd backed them separately.</p>
    <p>The tradeoff is risk: every leg has to win. A 5-fold accumulator with each leg at 80% likelihood doesn't have an 80% chance of winning overall — multiply the probabilities and it drops fast (0.8⁵ ≈ 33%). That's why we build accumulators from picks that genuinely reinforce each other — matches where the reasoning is strong individually — rather than just stacking as many legs as possible for a bigger headline number.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'How many selections should an accumulator have?', 'a' => 'There\'s no fixed answer — more selections mean higher potential returns but lower odds of winning overall. Our 3-fold accas are built for a realistic chance of landing; our 8-folds are higher-risk, higher-reward.'],
    ['q' => 'What happens if one match in my accumulator is postponed?', 'a' => 'Bookmaker rules vary — most either void that leg (recalculating odds without it) or void the whole bet. Check your specific bookmaker\'s terms before placing a multi-leg bet.'],
    ['q' => 'Where do the legs come from?', 'a' => 'From today\'s published tips list, preferring higher-confidence selections that still leave a sensible combined price.'],
  ],
  'rg' => true,
  'related' => [['Sure bets today', '/sure-bets-today'], ['1X2 predictions', '/1x2-predictions'], ['Today\'s tips', '/football-predictions-today']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Accumulators', 'url' => '/accumulator-tips']],
],

'1x2-predictions' => [
  'title' => '1X2 Predictions Today | Bao Predictions',
  'description' => 'Today\'s 1X2 match result predictions — home win, draw, or away win — with confidence ratings and reasoning for every fixture.',
  'h1' => '1X2 Predictions Today',
  'unique' => '1X2 is the simplest football bet: pick the match result — 1 for a home win, X for a draw, 2 for an away win. It\'s the most heavily bet-on market because it\'s the most intuitive, but it\'s also the hardest to get consistently right, since a draw is always a live outcome even when one team is clearly stronger. Our 1X2 predictions weigh recent form and head-to-head history specifically for draw frequency, not just which team is "better," since plenty of strong favourites still draw against well-organised weaker sides.',
  'faqs' => [
    ['q' => 'What does 1X2 mean?', 'a' => '1 = home win, X = draw, 2 = away win. It\'s the standard match-result market offered by every bookmaker.'],
    ['q' => 'Why do favourites sometimes lose in 1X2 betting?', 'a' => 'Football has more variance than most sports — a single goal, red card, or refereeing decision can flip a result regardless of the pre-match form gap. That\'s why we publish a confidence rating rather than treating any pick as certain.'],
    ['q' => 'Do you cover Kenyan fixtures too?', 'a' => 'Yes — when Kenya Premier League fixtures are on the day\'s slate, they appear in this list with the same format as European games.'],
  ],
  'rg' => true,
  'related' => [['Double chance', '/double-chance-predictions'], ['Must-win teams', '/must-win-teams-today'], ['How we predict', '/how-we-predict']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => '1X2', 'url' => '/1x2-predictions']],
],

'double-chance-predictions' => [
  'title' => 'Double Chance Predictions Today | Bao Predictions',
  'description' => 'Today\'s double chance predictions — covering two of three possible outcomes for lower-risk football betting, with reasoning for every pick.',
  'h1' => 'Double Chance Predictions Today',
  'unique' => 'Double chance lets you cover two of the three possible 1X2 outcomes in a single bet — 1X (home win or draw), X2 (draw or away win), or 12 (either team wins, no draw). It pays lower odds than a straight match-result bet because you\'re covering more ground, but it\'s a genuine way to reduce risk on matches where you\'re confident about ruling out one specific outcome rather than picking the exact result.',
  'faqs' => [
    ['q' => 'When is double chance a better bet than 1X2?', 'a' => 'When you\'re confident a specific outcome won\'t happen (e.g. an away win is very unlikely) but you\'re less sure whether it\'ll be a home win or a draw — 1X covers both at once.'],
    ['q' => 'Does double chance ever pay even money or better?', 'a' => 'Rarely — because you\'re covering two of three outcomes, the odds are usually well below 2.00 unless the match is very close to a coin flip.'],
    ['q' => 'Is double chance useful for jackpots?', 'a' => 'Some bookmakers allow double-chance legs on specials; for classic 17-game Mega Jackpots you still need straight 1X2. Use DC on singles and smaller multi-bets.'],
  ],
  'rg' => true,
  'related' => [['1X2 predictions', '/1x2-predictions'], ['Sure bets', '/sure-bets-today'], ['Responsible betting', '/responsible-betting']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Double Chance', 'url' => '/double-chance-predictions']],
],

'over-under-predictions' => [
  'title' => 'Over/Under Goals Predictions Today | Bao Predictions',
  'description' => 'Today\'s over/under predictions — 1.5, 2.5, and 3.5 goal lines — based on team scoring and defensive form.',
  'h1' => 'Over/Under Predictions Today',
  'unique' => 'Over/under betting is on total goals in a match, not who wins. The most common line is 2.5 goals — bet "over" if you expect 3 or more goals combined, "under" if you expect 2 or fewer. We base these predictions on both teams\' recent scoring and conceding rates, not just one side\'s attack, since a high-scoring team facing a very defensive opponent can still produce a low-scoring match.',
  'faqs' => [
    ['q' => 'What\'s the most common over/under line?', 'a' => '2.5 goals is standard, though 1.5 and 3.5 are also widely offered, especially for matches expected to be unusually low- or high-scoring.'],
    ['q' => 'Does home advantage affect over/under predictions?', 'a' => 'Yes — home teams generally score more and concede less than they do away, so the same two teams can produce a different expected goal total depending on venue.'],
    ['q' => 'How do you treat weather or cup rotations?', 'a' => 'Heavy rain and rotated lineups often suppress goals — we note that in match reasoning when it is material.'],
  ],
  'rg' => true,
  'related' => [['BTTS predictions', '/btts-predictions'], ['Today\'s tips', '/football-predictions-today']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Over/Under', 'url' => '/over-under-predictions']],
],

'btts-predictions' => [
  'title' => 'BTTS Predictions Today (Both Teams to Score) | Bao Predictions',
  'description' => 'Today\'s both teams to score predictions — which matches are likely to see goals at both ends, based on attacking and defensive form.',
  'h1' => 'BTTS Predictions Today',
  'unique' => 'Both Teams to Score (BTTS) is a bet on whether both sides find the net, regardless of the final result — a 2-1 or a 1-1 both count as "yes," a 3-0 counts as "no." It\'s a market that rewards looking at both teams\' attack and defence together, since a strong home attack against a leaky away defence can produce a BTTS "yes" even in a match one side is heavily expected to win overall.',
  'faqs' => [
    ['q' => 'What counts as BTTS "yes"?', 'a' => 'Both teams score at least one goal at any point in the match, regardless of the final scoreline or result.'],
    ['q' => 'Is BTTS affected by a team\'s overall win rate?', 'a' => 'Not directly — a team can have a strong win rate while still conceding regularly, which is exactly the profile that produces BTTS "yes" results even when they\'re expected to win comfortably.'],
    ['q' => 'Can BTTS pair with over 2.5?', 'a' => 'Often yes, but not always — a 1-1 is BTTS yes and under 2.5. Read each tip carefully.'],
  ],
  'rg' => true,
  'related' => [['Over/under', '/over-under-predictions'], ['1X2 predictions', '/1x2-predictions'], ['Accumulator tips', '/accumulator-tips']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'BTTS', 'url' => '/btts-predictions']],
],

'ht-ft-predictions' => [
  'title' => 'HT/FT Predictions Today (Half-Time/Full-Time) | Bao Predictions',
  'description' => 'Today\'s half-time/full-time predictions — how a match is likely to stand at the break and at full time, for one of football\'s higher-odds markets.',
  'h1' => 'HT/FT Predictions Today',
  'unique' => 'Half-time/full-time betting requires predicting both the result at half-time and the result at full-time — for example, a draw at half-time followed by a home win at full-time. It\'s a higher-odds market than straight 1X2 because you\'re right about two separate points in the match, not one. We look specifically at teams\' patterns of starting slowly or finishing strongly when building these predictions, since some sides are consistently stronger in one half than the other.',
  'faqs' => [
    ['q' => 'What does "X/1" mean in HT/FT betting?', 'a' => 'It means a draw at half-time (X) followed by a home win at full-time (1). Each HT/FT combination is written the same way — half-time result first, full-time result second.'],
    ['q' => 'Why do HT/FT odds pay more than 1X2?', 'a' => 'You\'re predicting two separate outcomes correctly rather than one, so there are more possible combinations and the odds reflect that increased difficulty.'],
    ['q' => 'Is HT/FT good for accumulators?', 'a' => 'Usually as a single or short multi — stacking many HT/FT legs multiplies difficulty quickly.'],
  ],
  'rg' => true,
  'related' => [['1X2', '/1x2-predictions'], ['How we predict', '/how-we-predict']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'HT/FT', 'url' => '/ht-ft-predictions']],
],

'weekend-football-predictions' => [
  'title' => 'Weekend Football Predictions | Bao Predictions',
  'description' => 'Weekend football predictions for Saturday and Sunday — fixtures, picks, and confidence ratings grouped for the full weekend slate.',
  'h1' => 'Weekend Football Predictions',
  'unique' => 'Planning ahead of matchday? Here are Saturday and Sunday fixtures grouped together so you can build weekend tickets without hopping between daily pages. Predictions are based on the latest team news available and will be reviewed again closer to kickoff.',
  'faqs' => [
    ['q' => 'When does this page update?', 'a' => 'Thursday evening for the first pass, then Friday and Saturday morning as lineups firm up.'],
    ['q' => 'Are jackpots included?', 'a' => 'Mega and weekend jackpots have their own pages; this page focuses on standard daily markets across the weekend.'],
    ['q' => 'Can I see tomorrow only?', 'a' => 'Yes — use Football Predictions Tomorrow for the next calendar day.'],
  ],
  'rg' => true,
  'related' => [['Today', '/football-predictions-today'], ['Tomorrow', '/football-predictions-tomorrow'], ['SportPesa Mega', '/sportpesa-mega-jackpot-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Weekend', 'url' => '/weekend-football-predictions']],
],

'results' => [
  'title' => 'Prediction Results & Track Record | Bao Predictions',
  'description' => 'Transparent football prediction results — wins, losses, win rate, and ROI. Judge our accuracy for yourself.',
  'h1' => 'Results & Track Record',
  'unique' => 'We track every published prediction from the moment it goes live. Wins and losses both stay visible. Use yesterday\'s page for the most recent matchday, and this page for the longer-term picture by market and over time.',
  'faqs' => [
    ['q' => 'Do you hide losing tips?', 'a' => 'No. Settled losses remain on the site.'],
    ['q' => 'How often is this updated?', 'a' => 'After each matchday once results are confirmed.'],
    ['q' => 'Is past performance a guarantee?', 'a' => 'No. Historical accuracy does not guarantee future results.'],
  ],
  'rg' => true,
  'related' => [['Yesterday\'s results', '/football-predictions-yesterday'], ['How we predict', '/how-we-predict'], ['About us', '/about-us']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Results', 'url' => '/results']],
],

];
