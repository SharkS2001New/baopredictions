<?php
/**
 * Brand tip-board meta (URL slug = /api/{slug}).
 * Unique SEO HTML lives in pages/brand-seo/{slug}.php — not a shared helper.
 * Each page gets its own fixture slate via board_key in config/api-pages.php.
 *
 * `intro` may contain trusted HTML (<strong>, <a>) — rendered in the hero.
 */
return [

    'betensured-predictions' => [
        'brand' => 'Betensured',
        'title' => 'Betensured Prediction: Tips & Analysis | Bao',
        'description' => 'Betensured prediction tips for 1X2, Double Chance, BTTS and Over/Under — check the date, form and market before you stake.',
        'keywords' => 'betensured, betensured prediction, betensured prediction today, betensured today, betensured tips',
        'h1' => 'Betensured Prediction: Football Tips and Analysis',
        'intro' => 'A <strong>Betensured prediction</strong> is a football tip tied to specific matches and markets — 1X2, Double Chance, BTTS and Over/Under. For Kenya and broader Africa, start with the match context behind the tip: teams, recent form, markets and the publish date. Use the free cards below for <strong>Betensured prediction today</strong>.',
        'intro_links' => 'Compare with <a href="/sure-bets-today">Sure Bets Today</a> or <a href="/football-predictions-today">Football Predictions Today</a>.',
        'breadcrumb' => 'Betensured Predictions',
        'sections' => ['prediction', 'tips', 'prediction_today', 'how_to_read'],
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'betpera-predictions' => [
        'brand' => 'Betpera',
        'title' => 'Betpera Prediction: Football Tips Today | Bao',
        'description' => 'Betpera prediction today: how to read 1X2, Double Chance, BTTS and Over/Under tips — check the fixture date before you stake.',
        'keywords' => 'betpera, betpera prediction, betpera tips, betpera prediction today, betpera predictions',
        'h1' => 'Betpera Prediction: Football Tips for Today',
        'intro' => 'A <strong>Betpera prediction</strong> covers match-result and goal markets across leagues. Betpera lists 1X2, Double Chance, BTTS and Over/Under alongside categories such as Super Single. For <strong>Betpera prediction today</strong>, check the fixture date — team news can change before kickoff. Free tips are on the cards below.',
        'intro_links' => 'Continue on <a href="/football-predictions-today">Football Predictions Today</a> or <a href="/live-football-predictions">Live Football Predictions</a>.',
        'breadcrumb' => 'Betpera Predictions',
        'sections' => ['prediction', 'tips', 'prediction_today', 'how_to_read'],
        'related' => '<a href="/football-predictions-today">Football Predictions Today</a> · <a href="/live-football-predictions">Live Football Predictions</a> · <a href="/results">Results</a>',
    ],

    'forebet-predictions' => [
        'brand' => 'Forebet',
        'title' => 'Forebet Prediction: Football Tips Today | Bao',
        'description' => 'Forebet prediction today: free tips across 1X2, Double Chance, BTTS and Over/Under — check the fixture date before you stake.',
        'keywords' => 'forebet, forebet today, forebet prediction, forebet mega jackpot prediction, forebet prediction today, zulubet predictions for today forebet, forebet today prediction, forebet today prediction tips, mega jackpot prediction 17 games today forebet, forebet tomorrow, forebet midweek jackpot predictions, sure mega jackpot predictions this weekend forebet, forebet predictions',
        'h1' => 'Forebet Prediction: Football Tips for Today',
        'intro' => 'A <strong>Forebet prediction</strong> is a mathematically generated forecast, usually shown as 1X2 probabilities with recent-form trends. This page turns that into one recommended market per fixture — 1X2, Double Chance, BTTS, Over/Under or HT/FT — with a reason attached. <strong>Forebet tomorrow</strong> belongs on the next-day board; Mega Jackpot coupons open on the live operator sheets.',
        'intro_links' => 'Open <a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a>, <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>, or <a href="/zulubet-predictions">Zulubet Predictions</a>.',
        'breadcrumb' => 'Forebet Predictions',
        'sections' => ['prediction', 'tips', 'prediction_today', 'tomorrow', 'mega_jackpot', 'midweek_jackpot', 'cross_forebet_zulubet', 'how_to_read'],
        'related' => '<a href="/zulubet-predictions">Zulubet Predictions</a> · <a href="/football-predictions-tomorrow">Tomorrow</a> · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a> · <a href="/results">Results</a>',
    ],

    'zulubet-predictions' => [
        'brand' => 'Zulubet',
        'title' => 'Zulubet Prediction: Football Tips Today | Bao',
        'description' => 'Zulubet prediction today: free tips with reasons across 1X2, Double Chance and goals markets. Jackpots on live operator sheets.',
        'keywords' => 'zulubet, zulubet mega jackpot prediction, zulubet predictions for today forebet, zulubet prediction, zulubet midweek jackpot prediction',
        'h1' => 'Zulubet Prediction: Football Tips for Today',
        'intro' => 'A <strong>Zulubet prediction</strong> is a daily football forecast shown as 1X2 percentages per fixture. This board turns that into one recommended market per match with a reason attached. Mega Jackpot and midweek intents open on the live Kenya sheets, and <strong>Zulubet predictions for today Forebet</strong> searches can use this page or the Forebet landing.',
        'intro_links' => 'See <a href="/forebet-predictions">Forebet Predictions</a>, <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>, or <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek Jackpot Predictions</a>.',
        'breadcrumb' => 'Zulubet Predictions',
        'sections' => ['prediction', 'prediction_today', 'mega_jackpot', 'midweek_jackpot', 'cross_forebet_zulubet', 'how_to_read'],
        'related' => '<a href="/forebet-predictions">Forebet Predictions</a> · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a> · <a href="/results">Results</a>',
    ],

    'mighty-tips' => [
        'brand' => 'Mighty Tips',
        'title' => 'Mighty Tips Prediction: Football Tips | Bao',
        'description' => 'Mighty Tips prediction today: free MightyTips board across 1X2, Double Chance, BTTS and Over/Under — no invented correct scores.',
        'keywords' => 'mighty tips, mighty tips prediction, mighty tips today, mighty tips predictions, mighty tips correct score, mightytips, mightytips today',
        'h1' => 'Mighty Tips Prediction: Football Tips for Today',
        'intro' => 'A <strong>Mighty Tips prediction</strong> (MightyTips) is a daily football tip tied to a dated fixture, covering result and goals markets. This board publishes one lean per match with a reason. <strong>Mighty Tips correct score</strong> searches are answered honestly — no invented scorelines, just the market the evidence supports.',
        'intro_links' => 'Try <a href="/ht-ft-predictions">HT/FT Predictions</a> or <a href="/banker-of-the-day">Banker of the Day</a> for a tighter pick.',
        'breadcrumb' => 'Mighty Tips',
        'sections' => ['prediction', 'prediction_today', 'tips', 'correct_score', 'how_to_read'],
        'related' => '<a href="/football-predictions-today">Football Predictions Today</a> · <a href="/ht-ft-predictions">HT/FT Predictions</a> · <a href="/results">Results</a>',
    ],

    'mwanasoka-tips' => [
        'brand' => 'Mwanasoka',
        'title' => 'Mwanasoka Tips: Football Predictions Today | Bao',
        'description' => 'Mwanasoka tips today: free daily football predictions for Kenya with reasons. Mega Jackpot on the live SportPesa sheet.',
        'keywords' => 'mwanasoka tips, mwanasoka, mwanasoka prediction, mwanasoka tips today, mwanasoka mega jackpot prediction',
        'h1' => 'Mwanasoka Tips: Football Predictions for Today',
        'intro' => '<strong>Mwanasoka tips</strong> are daily football selections for Kenyan bettors across result and goals markets. This board publishes one recommended market per fixture with a short reason. A <strong>Mwanasoka Mega Jackpot prediction</strong> is a separate product — those coupons open on the live SportPesa sheet.',
        'intro_links' => 'Open <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a> or <a href="/jackpots/betika-midweek-jackpot-predictions">Betika Midweek Jackpot Predictions</a>.',
        'breadcrumb' => 'Mwanasoka Tips',
        'sections' => ['tips', 'prediction', 'prediction_today', 'mega_jackpot', 'how_to_read'],
        'h2_today' => 'Mwanasoka Tips Today',
        'related' => '<a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'predictz-tips' => [
        'brand' => 'PredictZ',
        'title' => 'PredictZ Today: Football Tips & Analysis | Bao',
        'description' => 'PredictZ today: free football tips with reasons. 100 PredictZ and Surebet PredictZ searches answered without fake guarantees.',
        'keywords' => 'predictz, predictz today, 100 predictz, victor predictz, surebet predictz',
        'h1' => 'PredictZ Today: Football Tips and Analysis',
        'intro' => 'A <strong>PredictZ</strong> tip is a daily football prediction organised by fixture date across result and goals markets. This board publishes one recommended market per match with a reason. Searches such as <strong>100 PredictZ</strong> and <strong>Surebet PredictZ</strong> imply certainty football cannot provide — confidence here is capped, and Sure Bets is a separate shortlist.',
        'intro_links' => 'Open <a href="/sure-bets-today">Sure Bets Today</a>, <a href="/victorspredicts">Victor Prediction</a>, or <a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a>.',
        'breadcrumb' => 'PredictZ Tips',
        'sections' => ['prediction_today', 'tips', 'sure_wins_note', 'how_to_read'],
        'h2_today' => 'PredictZ Today',
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/victorspredicts">Victor Prediction</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'primatips-prediction' => [
        'brand' => 'PrimaTips',
        'title' => 'PrimaTips Prediction: Football Tips Today | Bao',
        'description' => 'PrimaTips today: a short free prediction board across 1X2, Double Chance, BTTS, Over/Under and HT/FT, with reasons on each card.',
        'keywords' => 'primatips, primatips today, primatips prediction',
        'h1' => 'PrimaTips Prediction: Football Tips for Today',
        'intro' => 'A <strong>PrimaTips prediction</strong> is a short daily football tip tied to a dated fixture, covering result and goals markets. This board keeps the list compact — one recommended market per match with a short reason — and targets <strong>PrimaTips today</strong> and <strong>PrimaTips prediction</strong> without padding in unrelated sections.',
        'intro_links' => 'Pair with <a href="/sure-bets-today">Sure Bets Today</a> or <a href="/results">Results</a>.',
        'breadcrumb' => 'PrimaTips Prediction',
        'sections' => ['prediction', 'prediction_today', 'how_to_read'],
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'solo-prediction' => [
        'brand' => 'Solo Prediction',
        'title' => 'Solo Prediction: Football Tips for Today | Bao',
        'description' => 'Solo prediction today: free single-match tips with reasons. Correct score and sure-win-100 searches answered without guarantees.',
        'keywords' => 'solo prediction, solo prediction for today, solo prediction today, solo prediction correct score, sure win 100 solo prediction',
        'h1' => 'Solo Prediction: Football Tips for Today',
        'intro' => 'A <strong>Solo prediction</strong> is a single-match football tip, staked on its own rather than combined into a multiple. This board publishes one recommended market per fixture with a short reason. <strong>Solo prediction correct score</strong> and <strong>sure win 100 Solo prediction</strong> searches are answered without invented scorelines or 100% claims.',
        'intro_links' => 'Build multiples on <a href="/accumulator-tips">Accumulator Tips Today</a>, or open <a href="/sure-bets-today">Sure Bets Today</a>.',
        'breadcrumb' => 'Solo Prediction',
        'sections' => ['prediction', 'prediction_today', 'correct_score', 'sure_wins_note', 'how_to_read'],
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/accumulator-tips">Accumulator Tips</a> · <a href="/results">Results</a>',
    ],

    'statarea-predictions' => [
        'brand' => 'Statarea',
        'title' => 'Statarea Prediction Today 1X2: Tips | Bao',
        'description' => 'Statarea prediction today 1X2: free tips with reasons. An Old Statarea view is not today’s card — check the fixture date first.',
        'keywords' => 'statarea, statarea prediction, old statarea, statarea prediction today, old statarea prediction today, statarea old, statarea mega jackpot prediction, statarea prediction today 1x2, statarea today, statarea midweek jackpot prediction, statarea zulubet today, old statarea prediction',
        'h1' => 'Statarea Prediction Today 1X2: Football Tips',
        'intro' => 'A <strong>Statarea prediction</strong> is a statistics-led forecast shown as a grid of market columns per fixture. This board leans 1X2 where the evidence supports it and switches market when it does not. <strong>Old Statarea</strong> views are archives, not today’s card; jackpot intents open on the live operator sheets.',
        'intro_links' => 'Open <a href="/1x2-predictions">1X2 Predictions Today</a>, <a href="/zulubet-predictions">Zulubet Predictions</a>, or <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>.',
        'breadcrumb' => 'Statarea Predictions',
        'sections' => ['prediction', 'prediction_today', 'onex2', 'old_board', 'mega_jackpot', 'midweek_jackpot', 'how_to_read'],
        'related' => '<a href="/1x2-predictions">1X2 Predictions Today</a> · <a href="/zulubet-predictions">Zulubet Predictions</a> · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a> · <a href="/results">Results</a>',
    ],

    'supatips-prediction' => [
        'brand' => 'Supatips',
        'title' => 'Supatips Prediction: Football Tips Today | Bao',
        'description' => 'Supatips prediction today: free daily tips with reasons. Mega Jackpot coupons open on the live SportPesa sheet, not this board.',
        'keywords' => 'supatips, supatips mega jackpot prediction, supatips prediction today, supatips today',
        'h1' => 'Supatips Prediction: Football Tips for Today',
        'intro' => 'A <strong>Supatips prediction</strong> is a daily football tip aimed largely at Kenyan bettors, covering result and goals markets. This board publishes one recommended market per fixture with a short reason. A <strong>Supatips Mega Jackpot prediction</strong> means the live SportPesa 17-game card, which is a separate product from this singles board.',
        'intro_links' => 'Open <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a> or <a href="/banker-of-the-day">Banker of the Day</a>.',
        'breadcrumb' => 'Supatips Prediction',
        'sections' => ['prediction_today', 'mega_jackpot', 'how_to_read'],
        'related' => '<a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'tips180-predictions' => [
        'brand' => 'Tips180',
        'title' => 'Tips180 Prediction: Double Chance Tips | Bao',
        'description' => 'Tips180 prediction: free tips with reasons. When Double Chance beats 1X2, and why correct score is not invented here.',
        'keywords' => 'tips180, victorpredict tips180, tips180 prediction, tips180 double chance, tips180 correct score',
        'h1' => 'Tips180 Prediction: Double Chance and Daily Tips',
        'intro' => 'A <strong>Tips180 prediction</strong> is a football tip published against a dated fixture list, with <strong>Tips180 Double Chance</strong> among the most-searched markets. This board explains when covering two outcomes is genuinely the better bet, and it does not invent correct scores. <strong>Victorpredict Tips180</strong> readers can also use the Victor Prediction landing.',
        'intro_links' => 'See <a href="/double-chance-predictions">Double Chance Predictions</a>, <a href="/victorspredicts">Victor Prediction</a>, or <a href="/football-predictions-today">Football Predictions Today</a>.',
        'breadcrumb' => 'Tips180 Predictions',
        'sections' => ['prediction', 'prediction_today', 'double_chance', 'correct_score', 'how_to_read'],
        'related' => '<a href="/double-chance-predictions">Double Chance Predictions</a> · <a href="/victorspredicts">Victor Prediction</a> · <a href="/results">Results</a>',
    ],

    'victorspredicts' => [
        'brand' => 'Victor Prediction',
        'title' => 'Victor Prediction: Football Tips Today | Bao',
        'description' => 'Victor prediction today: free football tips with reasons on every card. Correct score searches answered without invented scorelines.',
        'keywords' => 'victor prediction, victor prediction today, victor prediction for today, victor prediction correct score',
        'h1' => 'Victor Prediction: Football Tips for Today',
        'intro' => 'A <strong>Victor prediction</strong> is a daily football tip published against a dated fixture list across result and goals markets. This board names one market per match with the reason attached. <strong>Victor prediction correct score</strong> searches are answered honestly — no fabricated scorelines, just the market the evidence supports.',
        'intro_links' => 'Open <a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a> or <a href="/tips180-predictions">Tips180 Predictions</a>.',
        'breadcrumb' => 'Victor Prediction',
        'sections' => ['prediction', 'prediction_today', 'correct_score', 'how_to_read'],
        'related' => '<a href="/tips180-predictions">Tips180 Predictions</a> · <a href="/predictz-tips">PredictZ Today</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'vitibet-predictions' => [
        'brand' => 'Vitibet',
        'title' => 'Vitibet Prediction: Football Tips Today | Bao',
        'description' => 'Vitibet prediction today: free tips with reasons. Next-7-days planning maps to the Tomorrow and Weekend boards. No sure wins.',
        'keywords' => 'vitibet prediction, vitibet prediction today, vitibet prediction for next 7 days, vitibet prediction today sure wins',
        'h1' => 'Vitibet Prediction: Football Tips for Today',
        'intro' => 'A <strong>Vitibet prediction</strong> is a statistically generated forecast published across a rolling calendar rather than a single day. This board covers today with one recommended market per fixture. <strong>Vitibet prediction for next 7 days</strong> is planning intent — use the Tomorrow and Weekend boards, and treat early leans as provisional.',
        'intro_links' => 'See <a href="/football-predictions-tomorrow">Tomorrow</a>, <a href="/weekend-football-predictions">Weekend Football Predictions</a>, or <a href="/sure-bets-today">Sure Bets Today</a>.',
        'breadcrumb' => 'Vitibet Predictions',
        'sections' => ['prediction', 'prediction_today', 'next_7_days', 'sure_wins_note', 'how_to_read'],
        'related' => '<a href="/football-predictions-tomorrow">Tomorrow</a> · <a href="/weekend-football-predictions">Weekend</a> · <a href="/sure-bets-today">Sure Bets Today</a> · <a href="/results">Results</a>',
    ],

    'windrawwin-predictions' => [
        'brand' => 'WinDrawWin',
        'title' => 'WinDrawWin Today Prediction: Tips | Bao',
        'description' => 'WinDrawWin today prediction: free result-led tips with reasons, plus when Double Chance or goals beat a forced 1X2.',
        'keywords' => 'windrawwin, windrawwin today prediction, windrawwin prediction, windrawwin predictions today',
        'h1' => 'WinDrawWin Prediction: Football Tips for Today',
        'intro' => 'A <strong>WinDrawWin prediction</strong> is a football forecast built around the three match outcomes — home win, draw, away win — with statistics behind each fixture. This board keeps that result-first instinct but switches to Double Chance or a goals market when the evidence stops supporting a straight winner.',
        'intro_links' => 'See <a href="/1x2-predictions">1X2 Predictions Today</a> or <a href="/double-chance-predictions">Double Chance Predictions</a>.',
        'breadcrumb' => 'WinDrawWin Predictions',
        'sections' => ['prediction', 'prediction_today', 'how_to_read'],
        'h2_today' => 'WinDrawWin Today Prediction',
        'related' => '<a href="/1x2-predictions">1X2 Predictions Today</a> · <a href="/double-chance-predictions">Double Chance Predictions</a> · <a href="/results">Results</a>',
    ],

];
