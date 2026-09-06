<?php
/**
 * Accuratetip-style long-form SEO articles + featured banners per page.
 */
return [

'homepage' => [
  'title' => 'Today\'s Football Predictions & Free Tips | Bao Predictions',
  'description' => 'Free daily football predictions with confidence ratings, form and head-to-head analysis, and a public track record. Updated every matchday.',
  'h1' => 'Today\'s Football Predictions',
  'keywords' => 'football predictions today, free football tips, bao predictions, confidence ratings, jackpot predictions kenya',
  'featured_title' => 'Expert-verified predictions updated every matchday',
  'featured_text' => 'Bao Predictions publishes free daily football tips with clear confidence ratings, plain-language reasoning, and a public track record that includes losses — not just wins. Built for bettors who want the pick and the why.',
  'article_title' => 'How Bao Predictions delivers reliable football tips through data and human review',
  'faq_heading' => 'Football Predictions FAQ',
  'article_html' => <<<'HTML'
<p>Most tip sites either flood you with anonymous picks or hide how those picks performed. Bao Predictions was built the other way around: every published lean stays visible after it settles, and every card includes enough context to judge the idea before you stake.</p>
<h3>What goes into a Bao tip</h3>
<p>We start with recent form — weighted toward the same competition and venue as the upcoming fixture — then layer head-to-head history adjusted for squad turnover, confirmed team news, and whether the market price still offers edge. Motivation matters too: relegation scraps and European qualification races behave differently from dead rubbers, which is why Must-Win Teams is a separate shortlist.</p>
<h3>Confidence ratings explained</h3>
<p>85–100% tips are our strongest published leans. 70–84% are solid but not risk-free. 50–69% sit in genuine 50/50 territory and are better suited to accumulators than heavy singles. Below 50% we generally do not publish. Confidence is relative to our process that day — not a promised win rate.</p>
<h3>Jackpots and Kenya-facing markets</h3>
<p>Alongside major European leagues we cover SportPesa Mega and Midweek, Betika Midweek, SportyBet Daily, and Odibets Laki Tatu with per-game reasoning. A 17-game card fails on its weakest link; that is why jackpot sheets get game-by-game notes instead of a blank template with swapped names.</p>
<h3>Responsible use</h3>
<p>Tips are informational. We are not a bookmaker and we never guarantee outcomes. Bet only with licensed operators, stay 18+, and read our responsible betting guide. Full settled results live on the Results page so you can audit us yourself.</p>
HTML,
  'faqs' => [
    ['q' => 'Are Bao Predictions free?', 'a' => 'Yes. Daily tips, jackpot sheets, and results are free to view with reasoning on every pick.'],
    ['q' => 'How accurate are your football tips?', 'a' => 'We publish settled results including losses on the Results page. Judge accuracy from that record, not marketing claims.'],
    ['q' => 'What do confidence ratings mean?', 'a' => '85–100% is our strongest lean; 70–84% is solid but not risk-free; 50–69% suits accumulators more than heavy singles. Confidence is not a win guarantee.'],
    ['q' => 'Do you cover Kenyan jackpots?', 'a' => 'Yes — SportPesa Mega and Midweek, Betika Midweek, SportyBet Daily, and Odibets Laki Tatu with per-game notes.'],
    ['q' => 'How often are tips updated?', 'a' => 'Initial tips usually land the evening before. We review through matchday when late team news changes the picture.'],
    ['q' => 'Is this financial advice?', 'a' => 'No. Predictions are informational opinions. Bet only 18+ with licensed operators and money you can afford to lose.'],
  ],
],

'football-predictions-today' => [
  'title_tpl' => 'Today\'s Football Predictions & Sure Tips, {date} | Bao Predictions',
  'description' => 'Today\'s football predictions — every fixture, pick, and confidence rating across the leagues we cover. Updated live.',
  'h1_tpl' => 'Football Predictions for {date}',
  'keywords' => 'today football predictions, football tips today, sure tips today, match predictions today, bao predictions today',
  'featured_title' => 'Expert-verified predictions updated live today',
  'featured_text' => 'Today\'s board ranks fixtures by confidence across the leagues we cover. Each pick includes form, head-to-head context, and late team-news checks — re-open this page through the day if lineups move the lean.',
  'article_title' => 'How to use today\'s football predictions the right way',
  'faq_heading' => 'Today\'s Predictions FAQ',
  'article_html' => <<<'HTML'
<p>Today's predictions page is the full live slate — not a marketing shortlist. Tips are ordered so stronger confidence leans surface first, while lower-conviction games remain visible for jackpot builders who need every row filled honestly.</p>
<h3>Reading a tip card</h3>
<p>Each card shows league, teams, kickoff time, the pick, decimal odds when available, and a confidence percentage. Green tip text is the market outcome we favour; the confidence pill tells you how strongly form, history, and team news agree. High confidence is still not a guarantee.</p>
<h3>When tips update</h3>
<p>Initial predictions usually land the evening before. Through matchday we review late injuries, suspensions, and rotation risk. If a lean changes, the last-updated stamp near the top of the page moves with it. Always check that stamp before staking on a fragile fitness call.</p>
<h3>Building tickets from today's board</h3>
<p>For singles, stick to higher-confidence 1X2 or goals markets. For accumulators, fewer stronger legs beat long piles of 55% fillers. Must-Win and Sure Bets pages filter the same universe to 85%+ and high-conviction shortlists when you want less noise.</p>
<h3>18+ and bankroll discipline</h3>
<p>Only stake what you can afford to lose. Predictions are analysis, not financial advice. If gambling stops being entertainment, step away and use support resources linked from our responsible betting page.</p>
HTML,
  'faqs' => [
    ['q' => 'How many matches are predicted today?', 'a' => 'It varies by matchday — the cards above list every published pick across the leagues we cover today.'],
    ['q' => 'What is today\'s most confident pick?', 'a' => 'Scan the cards for the highest confidence percentage, or open Must-Win Teams for 85%+ only.'],
    ['q' => 'When are predictions updated?', 'a' => 'We publish initial predictions the evening before, then review through matchday if there is late team news.'],
    ['q' => 'Can I use these tips for jackpots?', 'a' => 'Yes — map 1X2 leans onto your operator sheet, and use jackpot pages for full 17-game or daily cards with reasons.'],
    ['q' => 'Do kickoff times follow my timezone?', 'a' => 'Yes. Times are stored in UTC, shown in East Africa Time on the server, then converted in your browser to your local timezone (same approach as Pitch Predictions).'],
    ['q' => 'Are losses deleted?', 'a' => 'No. Settled tips stay on Results and Yesterday so you can audit us.'],
  ],
],

'football-predictions-tomorrow' => [
  'title_tpl' => 'Tomorrow\'s Football Predictions & Early Tips, {date} | Bao Predictions',
  'description' => 'Tomorrow\'s football predictions — early picks with confidence ratings. Re-check closer to kickoff for lineup updates.',
  'keywords' => 'tomorrow football predictions, football tips tomorrow, early tips, bao predictions tomorrow',
  'featured_title' => 'Plan ahead — then re-check closer to kickoff',
  'featured_text' => 'Tomorrow\'s tips use the best team news available today. Fitness calls and cup rotation can still move a lean overnight, so treat this page as an early board and confirm the last-updated time before you lock a ticket.',
  'article_title' => 'Why tomorrow\'s predictions can change overnight',
  'faq_heading' => 'Tomorrow\'s Predictions FAQ',
  'article_html' => <<<'HTML'
<p>Publishing tomorrow's slate early helps bettors plan bankroll and jackpot tickets — but football news does not freeze at midnight. Manager press conferences, late scans, and travel delays can flip a double-chance lean into a straight home or void a goals lean entirely.</p>
<h3>How we flag fragile tips</h3>
<p>When a side has a midweek European tie or a long injury list, we note rotation risk in the card reasoning where available. Until the lineup is confirmed, confidence on those games should be treated as provisional.</p>
<h3>Weekend planning</h3>
<p>If you want Saturday and Sunday grouped, use the weekend predictions page. Tomorrow's page is strictly the next calendar day so search intent stays clean and the H1 date stays honest.</p>
HTML,
  'faqs' => [
    ['q' => 'Why publish tomorrow early?', 'a' => 'So you can plan bankroll and jackpot tickets — then re-check closer to kickoff for lineup news.'],
    ['q' => 'Will tips change overnight?', 'a' => 'They can. Injuries, suspensions, and rotation often land after the first publish.'],
    ['q' => 'Is tomorrow the same as the weekend page?', 'a' => 'No. Tomorrow is the next calendar day only. Use Weekend Predictions for Saturday–Sunday together.'],
    ['q' => 'Should I stake on early tips?', 'a' => 'Only if you accept provisional confidence. Fragile fitness calls should wait for confirmed lineups.'],
    ['q' => 'Where do I see today instead?', 'a' => 'Open Football Predictions Today for the live matchday board.'],
    ['q' => 'Are these tips free?', 'a' => 'Yes. Free to view with confidence ratings and reasoning.'],
  ],
],

'1x2-predictions' => [
  'title' => '1X2 Predictions Today — Home Draw Away Tips | Bao Predictions',
  'description' => 'Today\'s 1X2 match result predictions — home win, draw, or away win — with confidence ratings and clear reasoning.',
  'keywords' => '1x2 predictions today, win draw win tips, match result predictions, home win tips, bao 1x2',
  'featured_title' => 'Expert 1X2 match-winner analysis',
  'featured_text' => '1X2 is home win, draw, or away win — the most intuitive football market and one of the hardest to beat consistently. Our cards weigh draw frequency and defensive structure, not just which club has the bigger name.',
  'article_title' => '1X2 football predictions: how we pick home, draw, or away',
  'faq_heading' => '1X2 Predictions FAQ',
  'article_html' => <<<'HTML'
<p>1X2 remains the default market for most football bettors because it maps cleanly onto how people watch a match. That popularity also makes it efficient: bookmakers price favourites tightly, and a single red card can erase a pre-match edge.</p>
<h3>Home win vs draw frequency</h3>
<p>Strong favourites still draw against organised mid-table sides more often than casual tip sheets admit. We look at both teams' recent draw rates in the same competition, expected goals patterns where available, and whether the underdog sits deep away from home.</p>
<h3>Away wins</h3>
<p>Away leans need clearer form or a genuine mismatch. Travel, rest, and cup rotation matter more away than at home. When those factors disagree, we prefer double chance over forcing a raw 2.</p>
<h3>Using confidence on 1X2</h3>
<p>85%+ 1X2 tips are rare by design. Most published match-result leans sit in the 70s. Stacking five middling 1X2 legs into an accumulator multiplies risk faster than the headline odds suggest — see our accumulator explainer for the maths.</p>
HTML,
  'faqs' => [
    ['q' => 'What does 1X2 mean?', 'a' => '1 = home win, X = draw, 2 = away win — the standard match-result market.'],
    ['q' => 'Why do favourites lose?', 'a' => 'A goal, red card, or refereeing call can flip a result regardless of form. That is why we publish confidence, not certainty.'],
    ['q' => 'Do you cover Kenyan fixtures?', 'a' => 'Yes — Kenya Premier League games appear in the same card format when they are on the slate.'],
    ['q' => 'Is 1X2 better than double chance?', 'a' => '1X2 pays more when you are right on the exact result. Double chance is better when you can only rule one outcome out.'],
    ['q' => 'Should I stack many 1X2 legs?', 'a' => 'Long 1X2 accumulators multiply failure risk fast. Prefer fewer stronger legs.'],
    ['q' => 'Where is the track record?', 'a' => 'On the Results page — wins and losses both stay published.'],
  ],
],

'double-chance-predictions' => [
  'title' => 'Double Chance Predictions Today (1X, X2, 12) | Bao Predictions',
  'description' => 'Today\'s double chance predictions — cover two outcomes when the exact 1X2 is unclear. Lower variance, shorter odds.',
  'keywords' => 'double chance predictions, 1X tips, X2 tips, lower risk football bets',
  'featured_title' => 'Cover two outcomes when the exact result is unclear',
  'featured_text' => 'Double chance (1X, X2, or 12) lowers variance when you can rule one result out but not nail the precise 1X2. Odds are shorter — use it as risk control, not a free lunch.',
  'article_title' => 'When double chance beats straight 1X2',
  'faq_heading' => 'Double Chance FAQ',
  'article_html' => <<<'HTML'
<p>Double chance is best when your football read is asymmetric: you are confident the away side will not win, but less sure whether the home side closes the game out or settles for a point. 1X covers that story in one selection.</p>
<p>It is a poor fit when the match is a true coin flip between two attack-minded teams — then 12 (no draw) or a goals market may express the view better. Always compare the double-chance price to backing the two outcomes separately as a Dutch; if the book is offering almost no premium for the convenience, skip it.</p>
HTML,
  'faqs' => [
    ['q' => 'When is double chance better than 1X2?', 'a' => 'When you can rule one result out but not nail the exact scoreline path — e.g. 1X when an away win looks unlikely.'],
    ['q' => 'Why are the odds shorter?', 'a' => 'You cover two of three outcomes, so the book pays less than a straight 1X2.'],
    ['q' => 'What is 12?', 'a' => 'Either team wins — no draw. Useful when both sides attack and a draw looks least likely.'],
    ['q' => 'Can I Dutch the two outcomes instead?', 'a' => 'Sometimes. Compare prices; if double chance offers almost no convenience premium, skip it.'],
    ['q' => 'Is double chance risk-free?', 'a' => 'No. You can still lose if the uncovered outcome lands.'],
    ['q' => 'Do you publish 1X and X2 both?', 'a' => 'We publish the lean that matches our read — not every variant on every match.'],
  ],
],

'over-under-predictions' => [
  'title' => 'Over/Under Predictions Today — Goals Tips | Bao Predictions',
  'description' => 'Over and under goals predictions from both teams\' scoring and conceding profiles, not just the match winner.',
  'keywords' => 'over under predictions, over 2.5 tips, under 2.5 tips, goals predictions today',
  'featured_title' => 'Goals lines from both teams\' scoring profiles',
  'featured_text' => 'Over/under tips look at combined expected goals, not just who wins. A high-scoring favourite against a parked bus can still land under 2.5 — we model both attacks and both defences.',
  'article_title' => 'How Bao builds over/under goals predictions',
  'faq_heading' => 'Over/Under FAQ',
  'article_html' => <<<'HTML'
<p>The 2.5 line remains the most liquid goals market. We also publish 1.5 and 3.5 when the profile is extreme. Home advantage usually lifts totals; cup rotation and heavy weather usually suppress them.</p>
<p>Pairing over 2.5 with BTTS yes is common but not automatic — a 3-0 is over without BTTS. Read each card's pick text carefully before combining markets on the same match.</p>
HTML,
  'faqs' => [
    ['q' => 'What line do you use most?', 'a' => 'Over/under 2.5 is the default liquid line; we also publish 1.5 and 3.5 when profiles are extreme.'],
    ['q' => 'Does a favourite always go over?', 'a' => 'No. A strong favourite can win 1-0. We model both attacks and both defences.'],
    ['q' => 'Can I pair over 2.5 with BTTS?', 'a' => 'Common, but a 3-0 is over without BTTS. Read each pick carefully.'],
    ['q' => 'Do cup games go under more?', 'a' => 'Often yes when rotation and caution suppress open play — we note that when material.'],
    ['q' => 'Are totals updated for team news?', 'a' => 'Yes when a key striker or centre-back absence clearly changes the goals profile.'],
    ['q' => 'Where else are goals tips?', 'a' => 'On Today\'s board and in BTTS when both markets apply.'],
  ],
],

'btts-predictions' => [
  'title' => 'BTTS Predictions Today — Both Teams to Score Tips | Bao Predictions',
  'description' => 'Both teams to score tips based on attack output and defensive leaks — yes and no leans with confidence ratings.',
  'keywords' => 'btts predictions, both teams to score tips, gg tips today',
  'featured_title' => 'Both teams to score — attack meets defence',
  'featured_text' => 'BTTS ignores the final result. We favour yes when both attacks create chances and both defences concede regularly; no when one side keeps clean sheets against this level of opponent.',
  'article_title' => 'Reading BTTS beyond win rates',
  'faq_heading' => 'BTTS Predictions FAQ',
  'article_html' => <<<'HTML'
<p>A team can win often and still concede weekly — that profile produces BTTS yes even in comfortable victories. Conversely, low-block away underdogs can frustrate BTTS yes tickets despite occasional shocks.</p>
<p>Derbies and cup ties with extra motivation sometimes suppress open play; we note that when it is material to the lean.</p>
HTML,
  'faqs' => [
    ['q' => 'What does BTTS mean?', 'a' => 'Both teams to score — yes or no — regardless of who wins.'],
    ['q' => 'When do you lean BTTS yes?', 'a' => 'When both attacks create chances and both defences concede regularly against this level of opponent.'],
    ['q' => 'Can a team win and kill BTTS?', 'a' => 'Yes — a 2-0 or 3-0 wins 1X2 but fails BTTS yes.'],
    ['q' => 'Do derbies suppress BTTS?', 'a' => 'Sometimes. Extra caution can reduce open play; we note it when it matters.'],
    ['q' => 'Is BTTS easier than 1X2?', 'a' => 'Different, not easier. You ignore the result but still need both nets to move.'],
    ['q' => 'Are tips free?', 'a' => 'Yes — free BTTS leans with confidence ratings.'],
  ],
],

'ht-ft-predictions' => [
  'title' => 'HT/FT Predictions Today — Half Time Full Time Tips | Bao Predictions',
  'description' => 'Half-time / full-time predictions for slow starters and late finishers. Two results, higher odds.',
  'keywords' => 'ht ft predictions, half time full time tips, ht/ft betting',
  'featured_title' => 'Half-time / full-time patterns',
  'featured_text' => 'HT/FT needs two correct results. We look for sides that start slow or finish strong — X/1 and 1/1 profiles are the most common published leans.',
  'article_title' => 'How HT/FT differs from 1X2',
  'faq_heading' => 'HT/FT Predictions FAQ',
  'article_html' => <<<'HTML'
<p>A team can trail or draw at the break and still win — that X/1 shape is a classic HT/FT angle for favourites who dominate late. Straight 1X2 home win does not capture the half-time path. Odds are higher because you must be right twice.</p>
HTML,
  'faqs' => [
    ['q' => 'What is HT/FT?', 'a' => 'You pick the half-time result and the full-time result — both must be correct.'],
    ['q' => 'Why is X/1 common?', 'a' => 'Favourites often draw or trail early then dominate late.'],
    ['q' => 'Is HT/FT harder than 1X2?', 'a' => 'Yes — you must be right twice, which is why odds are higher.'],
    ['q' => 'Do you tip HT/FT on every game?', 'a' => 'Only when the half-time path is a clear part of the match story.'],
    ['q' => 'Can I combine HT/FT with goals?', 'a' => 'You can, but correlated legs raise variance. Keep stakes small.'],
    ['q' => 'Where else to look?', 'a' => '1X2 for the final result only if you do not need the half-time path.'],
  ],
],

'must-win-teams-today' => [
  'title' => 'Must-Win Teams Today — High Confidence Tips | Bao Predictions',
  'description' => 'Must-win shortlist: table pressure plus 85%+ confidence. Not every favourite qualifies.',
  'keywords' => 'must win teams today, banker tips, high confidence football tips',
  'featured_title' => 'Pressure + form — not just favourites',
  'featured_text' => 'Must-win means table pressure (relegation, Europe, title) combined with 85%+ confidence. A big club can be favourite without meeting that bar.',
  'article_title' => 'What “must-win” means on Bao Predictions',
  'faq_heading' => 'Must-Win Teams FAQ',
  'article_html' => <<<'HTML'
<p>Motivation changes effort and risk tolerance. Sides fighting relegation with a shrinking run-in often press higher and leave more space — that can help a goals lean as much as a result lean. We only tag must-win when both the narrative and the underlying numbers agree.</p>
HTML,
  'faqs' => [
    ['q' => 'What does must-win mean here?', 'a' => 'Table pressure (relegation, Europe, title) plus 85%+ confidence — not just a big club favourite.'],
    ['q' => 'Can a favourite miss this list?', 'a' => 'Yes, if confidence or motivation does not clear the bar.'],
    ['q' => 'Are these bankers?', 'a' => 'They are our strongest published leans that day — still not guarantees.'],
    ['q' => 'Do must-win sides always win?', 'a' => 'No. Pressure can also create chaotic games. Check Results.'],
    ['q' => 'How often does the list update?', 'a' => 'Daily, and again if late news kills a lean.'],
    ['q' => 'Related pages?', 'a' => 'Sure Bets Today and 1X2 Predictions cover overlapping high-conviction angles.'],
  ],
],

'sure-bets-today' => [
  'title' => 'Sure Bets Today — Highest Confidence Tips | Bao Predictions',
  'description' => '"Sure bet" is a search phrase, not a promise. These are our highest-confidence published picks with a public track record.',
  'keywords' => 'sure bets today, high confidence tips, safest football tips today',
  'featured_title' => '“Sure bet” is a search phrase — not a promise',
  'featured_text' => 'These are our highest-confidence published picks. Nothing in football is sure. Check Results for how often this band actually lands.',
  'article_title' => 'High-confidence tips without fake certainty',
  'faq_heading' => 'Sure Bets FAQ',
  'article_html' => <<<'HTML'
<p>We keep the “sure bets” URL because that is how people search, then explain the limit in plain language. High confidence means form, history, and team news align — it does not delete variance. Use smaller stakes even here, and never chase losses with longer tickets.</p>
HTML,
  'faqs' => [
    ['q' => 'Are these literally sure?', 'a' => 'No. The URL matches search language; the copy explains the limit. Nothing in football is sure.'],
    ['q' => 'How do you pick them?', 'a' => 'Highest published confidence where form, history, and team news align.'],
    ['q' => 'Should I stake more on sure bets?', 'a' => 'Use disciplined stakes even here. Do not chase losses.'],
    ['q' => 'Where is proof?', 'a' => 'Results and Yesterday show how this confidence band actually lands.'],
    ['q' => 'Is this arbitrage?', 'a' => 'No. These are tip leans, not multi-book arb positions.'],
    ['q' => '18+?', 'a' => 'Yes. Informational only — bet responsibly with licensed operators.'],
  ],
],

'betnumbers-tips' => [
  'title' => 'BetNumbers Tips — Best Mixed Market Picks Today | Bao Predictions',
  'description' => 'BetNumbers tips: each fixture picks the strongest lean across 1X2, BTTS, Over/Under 2.5, and Double Chance.',
  'keywords' => 'betnumbers tips, mixed market tips, best odds tips today',
  'featured_title' => 'One tip per game — best market wins',
  'featured_text' => 'We compare 1X2, BTTS, Over/Under 2.5, and Double Chance, then keep the lean with the strongest win chance and a price that fits the model.',
  'article_title' => 'BetNumbers: strongest chance, sensible price',
  'faq_heading' => 'BetNumbers Tips FAQ',
  'article_html' => <<<'HTML'
<p>Each card picks one market. We rank by model confidence, then by how closely the book odds sit to a fair price for that chance. Short Double Chance tickets can look “safe” on paper — we soft-penalise them in ranking so the list stays a genuine mix, not a wall of 1X/X2.</p>
HTML,
  'faqs' => [
    ['q' => 'Which markets are compared?', 'a' => '1X2, BTTS, Over/Under 2.5, and Double Chance — one tip published per fixture.'],
    ['q' => 'How is the winning market chosen?', 'a' => 'Highest winning chance first; if two are close, the odds that better fit the model probability win.'],
    ['q' => 'Why skip some odds?', 'a' => 'We ignore prices outside a usable band (about 1.18–3.80) so tips stay stakeable.'],
    ['q' => 'Is this the same as Sure Bets?', 'a' => 'Same mixed-market engine; Sure Bets and Must-Win apply higher confidence filters.'],
    ['q' => '18+?', 'a' => 'Yes. Informational only — bet responsibly with licensed operators.'],
  ],
],

'sportpesa-mega-jackpot-predictions' => [
  'title' => 'SportPesa Mega Jackpot Predictions — 17 Games Tips | Bao Predictions',
  'description' => 'SportPesa Mega Jackpot predictions with 1X2 leans and reasoning on every game. Confirm stake and deadline on SportPesa.',
  'keywords' => 'sportpesa mega jackpot predictions, sportpesa 17 games, mega jackpot tips kenya',
  'featured_title' => '17-game Mega Jackpot — pick and reasoning per game',
  'featured_text' => 'SportPesa Mega Jackpot tips with 1X2 leans and a one-line reason on every row. Confirm stake, bonus bands, and deadline on SportPesa before you play. 18+ only.',
  'article_title' => 'How to use Bao\'s SportPesa Mega Jackpot sheet',
  'faq_heading' => 'SportPesa Mega Jackpot FAQ',
  'article_html' => <<<'HTML'
<p>A perfect 17 requires every lean to land. We publish confidence and reasoning so you can see where the sheet is fragile and decide whether to cover, swap, or skip. Minimum stake and bonus thresholds change — always verify on SportPesa's platform.</p>
<p>Treat Mega Jackpot as entertainment with a long-shot profile. Bankroll sizing should assume a full loss on the ticket.</p>
HTML,
  'faqs' => [
    ['q' => 'How many games is Mega Jackpot?', 'a' => 'Typically 17 — confirm the live card on SportPesa for this round.'],
    ['q' => 'Do you guarantee a win?', 'a' => 'No. A 17-game perfect is a long shot. Treat it as entertainment.'],
    ['q' => 'Why show confidence per game?', 'a' => 'So you can see fragile rows and decide whether to cover, swap, or skip.'],
    ['q' => 'Where do I confirm stake?', 'a' => 'On SportPesa — minimum stake and bonuses change.'],
    ['q' => 'What if a match is postponed?', 'a' => 'Follow SportPesa void/bonus rules for that round.'],
    ['q' => 'Is this free?', 'a' => 'Yes — free tips with reasoning. Betting is on SportPesa.'],
  ],
],

'sportpesa-midweek-jackpot-predictions' => [
  'title' => 'SportPesa Midweek Jackpot Predictions | Bao Predictions',
  'description' => 'SportPesa Midweek Jackpot tips with per-game reasoning. Higher rotation risk midweek — re-check before the deadline.',
  'keywords' => 'sportpesa midweek jackpot predictions, midweek jackpot tips',
  'featured_title' => 'Midweek jackpot sheet with fresh team news',
  'featured_text' => 'Same 1X2 format as Mega, separate prize pool, midweek fixtures. Rotation risk is higher midweek — re-check before the deadline.',
  'article_title' => 'SportPesa Midweek Jackpot analysis approach',
  'faq_heading' => 'SportPesa Midweek FAQ',
  'article_html' => <<<'HTML'
<p>Midweek cards mix domestic leagues and European ties. Fatigue and rotation make late lineup news more important than on a quiet Saturday. We refresh reasoning when major absences land.</p>
HTML,
  'faqs' => [
    ['q' => 'How is Midweek different from Mega?', 'a' => 'Separate prize pool and midweek fixtures; rotation risk is usually higher.'],
    ['q' => 'When should I re-check?', 'a' => 'Before the deadline after team news and European travel land.'],
    ['q' => 'Same tip format?', 'a' => 'Yes — 1X2 leans with per-game reasoning.'],
    ['q' => 'Are tips free?', 'a' => 'Yes.'],
    ['q' => 'Where are results?', 'a' => 'On our Results page after settlement.'],
    ['q' => '18+ only?', 'a' => 'Yes. Confirm terms on SportPesa and bet responsibly.'],
  ],
],

'betika-midweek-jackpot-predictions' => [
  'title' => 'Betika Midweek Jackpot Predictions | Bao Predictions',
  'description' => 'Betika Midweek Jackpot tips for this round\'s fixtures. Always confirm live stake and bonus rules on Betika.',
  'keywords' => 'betika midweek jackpot predictions, betika jackpot tips',
  'featured_title' => 'Betika Midweek — confirm live terms',
  'featured_text' => 'Game count and stake have changed before. Our sheet follows this round\'s fixtures; always match stake and bonus rules on Betika before betting.',
  'article_title' => 'Using Betika Midweek tips responsibly',
  'faq_heading' => 'Betika Midweek FAQ',
  'article_html' => <<<'HTML'
<p>Operator formats evolve. Bao's job is the football read per fixture; your job is confirming the product terms in-app. Never rely on a stale stake number from an article alone.</p>
HTML,
  'faqs' => [
    ['q' => 'Do Betika game counts change?', 'a' => 'They have before. Always match the live product in the Betika app.'],
    ['q' => 'Do you set the stake?', 'a' => 'No — confirm stake and bonuses on Betika before playing.'],
    ['q' => 'What do you publish?', 'a' => '1X2 leans and reasons for this round\'s fixtures.'],
    ['q' => 'Are tips free?', 'a' => 'Yes.'],
    ['q' => 'Postponements?', 'a' => 'Follow Betika\'s official void rules.'],
    ['q' => 'Responsible betting?', 'a' => '18+ only. Never chase jackpot losses.'],
  ],
],

'sportybet-daily-jackpot-predictions' => [
  'title' => 'SportyBet Daily Jackpot Predictions Today | Bao Predictions',
  'description' => 'SportyBet Daily Jackpot tips rebuilt every day. Check fixtures and deadline before you play.',
  'keywords' => 'sportybet daily jackpot predictions, daily jackpot tips today',
  'featured_title' => 'Daily jackpot — rebuilt every day',
  'featured_text' => 'SportyBet Daily runs on a shorter cycle than weekly megas. Check back daily for the new fixture list and deadline.',
  'article_title' => 'Why daily jackpots need daily updates',
  'faq_heading' => 'SportyBet Daily FAQ',
  'article_html' => <<<'HTML'
<p>Daily products change fixtures and sometimes structure more often than weekend megas. Stale sheets are useless. We treat this URL as a matchday page, not a weekly guide.</p>
HTML,
  'faqs' => [
    ['q' => 'Why daily?', 'a' => 'The product rebuilds on a short cycle — yesterday\'s sheet is useless.'],
    ['q' => 'When do tips refresh?', 'a' => 'Every day with the new fixture list.'],
    ['q' => 'Same as Mega?', 'a' => 'No — shorter cycle and different structure. Confirm on SportyBet.'],
    ['q' => 'Are tips free?', 'a' => 'Yes.'],
    ['q' => 'Deadline?', 'a' => 'Always check SportyBet for the live cutoff.'],
    ['q' => '18+?', 'a' => 'Yes — informational tips only.'],
  ],
],

'odibets-laki-tatu-predictions' => [
  'title' => 'Odibets Laki Tatu Predictions — 3 Game Tips | Bao Predictions',
  'description' => 'Odibets Laki Tatu predictions with deeper notes on three games. Verify the live product name and stake on Odibets.',
  'keywords' => 'odibets laki tatu predictions, laki tatu tips, odibets 3 game jackpot',
  'featured_title' => 'Laki Tatu — three games, deeper notes',
  'featured_text' => 'With only three legs, every pick matters more than on a 17-game mega. We write deeper analysis per match. Verify the live product name and stake on Odibets.',
  'article_title' => 'How Laki Tatu differs from Mega Jackpots',
  'faq_heading' => 'Odibets Laki Tatu FAQ',
  'article_html' => <<<'HTML'
<p>Shorter cards have shorter odds of a clean sweep but smaller pools. Analysis quality per game should go up, not down. Confirm current branding and rules on Odibets before staking.</p>
HTML,
  'faqs' => [
    ['q' => 'What is Laki Tatu?', 'a' => 'A short Odibets jackpot-style product — typically three games. Confirm live branding and rules on Odibets.'],
    ['q' => 'Why deeper notes?', 'a' => 'With fewer legs, each pick carries more weight than on a 17-game mega.'],
    ['q' => 'Do stakes change?', 'a' => 'They can — verify in the Odibets app before staking.'],
    ['q' => 'Are tips free?', 'a' => 'Yes.'],
    ['q' => 'Is a clean sweep likely?', 'a' => 'Easier than 17 perfects, still not likely. Size bankroll for a full loss.'],
    ['q' => '18+ only?', 'a' => 'Yes.'],
  ],
],

'accumulator-tips' => [
  'title' => 'Accumulator Tips Today — Acca Folds | Bao Predictions',
  'description' => 'Pre-built accumulator tips at different risk levels, plus plain-language maths on why long accas fail.',
  'keywords' => 'accumulator tips today, acca tips, 3 fold 5 fold tips',
  'featured_title' => 'Pre-built accas at different risk levels',
  'featured_text' => 'Accumulators multiply odds and multiply failure risk. Our 3-folds aim for realism; longer folds are labelled higher variance on purpose.',
  'article_title' => 'Accumulator maths in plain language',
  'faq_heading' => 'Accumulator Tips FAQ',
  'article_html' => <<<'HTML'
<p>Five legs at 80% independent chance is about 33% to land the whole ticket — not 80%. That is why we build from individually strong leans instead of chasing a huge combined price with weak fillers.</p>
HTML,
  'faqs' => [
    ['q' => 'How many legs should an acca have?', 'a' => 'Fewer stronger legs beat long piles of weak fillers. Our 3-folds aim for realism; longer folds are higher variance.'],
    ['q' => 'What if one match is postponed?', 'a' => 'Bookmaker rules vary — void that leg or void the ticket. Check your operator.'],
    ['q' => 'Where do legs come from?', 'a' => 'From published tips, preferring higher-confidence selections.'],
    ['q' => 'Why does 80% × 5 fail so often?', 'a' => 'Independent 80% legs multiply to about 33% for the whole ticket.'],
    ['q' => 'Are accas free to view?', 'a' => 'Yes.'],
    ['q' => 'Related pages?', 'a' => 'Sure Bets and 1X2 for single-leg building blocks.'],
  ],
],

'jackpot-predictions' => [
  'title' => 'Jackpot Predictions Kenya — SportPesa Betika SportyBet Odibets | Bao Predictions',
  'description' => 'Kenya jackpot predictions hub — SportPesa, Betika, SportyBet, and Odibets sheets with game-by-game reasoning.',
  'keywords' => 'jackpot predictions kenya, sportpesa betika sportybet odibets jackpot tips',
  'featured_title' => 'All Kenya jackpots in one hub',
  'featured_text' => 'Pick your operator sheet for game-by-game 1X2 leans and reasoning. Rules for voids and bonuses differ by bookmaker — always check the official terms.',
  'article_title' => 'Football jackpots explained',
  'faq_heading' => 'Jackpot Predictions FAQ',
  'article_html' => <<<'HTML'
<p>Jackpots ask you to nail a fixed list of results for a pool prize. Most also pay smaller bonuses for near-misses. Bao covers the major Kenyan products with the same transparency standard as daily tips: reasons on the sheet, losses not deleted from history.</p>
HTML,
  'faqs' => [
    ['q' => 'Which jackpots do you cover?', 'a' => 'SportPesa Mega and Midweek, Betika Midweek, SportyBet Daily, and Odibets Laki Tatu.'],
    ['q' => 'Do operator rules differ?', 'a' => 'Yes — voids, bonuses, and stakes differ. Always read the official terms.'],
    ['q' => 'Do you guarantee jackpot wins?', 'a' => 'No. Jackpots are long-shot entertainment products.'],
    ['q' => 'Where are per-game tips?', 'a' => 'On each operator\'s dedicated prediction page linked from this hub.'],
    ['q' => 'Are tips free?', 'a' => 'Yes.'],
    ['q' => '18+ only?', 'a' => 'Yes — bet responsibly.'],
  ],
],

'results' => [
  'title' => 'Football Tip Results & Track Record | Bao Predictions',
  'description' => 'Settled prediction results — wins and losses both stay published so you can audit our track record.',
  'keywords' => 'football tip results, prediction track record, tipster accuracy',
  'featured_title' => 'Wins and losses — both stay published',
  'featured_text' => 'Audit our settled tips before you trust today\'s board. Past performance is not a guarantee of future results.',
  'article_title' => 'How to read Bao\'s track record',
  'faq_heading' => 'Results FAQ',
  'article_html' => <<<'HTML'
<p>Win rate counts only settled predictions where the specific market outcome matched the result. Postponements are excluded. ROI, when shown, uses published odds at tip time — not closing lines after the fact.</p>
HTML,
  'faqs' => [
    ['q' => 'Do you hide losing tips?', 'a' => 'No. Wins and losses both stay published.'],
    ['q' => 'How is win rate calculated?', 'a' => 'Settled predictions where the specific market outcome matched the result. Postponements are excluded.'],
    ['q' => 'Is past performance a guarantee?', 'a' => 'No. It is a transparency tool only.'],
    ['q' => 'What odds do you use for ROI?', 'a' => 'Published odds at tip time when shown — not closing lines after the fact.'],
    ['q' => 'How often is Results updated?', 'a' => 'After matchdays as fixtures settle.'],
    ['q' => 'Where are yesterday\'s tips?', 'a' => 'On the Yesterday predictions page for a daily verification layer.'],
  ],
],

'weekend-football-predictions' => [
  'title' => 'Weekend Football Predictions — Saturday & Sunday Tips | Bao Predictions',
  'description' => 'Weekend football predictions for Saturday and Sunday fixtures, ranked by confidence for ticket planning.',
  'keywords' => 'weekend football predictions, saturday sunday tips',
  'featured_title' => 'Saturday and Sunday in one place',
  'featured_text' => 'Weekend fixtures grouped for ticket planning. Still re-check lineups Saturday morning — Friday tips can move.',
  'article_title' => 'Building a weekend betting plan',
  'faq_heading' => 'Weekend Predictions FAQ',
  'article_html' => <<<'HTML'
<p>Weekend volume is higher. Prioritise sleepers with strong confidence, then fill jackpot cards from the weaker rows knowingly. Do not raise stakes just because more matches are on TV.</p>
HTML,
  'faqs' => [
    ['q' => 'What days are included?', 'a' => 'Saturday and Sunday fixtures we cover that weekend.'],
    ['q' => 'Can Friday tips move?', 'a' => 'Yes — re-check Saturday morning for lineups.'],
    ['q' => 'How should I plan stakes?', 'a' => 'Do not raise stakes just because more matches are on TV. Prioritise higher confidence.'],
    ['q' => 'Jackpots on weekends?', 'a' => 'Use the Jackpot hub and SportPesa Mega sheet alongside this page.'],
    ['q' => 'Are tips free?', 'a' => 'Yes.'],
    ['q' => '18+?', 'a' => 'Yes — informational only.'],
  ],
],

'football-predictions-yesterday' => [
  'title' => 'Yesterday\'s Football Predictions Results | Bao Predictions',
  'description' => 'Yesterday\'s tips vs actual results. Every published pick stays listed — wins and losses.',
  'keywords' => 'yesterday football predictions results, tip verification',
  'featured_title' => 'Yesterday\'s tips vs actual results',
  'featured_text' => 'Verification first. Every published pick from yesterday stays listed with the outcome — wins and losses.',
  'article_title' => 'Why we keep losing tips visible',
  'faq_heading' => 'Yesterday\'s Results FAQ',
  'article_html' => <<<'HTML'
<p>A tips site that deletes losers is not one you should trust. Yesterday's page is the daily proof layer; the Results page is the longer archive.</p>
HTML,
  'faqs' => [
    ['q' => 'Why keep losing tips?', 'a' => 'A site that deletes losers is not trustworthy. Yesterday is the daily proof layer.'],
    ['q' => 'Is this the full archive?', 'a' => 'Yesterday is the daily slice; Results is the longer track record.'],
    ['q' => 'When does Yesterday update?', 'a' => 'After the previous day\'s fixtures settle.'],
    ['q' => 'Can I compare to today?', 'a' => 'Yes — use Today for live tips and Yesterday for verification.'],
    ['q' => 'Do voids count as losses?', 'a' => 'Postponements are generally excluded from win-rate maths.'],
    ['q' => 'Are tips free to review?', 'a' => 'Yes.'],
  ],
],

];
