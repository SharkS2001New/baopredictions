<?php
return [

'jackpot-predictions' => [
  'title' => 'Jackpot Predictions | Bao Predictions',
  'description' => 'Football jackpot predictions for SportPesa, Betika, SportyBet, and Odibets — every game, our pick, and the reasoning, updated for every jackpot round.',
  'h1' => 'Jackpot Predictions',
  'unique' => 'A jackpot bet asks you to correctly predict the outcome of a fixed list of matches — anywhere from 3 games to 17 — for a chance at a prize pool that grows until someone wins it. It\'s a different kind of bet from a single match: the odds of getting every single game right are long, which is exactly why the payouts are large, and most jackpots also pay smaller bonus prizes for getting most (not all) of the games correct. Below are the jackpots we currently cover — pick yours for the full game-by-game breakdown.',
  'faqs' => [
    ['q' => 'What happens if a match in a jackpot is postponed or cancelled?', 'a' => 'Rules vary by bookmaker — most either apply a standard result (often treated as a draw) to that fixture or adjust the jackpot terms. Always check the specific bookmaker\'s official rules before staking.'],
    ['q' => 'Do jackpot bonuses require getting every game right?', 'a' => 'No — most jackpots pay smaller bonus amounts for getting most games correct (commonly starting a few games below the full total), on top of the main prize for a perfect score. Check each jackpot page for that jackpot\'s specific bonus structure.'],
    ['q' => 'Are jackpot tips free on Bao?', 'a' => 'Yes. Game-by-game sheets are free to view.'],
  ],
  'rg' => true,
  'related' => [['SportPesa Mega', '/sportpesa-mega-jackpot-predictions'], ['Betika Midweek', '/betika-midweek-jackpot-predictions'], ['Odibets Laki Tatu', '/odibets-laki-tatu-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Jackpots', 'url' => '/jackpot-predictions']],
],

'sportpesa-mega-jackpot-predictions' => [
  'title' => 'SportPesa Mega Jackpot Predictions This Week | Bao Predictions',
  'description' => 'SportPesa Mega Jackpot predictions — all 17 games, our pick and reasoning for each, updated ahead of this week\'s deadline.',
  'h1' => 'SportPesa Mega Jackpot Predictions',
  'unique' => 'The SportPesa Mega Jackpot asks you to predict the outcome of 17 selected matches for a prize pool that starts at KSh 100 million and grows until it\'s won. Below is our game-by-game breakdown for this week\'s selection — each pick includes our reasoning, not just a result, so you can weigh it against your own view before staking.',
  'howto_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>How the SportPesa Mega Jackpot works</h2>
    <p>Minimum stake is KSh 99 for a single line. You're predicting the 1X2 result (home win, draw, or away win) for all 17 matches. Getting all 17 correct wins the full jackpot; SportPesa also pays bonus prizes for correctly predicting a high number of games without a perfect score — check SportPesa's current terms for the exact bonus thresholds and amounts, as these can change between jackpot rounds.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'How many games are in the SportPesa Mega Jackpot?', 'a' => '17 matches, selected by SportPesa from various leagues and competitions worldwide.'],
    ['q' => 'What\'s the minimum stake?', 'a' => 'KSh 99 for a single line, as of the terms current at time of writing — always confirm the current stake on SportPesa\'s own platform before betting.'],
    ['q' => 'When does the Mega Jackpot close?', 'a' => 'Before the first kickoff on the card — always double-check the exact cutoff on SportPesa\'s site, as kickoff times can shift.'],
  ],
  'rg' => true,
  'related' => [['All jackpots', '/jackpot-predictions'], ['SportPesa Midweek', '/sportpesa-midweek-jackpot-predictions'], ['1X2 predictions', '/1x2-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Jackpots', 'url' => '/jackpot-predictions'], ['name' => 'SportPesa Mega', 'url' => '/sportpesa-mega-jackpot-predictions']],
],

'sportpesa-midweek-jackpot-predictions' => [
  'title' => 'SportPesa Midweek Jackpot Predictions | Bao Predictions',
  'description' => 'SportPesa Midweek Jackpot predictions — all games this round, our pick and reasoning for each fixture.',
  'h1' => 'SportPesa Midweek Jackpot Predictions',
  'unique' => 'The SportPesa Midweek Jackpot runs on the same format as the weekend Mega Jackpot — predict every match correctly for the full prize — but with fixtures played midweek and a separate, smaller starting prize pool. Below is our breakdown for this round.',
  'howto_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>How the SportPesa Midweek Jackpot works</h2>
    <p>Same format as the Mega Jackpot — predict the 1X2 result for every selected match — but run midweek with its own prize pool, typically starting lower than the weekend Mega Jackpot. Confirm the current stake and prize structure on SportPesa's platform, as these are set independently from the weekend jackpot.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'Is the Midweek Jackpot the same format as the Mega Jackpot?', 'a' => 'Yes — same 1X2 prediction format — but it\'s a separate prize pool with its own fixtures, played midweek rather than the weekend.'],
    ['q' => 'How often does the Midweek Jackpot run?', 'a' => 'Typically weekly, midweek — check SportPesa\'s current schedule as timing can shift around international breaks and cup competitions.'],
    ['q' => 'Do you update tips after team news?', 'a' => 'Yes — midweek cards move fast; we refresh reasoning when major absences land.'],
  ],
  'rg' => true,
  'related' => [['SportPesa Mega', '/sportpesa-mega-jackpot-predictions'], ['Betika Midweek', '/betika-midweek-jackpot-predictions'], ['Jackpot hub', '/jackpot-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Jackpots', 'url' => '/jackpot-predictions'], ['name' => 'SportPesa Midweek', 'url' => '/sportpesa-midweek-jackpot-predictions']],
],

