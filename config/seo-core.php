<?php
/**
 * Per-page SEO + unique content blocks (2026 content plan).
 * Keys match page filenames without .php
 */
return [
    'homepage' => [
        'title' => 'Football Predictions Today | Bao Predictions',
        'description' => 'Free daily football predictions with confidence ratings, backed by form and head-to-head stats. Updated every matchday.',
        'h1' => 'Football Predictions Today',
        'canonical' => '/',
        'unique' => 'Bao Predictions publishes free football tips for today\'s slate first — ranked by confidence, not by how famous the club is. Each lean is reviewed against recent form and price before it hits the board. Open a fixture row for the pick; use Must-Win or Sure Bets when you only want the highest-conviction shortlist. Jackpot sheets and accumulator builds sit one click away for the weekend card.',
        'related' => [
            ['Football predictions today', '/football-predictions-today'],
            ['Jackpot predictions', '/jackpot-predictions'],
            ['How we predict', '/how-we-predict'],
        ],
        'faqs' => [
            ['q' => 'Are Bao Predictions free?', 'a' => 'Yes. Daily tips, jackpot sheets, and our public track record are free to view.'],
            ['q' => 'How often are tips updated?', 'a' => 'Throughout the matchday as lineups and prices move. The last-updated stamp on each page shows the latest refresh.'],
            ['q' => 'Do you guarantee winning bets?', 'a' => 'No. Confidence scores are relative strength ratings, not promises. Bet only what you can afford to lose.'],
            ['q' => 'Where can I check past accuracy?', 'a' => 'See the Results page for settled tips, including losses, plus rolling win rate and ROI.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/']],
    ],
    'football-predictions-today' => [
        'title' => null, // set dynamically with date
        'title_tpl' => 'Football Predictions Today, {date} | Bao Predictions',
        'description' => 'Today\'s football predictions with confidence scores and free tips. Updated daily — see the most confident picks before kickoff.',
        'h1_tpl' => 'Football Predictions Today, {date}',
        'canonical' => '/football-predictions-today',
        'unique' => 'Today\'s board opens with Manchester City at home as the clearest percentage lean on the card, while Der Klassiker is tagged for goals rather than a raw 1X2 hammer. The Gor Mahia–Tusker derby stays a low-scoring home lean typical of Kenyan Premier League intensity. Re-check lineups about an hour before kickoff — late absences are where confidence scores move fastest.',
        'related' => [
            ['Tomorrow\'s predictions', '/football-predictions-tomorrow'],
            ['Must-win teams today', '/must-win-teams-today'],
            ['1X2 predictions', '/1x2-predictions'],
        ],
        'faqs' => [
            ['q' => 'How many matches are predicted today?', 'a' => 'We cover every fixture in our leagues that has a clear lean. The count on the homepage stats bar reflects today\'s live total.'],
            ['q' => 'What is today\'s most confident pick?', 'a' => 'Scan the tips table for the highest confidence percentage, or open the Must-Win page for 85%+ only.'],
            ['q' => 'Are today\'s football predictions free?', 'a' => 'Yes. Bao Predictions publishes today\'s tips free of charge.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Today', 'url' => '/football-predictions-today']],
    ],
    'football-predictions-tomorrow' => [
        'title_tpl' => 'Football Predictions Tomorrow, {date} | Bao Predictions',
        'description' => 'Tomorrow\'s football tips with early confidence ratings. Free soccer predictions updated as lineups firm up.',
        'h1_tpl' => 'Football Predictions Tomorrow, {date}',
        'canonical' => '/football-predictions-tomorrow',
        'unique' => 'Tomorrow\'s provisional sheet already flags Real Madrid at the Bernabeu as a high-confidence home lean against Sevilla, while PSG vs Bayern is intentionally marked BTTS rather than a hard match-winner call. Elite European ties punish overconfidence on 1X2 — we would rather publish a goals lean than invent certainty. Scores and confidence may tighten after tonight\'s results.',
        'related' => [
            ['Today\'s predictions', '/football-predictions-today'],
            ['BTTS predictions', '/btts-predictions'],
            ['Accumulator tips', '/accumulator-tips'],
        ],
        'faqs' => [
            ['q' => 'When do tomorrow\'s predictions finalize?', 'a' => 'We publish an early sheet the evening before, then refresh after confirmed lineups — usually 60–90 minutes pre-kickoff.'],
            ['q' => 'Can I build an acca from tomorrow\'s tips?', 'a' => 'Yes. See Accumulator Tips for ready-made 3-, 5-, and 8-folds from higher-confidence selections.'],
            ['q' => 'Are tomorrow\'s tips free?', 'a' => 'Yes.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Tomorrow', 'url' => '/football-predictions-tomorrow']],
    ],
    'football-predictions-yesterday' => [
        'title' => 'Yesterday Football Predictions Results | Bao Predictions',
        'description' => 'Yesterday\'s football prediction results — wins and losses published transparently so you can verify tipster accuracy.',
        'h1' => 'Yesterday\'s Football Predictions Results',
        'canonical' => '/football-predictions-yesterday',
        'unique' => 'This page exists so you can check whether yesterday\'s tips landed before you stake on today\'s. We show wins and losses in the same table — hiding failed picks is how tip sites lose trust. Use it as a verification stop, then cross-check the longer track record on Results for win rate and ROI beyond a single day.',
        'related' => [
            ['Full track record', '/results'],
            ['Today\'s predictions', '/football-predictions-today'],
            ['How we predict', '/how-we-predict'],
        ],
        'faqs' => [
            ['q' => 'Where is the full track record?', 'a' => 'Visit Results for win rate, ROI, and longer history beyond a single day.'],
            ['q' => 'Do you show losing tips?', 'a' => 'Always. Hiding losses is a red flag for tipster sites — and for search quality raters.'],
            ['q' => 'Why check yesterday before betting today?', 'a' => 'Many bettors search yesterday\'s prediction results specifically to judge accuracy before trusting a new day\'s sheet.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Yesterday', 'url' => '/football-predictions-yesterday']],
    ],
    'weekend-football-predictions' => [
        'title' => 'Weekend Football Predictions | Bao Predictions',
        'description' => 'Weekend football predictions across top leagues — Saturday and Sunday tips with confidence scores. Updated Friday evening.',
        'h1' => 'Weekend Football Predictions',
        'canonical' => '/weekend-football-predictions',
        'unique' => 'Weekend cards reward patience: midday underdogs and evening favourites behave differently. We keep cautious London derbies toward unders or draws when form is flat, and load confidence into clearer Sunday favourites once Saturday\'s results reshape the table. Pair this page with the SportPesa Mega Jackpot sheet if you are filling a 17-fold.',
        'related' => [
            ['SportPesa Mega Jackpot', '/sportpesa-mega-jackpot-predictions'],
            ['Today\'s predictions', '/football-predictions-today'],
            ['Over/Under predictions', '/over-under-predictions'],
        ],
        'faqs' => [
            ['q' => 'Does this include jackpot fixtures?', 'a' => 'Weekend predictions cover league tips. For the ordered 17-game SportPesa Mega sheet, use the dedicated jackpot page.'],
            ['q' => 'When is the weekend sheet updated?', 'a' => 'Friday evening for early leans, then Saturday morning before first kickoffs.'],
            ['q' => 'Are weekend predictions free?', 'a' => 'Yes.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Weekend', 'url' => '/weekend-football-predictions']],
    ],
    'must-win-teams-today' => [
        'title' => 'Must Win Teams Today | Bao Predictions',
        'description' => 'Must win teams today — 85%+ confidence football tips only. Free shortlist with clear must-win criteria explained.',
        'h1' => 'Must Win Teams Today',
        'canonical' => '/must-win-teams-today',
        'unique' => 'On Bao, a must-win tip is not marketing speak. It means we rate the lean at 85%+ confidence because of table pressure or a clear mismatch — title races, European qualification six-pointers, relegation scraps, or elite home favourites against mid-table visitors with no key absences we can see. Famous clubs at 60% do not appear here. Still not a guarantee: football produces upsets.',
        'related' => [
            ['Sure bets today', '/sure-bets-today'],
            ['Today\'s full list', '/football-predictions-today'],
            ['Accumulator tips', '/accumulator-tips'],
        ],
        'faqs' => [
            ['q' => 'What does must-win mean?', 'a' => 'Tips we rate 85%+ based on form, table context, and price — not a promise the team will win.'],
            ['q' => 'How is this different from the homepage?', 'a' => 'The homepage shows the full slate. This page filters to high-conviction leans and states the criteria up front.'],
            ['q' => 'Can I build an acca from must-wins?', 'a' => 'Yes — start here, then check Accumulator Tips for pre-built tickets.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Must-Win', 'url' => '/must-win-teams-today']],
    ],
    'sure-bets-today' => [
        'title' => 'Sure Bets Today — High Confidence Tips | Bao Predictions',
        'description' => 'Sure bets today means high-confidence football tips (80%+), not guaranteed wins. Free shortlist updated daily.',
        'h1' => 'Sure Bets Today (High Confidence)',
        'canonical' => '/sure-bets-today',
        'unique' => 'Searchers use "sure bets today" and "banker bets" for high-confidence tips — not risk-free arbs. On this page we only list leans at 80%+ confidence. No tip is guaranteed. If a famous side sits at 65%, it will not appear here. Read the pick, check the price, and stake only what you can afford to lose.',
        'related' => [
            ['Must-win teams today', '/must-win-teams-today'],
            ['Today\'s predictions', '/football-predictions-today'],
            ['Responsible betting', '/responsible-betting'],
        ],
        'faqs' => [
            ['q' => 'Are these risk-free sure bets?', 'a' => 'No. There is no risk-free football tip. This is a high-confidence filter only.'],
            ['q' => 'What confidence level do you use?', 'a' => '80%+ here; 85%+ on the Must-Win page.'],
            ['q' => 'Do you offer fixed odds yourself?', 'a' => 'No. Bao Predictions is not a bookmaker.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Sure Bets', 'url' => '/sure-bets-today']],
    ],
    'betnumbers-tips' => [
        'title' => 'BetNumbers Tips — Mixed Market Picks | Bao Predictions',
        'description' => 'BetNumbers tips: best lean across 1X2, BTTS, Over/Under 2.5, and Double Chance with fitting odds. Free daily shortlist.',
        'h1' => 'BetNumbers Tips Today',
        'canonical' => '/betnumbers-tips',
        'unique' => 'Each fixture gets one tip from four markets. We rank by winning chance, then by how well the book price fits that chance. Odds outside a stakeable band are skipped.',
        'related' => [
            ['Sure bets today', '/sure-bets-today'],
            ['Must-win teams today', '/must-win-teams-today'],
            ['1X2 predictions', '/1x2-predictions'],
        ],
        'faqs' => [
            ['q' => 'Which markets?', 'a' => '1X2, BTTS, Over/Under 2.5, and Double Chance.'],
            ['q' => 'How do you choose?', 'a' => 'Best win chance first, then best-fitting odds.'],
            ['q' => 'Guaranteed?', 'a' => 'No. Informational tips only — 18+.'],
        ],
        'rg' => true,
        'crumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'BetNumbers Tips', 'url' => '/betnumbers-tips']],
    ],
];
