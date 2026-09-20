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
        'title' => 'Betensured Prediction Today & Tips | Bao Predictions',
        'description' => 'Betensured prediction today — free Betensured tips across 1X2, Double Chance, BTTS, Over/Under and HT/FT from Bao Predictions.',
        'keywords' => 'betensured, betensured prediction, betensured prediction today, betensured today, betensured tips',
        'h1' => 'Betensured Prediction Today',
        'intro' => '<strong>Betensured</strong> tips on Bao Predictions are a free daily board for today: one recommended market per fixture with the lean and a short reason. Use the cards below for <strong>Betensured prediction today</strong> — not a VIP wall and not a jackpot coupon.',
        'intro_links' => 'Compare with <a href="/sure-bets-today">Sure Bets Today</a> or <a href="/football-predictions-today">Football Predictions Today</a>.',
        'breadcrumb' => 'Betensured Predictions',
        'sections' => ['prediction', 'tips', 'prediction_today', 'how_to_read'],
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'betpera-predictions' => [
        'brand' => 'Betpera',
        'title' => 'Betpera Prediction Today & Tips | Bao Predictions',
        'description' => 'Betpera prediction today — free Betpera tips and Betpera predictions across mixed markets from Bao Predictions.',
        'keywords' => 'betpera, betpera prediction, betpera tips, betpera prediction today, betpera predictions',
        'h1' => 'Betpera Prediction Today',
        'intro' => '<strong>Betpera</strong> predictions on Bao Predictions are free tips for today’s fixtures — one market per match. This page covers <strong>Betpera prediction today</strong> and <strong>Betpera tips</strong> without padding jackpot sections that are not part of this keyword set.',
        'intro_links' => 'Continue on <a href="/football-predictions-today">Football Predictions Today</a> or <a href="/live-football-predictions">Live Football Predictions</a>.',
        'breadcrumb' => 'Betpera Predictions',
        'sections' => ['prediction', 'tips', 'prediction_today', 'how_to_read'],
        'related' => '<a href="/football-predictions-today">Football Predictions Today</a> · <a href="/live-football-predictions">Live Football Predictions</a> · <a href="/results">Results</a>',
    ],

    'forebet-predictions' => [
        'brand' => 'Forebet',
        'title' => 'Forebet Prediction Today, Tomorrow & Mega Jackpot | Bao Predictions',
        'description' => 'Forebet prediction today and Forebet tomorrow — free tips, Mega Jackpot and midweek jackpot links from Bao Predictions.',
        'keywords' => 'forebet, forebet today, forebet prediction, forebet mega jackpot prediction, forebet prediction today, zulubet predictions for today forebet, forebet today prediction, forebet today prediction tips, mega jackpot prediction 17 games today forebet, forebet tomorrow, forebet midweek jackpot predictions, sure mega jackpot predictions this weekend forebet, forebet predictions',
        'h1' => 'Forebet Prediction Today',
        'intro' => '<strong>Forebet</strong> prediction today on Bao Predictions is a free mixed-market board — not a probability API mirror. Cards cover today’s tips; Mega Jackpot (17 games) and midweek coupons link out to the live operator sheets. <strong>Forebet tomorrow</strong> belongs on Tomorrow’s board.',
        'intro_links' => 'Open <a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a>, <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>, or <a href="/zulubet-predictions">Zulubet Predictions</a>.',
        'breadcrumb' => 'Forebet Predictions',
        'sections' => ['prediction', 'tips', 'prediction_today', 'tomorrow', 'mega_jackpot', 'midweek_jackpot', 'cross_forebet_zulubet', 'how_to_read'],
        'related' => '<a href="/zulubet-predictions">Zulubet Predictions</a> · <a href="/football-predictions-tomorrow">Tomorrow</a> · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a> · <a href="/results">Results</a>',
    ],

    'zulubet-predictions' => [
        'brand' => 'Zulubet',
        'title' => 'Zulubet Prediction Today & Mega Jackpot | Bao Predictions',
        'description' => 'Zulubet prediction today — free tips, Mega Jackpot and midweek jackpot links. Also covers Zulubet predictions for today Forebet searches.',
        'keywords' => 'zulubet, zulubet mega jackpot prediction, zulubet predictions for today forebet, zulubet prediction, zulubet midweek jackpot prediction',
        'h1' => 'Zulubet Prediction Today',
        'intro' => '<strong>Zulubet</strong> prediction on Bao Predictions is a free daily tip board. Mega Jackpot and midweek jackpot intents link to the live Kenya sheets. Searches that combine <strong>Zulubet predictions for today Forebet</strong> can use this page or the Forebet landing — both run Bao’s mixed-market engine.',
        'intro_links' => 'See <a href="/forebet-predictions">Forebet Predictions</a>, <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>, or <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek Jackpot Predictions</a>.',
        'breadcrumb' => 'Zulubet Predictions',
        'sections' => ['prediction', 'prediction_today', 'mega_jackpot', 'midweek_jackpot', 'cross_forebet_zulubet', 'how_to_read'],
        'related' => '<a href="/forebet-predictions">Forebet Predictions</a> · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a> · <a href="/results">Results</a>',
    ],

    'mighty-tips' => [
        'brand' => 'Mighty Tips',
        'title' => 'Mighty Tips Prediction Today | Bao Predictions',
        'description' => 'Mighty Tips prediction today — free MightyTips / mighty tips today board. Correct score searches explained honestly.',
        'keywords' => 'mighty tips, mighty tips prediction, mighty tips today, mighty tips predictions, mighty tips correct score, mightytips, mightytips today',
        'h1' => 'Mighty Tips Prediction Today',
        'intro' => '<strong>Mighty Tips</strong> (MightyTips) today on Bao Predictions is a free mixed-market board — one lean per fixture. <strong>Mighty Tips correct score</strong> searches are answered honestly: we do not invent exact scores; cards show the supported market instead.',
        'intro_links' => 'Try <a href="/ht-ft-predictions">HT/FT Predictions</a> or <a href="/banker-of-the-day">Banker of the Day</a> for a tighter pick.',
        'breadcrumb' => 'Mighty Tips',
        'sections' => ['prediction', 'prediction_today', 'tips', 'correct_score', 'how_to_read'],
        'related' => '<a href="/football-predictions-today">Football Predictions Today</a> · <a href="/ht-ft-predictions">HT/FT Predictions</a> · <a href="/results">Results</a>',
    ],

    'mwanasoka-tips' => [
        'brand' => 'Mwanasoka',
        'title' => 'Mwanasoka Tips Today & Mega Jackpot | Bao Predictions',
        'description' => 'Mwanasoka tips today — free Mwanasoka prediction board and Mega Jackpot links for Kenya players.',
        'keywords' => 'mwanasoka tips, mwanasoka, mwanasoka prediction, mwanasoka tips today, mwanasoka mega jackpot prediction',
        'h1' => 'Mwanasoka Tips Today',
        'intro' => '<strong>Mwanasoka</strong> tips on Bao Predictions are free daily football selections for Kenya bettors. This page covers <strong>Mwanasoka tips today</strong> and <strong>Mwanasoka prediction</strong>; Mega Jackpot coupons open on the live SportPesa sheet.',
        'intro_links' => 'Open <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a> or <a href="/jackpots/betika-midweek-jackpot-predictions">Betika Midweek Jackpot Predictions</a>.',
        'breadcrumb' => 'Mwanasoka Tips',
        'sections' => ['tips', 'prediction', 'prediction_today', 'mega_jackpot', 'how_to_read'],
        'h2_today' => 'Mwanasoka Tips Today',
        'related' => '<a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'predictz-tips' => [
        'brand' => 'PredictZ',
        'title' => 'PredictZ Today — Free Tips | Bao Predictions',
        'description' => 'PredictZ today — free PredictZ-style tips. Covers 100 PredictZ and Surebet PredictZ searches without fake guarantees.',
        'keywords' => 'predictz, predictz today, 100 predictz, victor predictz, surebet predictz',
        'h1' => 'PredictZ Today',
        'intro' => '<strong>PredictZ</strong> today on Bao Predictions is a free tip board for the current day’s fixtures. Queries such as <strong>100 PredictZ</strong> or <strong>Surebet PredictZ</strong> do not mean guaranteed wins — confidence is capped, and Sure Bets is a separate higher-floor shortlist.',
        'intro_links' => 'Open <a href="/sure-bets-today">Sure Bets Today</a>, <a href="/victorspredicts">Victor Prediction</a>, or <a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a>.',
        'breadcrumb' => 'PredictZ Tips',
        'sections' => ['prediction_today', 'tips', 'sure_wins_note', 'how_to_read'],
        'h2_today' => 'PredictZ Today',
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/victorspredicts">Victor Prediction</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'primatips-prediction' => [
        'brand' => 'PrimaTips',
        'title' => 'PrimaTips Prediction Today | Bao Predictions',
        'description' => 'PrimaTips today — free PrimaTips prediction board across mixed markets from Bao Predictions.',
        'keywords' => 'primatips, primatips today, primatips prediction',
        'h1' => 'PrimaTips Prediction Today',
        'intro' => '<strong>PrimaTips</strong> today on Bao Predictions is a short free tip list — one market per fixture. This page targets <strong>PrimaTips</strong>, <strong>PrimaTips today</strong> and <strong>PrimaTips prediction</strong> only.',
        'intro_links' => 'Pair with <a href="/sure-bets-today">Sure Bets Today</a> or <a href="/results">Results</a>.',
        'breadcrumb' => 'PrimaTips Prediction',
        'sections' => ['prediction', 'prediction_today', 'how_to_read'],
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'solo-prediction' => [
        'brand' => 'Solo Prediction',
        'title' => 'Solo Prediction Today | Bao Predictions',
        'description' => 'Solo prediction for today — free tips. Correct score and “sure win 100” searches answered without fake guarantees.',
        'keywords' => 'solo prediction, solo prediction for today, solo prediction today, solo prediction correct score, sure win 100 solo prediction',
        'h1' => 'Solo Prediction Today',
        'intro' => '<strong>Solo prediction today</strong> on Bao Predictions is a free singles board — one recommended market per fixture. <strong>Solo prediction correct score</strong> and <strong>sure win 100 Solo prediction</strong> searches are answered without inventing scores or 100% claims.',
        'intro_links' => 'Build multiples on <a href="/accumulator-tips">Accumulator Tips Today</a>, or open <a href="/sure-bets-today">Sure Bets Today</a>.',
        'breadcrumb' => 'Solo Prediction',
        'sections' => ['prediction', 'prediction_today', 'correct_score', 'sure_wins_note', 'how_to_read'],
        'related' => '<a href="/sure-bets-today">Sure Bets Today</a> · <a href="/accumulator-tips">Accumulator Tips</a> · <a href="/results">Results</a>',
    ],

    'statarea-predictions' => [
        'brand' => 'Statarea',
        'title' => 'Statarea Prediction Today 1X2 & Jackpots | Bao Predictions',
        'description' => 'Statarea prediction today 1X2 — free tips. Covers old Statarea, Mega Jackpot and midweek jackpot intents.',
        'keywords' => 'statarea, statarea prediction, old statarea, statarea prediction today, old statarea prediction today, statarea old, statarea mega jackpot prediction, statarea prediction today 1x2, statarea today, statarea midweek jackpot prediction, statarea zulubet today, old statarea prediction',
        'h1' => 'Statarea Prediction Today 1X2',
        'intro' => '<strong>Statarea</strong> today on Bao Predictions is a free tip board with a 1X2 emphasis where the evidence supports it. <strong>Old Statarea</strong> pages should not be treated as today’s card — use the live tips below. Mega and midweek jackpot intents link to operator sheets; <strong>Statarea Zulubet today</strong> can also use the Zulubet landing.',
        'intro_links' => 'Open <a href="/1x2-predictions">1X2 Predictions Today</a>, <a href="/zulubet-predictions">Zulubet Predictions</a>, or <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a>.',
        'breadcrumb' => 'Statarea Predictions',
        'sections' => ['prediction', 'prediction_today', 'onex2', 'old_board', 'mega_jackpot', 'midweek_jackpot', 'how_to_read'],
        'related' => '<a href="/1x2-predictions">1X2 Predictions Today</a> · <a href="/zulubet-predictions">Zulubet Predictions</a> · <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/jackpots/sportpesa-midweek-jackpot-predictions">SportPesa Midweek</a> · <a href="/results">Results</a>',
    ],

    'supatips-prediction' => [
        'brand' => 'Supatips',
        'title' => 'Supatips Prediction Today & Mega Jackpot | Bao Predictions',
        'description' => 'Supatips prediction today — free Supatips today tips and Mega Jackpot links from Bao Predictions.',
        'keywords' => 'supatips, supatips mega jackpot prediction, supatips prediction today, supatips today',
        'h1' => 'Supatips Prediction Today',
        'intro' => '<strong>Supatips</strong> today on Bao Predictions is a free daily tip board. <strong>Supatips Mega Jackpot prediction</strong> belongs on the live SportPesa Mega sheet, not as a fake 17-leg list on this page.',
        'intro_links' => 'Open <a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot Predictions</a> or <a href="/banker-of-the-day">Banker of the Day</a>.',
        'breadcrumb' => 'Supatips Prediction',
        'sections' => ['prediction_today', 'mega_jackpot', 'how_to_read'],
        'related' => '<a href="/jackpots/sportpesa-mega-jackpot-predictions">SportPesa Mega Jackpot</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'tips180-predictions' => [
        'brand' => 'Tips180',
        'title' => 'Tips180 Prediction — Double Chance & Correct Score | Bao Predictions',
        'description' => 'Tips180 prediction — free tips. Covers Tips180 Double Chance and correct score searches; Victorpredict Tips180 linked honestly.',
        'keywords' => 'tips180, victorpredict tips180, tips180 prediction, tips180 double chance, tips180 correct score',
        'h1' => 'Tips180 Prediction',
        'intro' => '<strong>Tips180</strong> prediction on Bao Predictions is a free mixed-market board. <strong>Tips180 Double Chance</strong> and <strong>Tips180 correct score</strong> intents are covered below without inventing exact scores. <strong>Victorpredict Tips180</strong> readers can also use the Victor Prediction landing.',
        'intro_links' => 'See <a href="/double-chance-predictions">Double Chance Predictions</a>, <a href="/victorspredicts">Victor Prediction</a>, or <a href="/football-predictions-today">Football Predictions Today</a>.',
        'breadcrumb' => 'Tips180 Predictions',
        'sections' => ['prediction', 'prediction_today', 'double_chance', 'correct_score', 'how_to_read'],
        'related' => '<a href="/double-chance-predictions">Double Chance Predictions</a> · <a href="/victorspredicts">Victor Prediction</a> · <a href="/results">Results</a>',
    ],

    'victorspredicts' => [
        'brand' => 'Victor Prediction',
        'title' => 'Victor Prediction Today | Bao Predictions',
        'description' => 'Victor prediction today and Victor prediction for today — free tips. Correct score searches answered without invented scorelines.',
        'keywords' => 'victor prediction, victor prediction today, victor prediction for today, victor prediction correct score',
        'h1' => 'Victor Prediction Today',
        'intro' => '<strong>Victor prediction today</strong> on Bao Predictions is a free tip board for the current day’s fixtures. <strong>Victor prediction correct score</strong> is not fabricated here — cards show the supported market with reasoning instead.',
        'intro_links' => 'Open <a href="/football-predictions-tomorrow">Football Predictions Tomorrow</a> or <a href="/tips180-predictions">Tips180 Predictions</a>.',
        'breadcrumb' => 'Victor Prediction',
        'sections' => ['prediction', 'prediction_today', 'correct_score', 'how_to_read'],
        'related' => '<a href="/tips180-predictions">Tips180 Predictions</a> · <a href="/predictz-tips">PredictZ Today</a> · <a href="/football-predictions-today">Football Predictions Today</a> · <a href="/results">Results</a>',
    ],

    'vitibet-predictions' => [
        'brand' => 'Vitibet',
        'title' => 'Vitibet Prediction Today | Bao Predictions',
        'description' => 'Vitibet prediction today — free tips. Covers Vitibet prediction for next 7 days and sure-wins searches without fake guarantees.',
        'keywords' => 'vitibet prediction, vitibet prediction today, vitibet prediction for next 7 days, vitibet prediction today sure wins',
        'h1' => 'Vitibet Prediction Today',
        'intro' => '<strong>Vitibet prediction today</strong> on Bao Predictions is a free daily board. <strong>Vitibet prediction for next 7 days</strong> is planning intent — use Tomorrow and Weekend boards for the wider window. “Sure wins” are not guaranteed.',
        'intro_links' => 'See <a href="/football-predictions-tomorrow">Tomorrow</a>, <a href="/weekend-football-predictions">Weekend Football Predictions</a>, or <a href="/sure-bets-today">Sure Bets Today</a>.',
        'breadcrumb' => 'Vitibet Predictions',
        'sections' => ['prediction', 'prediction_today', 'next_7_days', 'sure_wins_note', 'how_to_read'],
        'related' => '<a href="/football-predictions-tomorrow">Tomorrow</a> · <a href="/weekend-football-predictions">Weekend</a> · <a href="/sure-bets-today">Sure Bets Today</a> · <a href="/results">Results</a>',
    ],

    'windrawwin-predictions' => [
        'brand' => 'WinDrawWin',
        'title' => 'WinDrawWin Today Prediction | Bao Predictions',
        'description' => 'WinDrawWin today prediction — free WinDrawWin prediction and predictions today from Bao Predictions.',
        'keywords' => 'windrawwin, windrawwin today prediction, windrawwin prediction, windrawwin predictions today',
        'h1' => 'WinDrawWin Today Prediction',
        'intro' => '<strong>WinDrawWin</strong> today prediction on Bao Predictions is a free tip board focused on clear match-result and goals leans for today’s fixtures.',
        'intro_links' => 'See <a href="/1x2-predictions">1X2 Predictions Today</a> or <a href="/double-chance-predictions">Double Chance Predictions</a>.',
        'breadcrumb' => 'WinDrawWin Predictions',
        'sections' => ['prediction', 'prediction_today', 'how_to_read'],
        'h2_today' => 'WinDrawWin Today Prediction',
        'related' => '<a href="/1x2-predictions">1X2 Predictions Today</a> · <a href="/double-chance-predictions">Double Chance Predictions</a> · <a href="/results">Results</a>',
    ],

];