'betika-midweek-jackpot-predictions' => [
  'title' => 'Betika Midweek Jackpot Predictions | Bao Predictions',
  'description' => 'Betika Midweek Jackpot predictions — every game this round, our pick and reasoning, updated ahead of the deadline.',
  'h1' => 'Betika Midweek Jackpot Predictions',
  'unique' => 'Betika\'s Midweek Jackpot asks for predictions across a set of midweek fixtures, with a prize pool that rolls over and grows until someone lands every result. Here\'s our full breakdown for this round\'s games.',
  'howto_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>How the Betika Midweek Jackpot works</h2>
    <p><!--Confirm current game count, minimum stake, and bonus structure on Betika before treating these figures as final — operators adjust formats.--> Confirm the current game count, minimum stake, and bonus structure directly on Betika's platform before staking. Betika has adjusted jackpot formats before; always match the live product terms.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'How many games are in the Betika Midweek Jackpot?', 'a' => 'Confirm the current format on Betika\'s platform for this round — game counts have changed historically.'],
    ['q' => 'What\'s the minimum stake?', 'a' => 'Confirm the current stake on Betika before betting; we do not lock a figure here that can go stale.'],
    ['q' => 'Are your Betika tips free?', 'a' => 'Yes — the full sheet with reasoning is free on Bao Predictions.'],
  ],
  'rg' => true,
  'related' => [['SportPesa Midweek', '/sportpesa-midweek-jackpot-predictions'], ['SportyBet Daily', '/sportybet-daily-jackpot-predictions'], ['Jackpot hub', '/jackpot-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Jackpots', 'url' => '/jackpot-predictions'], ['name' => 'Betika Midweek', 'url' => '/betika-midweek-jackpot-predictions']],
],

'sportybet-daily-jackpot-predictions' => [
  'title' => 'SportyBet Daily Jackpot Predictions Today | Bao Predictions',
  'description' => 'SportyBet Daily Jackpot predictions for today — every game, pick, and reasoning, updated daily ahead of the deadline.',
  'h1' => 'SportyBet Daily Jackpot Predictions Today',
  'unique' => 'Unlike the weekly jackpots, SportyBet\'s Daily Jackpot runs every day with a smaller game count and prize pool — a lower barrier to entry with more frequent chances to win. Here\'s today\'s breakdown. <!--Note: this page needs a daily rebuild, not weekly.-->',
  'howto_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>How the SportyBet Daily Jackpot works</h2>
    <p>Confirm current game count, stake, and prize structure on SportyBet's platform before staking — daily jackpots are more prone to format tweaks than weekly ones since the operator runs so many rounds.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'Does the Daily Jackpot really run every day?', 'a' => 'Yes — a new round opens daily, with its own deadline and fixture list. Check back here each day for the current round\'s picks.'],
    ['q' => 'How does the prize pool compare to weekly jackpots?', 'a' => 'Typically smaller than the SportPesa Mega or Betika Midweek jackpots, reflecting the lower game count and daily frequency — confirm current amounts on SportyBet\'s platform.'],
    ['q' => 'When should I check for updates?', 'a' => 'Morning for the first sheet, then again after confirmed team news on evening kickoffs.'],
  ],
  'rg' => true,
  'related' => [['Odibets Laki Tatu', '/odibets-laki-tatu-predictions'], ['Today\'s tips', '/football-predictions-today'], ['Jackpot hub', '/jackpot-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Jackpots', 'url' => '/jackpot-predictions'], ['name' => 'SportyBet Daily', 'url' => '/sportybet-daily-jackpot-predictions']],
],

'odibets-laki-tatu-predictions' => [
  'title' => 'Odibets Laki Tatu Predictions Today | Bao Predictions',
  'description' => 'Odibets Laki Tatu predictions — today\'s 3-game jackpot, our picks and reasoning, updated daily.',
  'h1' => 'Odibets Laki Tatu Predictions Today',
  'unique' => 'Laki Tatu ("lucky three" in Swahili) is Odibets\' compact jackpot — just 3 matches to predict, which means a much shorter odds ladder than a 17-game Mega Jackpot but also a much smaller potential prize. It\'s a genuinely different kind of bet from the bigger weekly jackpots, and we treat the analysis differently here: with only 3 games, every single pick matters far more, so we go deeper on each one rather than moving quickly through a long list. <!--Verify current name/format on Odibets before launch.-->',
  'howto_html' => <<<'HTML'
<section class="section section-muted">
  <div class="wrap prose">
    <h2>How Odibets Laki Tatu works</h2>
    <p>Confirm current stake, prize structure, and exact game count on Odibets' platform before staking — this section must match whatever the product is actually called and structured as live, not a stale snapshot.</p>
  </div>
</section>
HTML,
  'faqs' => [
    ['q' => 'How many games are in Laki Tatu?', 'a' => 'Documented as 3 games at time of writing — verify the live format on Odibets before betting.'],
    ['q' => 'Is a 3-game jackpot easier to win than a 17-game one?', 'a' => 'The odds of getting all games right are shorter with fewer games, but the prize pool is correspondingly smaller — it\'s a different risk/reward profile, not simply "easier."'],
    ['q' => 'Do you write deeper notes on Laki Tatu?', 'a' => 'Yes — with only three legs, each match gets more analysis space than a 17-game Mega sheet.'],
  ],
  'rg' => true,
  'related' => [['SportyBet Daily', '/sportybet-daily-jackpot-predictions'], ['Jackpot hub', '/jackpot-predictions'], ['1X2 predictions', '/1x2-predictions']],
  'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Jackpots', 'url' => '/jackpot-predictions'], ['name' => 'Odibets Laki Tatu', 'url' => '/odibets-laki-tatu-predictions']],
],

];
