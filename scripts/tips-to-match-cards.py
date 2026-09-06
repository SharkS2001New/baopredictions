#!/usr/bin/env python3
"""Replace tips-table-wrap blocks with bao_matches_html() PHP calls."""
from pathlib import Path
import re
import html as html_lib

PAGES = Path('/Users/mac/Documents/projects/baopredictions/pages')

LEAGUE_NAMES = {
    'EPL': 'Premier League',
    'LAL': 'La Liga',
    'SEA': 'Serie A',
    'BUN': 'Bundesliga',
    'LI1': 'Ligue 1',
    'UCL': 'Champions League',
    'KPL': 'Kenya Premier League',
    'ERE': 'Eredivisie',
    'POR': 'Primeira Liga',
    'SPL': 'Scottish Premiership',
}

PICK_EXPAND = {
    '1': 'Home Win',
    'X': 'Draw',
    '2': 'Away Win',
    'x': 'Draw',
}


def php_str(s: str) -> str:
    s = html_lib.unescape(s or '')
    return "'" + s.replace("\\", "\\\\").replace("'", "\\'") + "'"


def parse_rows(table_html: str):
    games = []
    for tr in re.finditer(r'<tr>(.*?)</tr>', table_html, re.S | re.I):
        block = tr.group(1)
        if '<th' in block.lower():
            continue
        time_m = re.search(r'class="tt-time[^"]*"[^>]*>([^<]+)', block)
        league_m = re.search(r'class="tt-league-code"[^>]*>([^<]+)', block)
        teams_m = re.search(
            r'class="tt-teams">(.*?)</div>',
            block,
            re.S,
        )
        tip_m = re.search(r'class="tt-tip[^"]*"[^>]*>([^<]+)', block)
        odds_m = re.search(r'class="tt-odds"[^>]*>([^<]+)', block)
        score_m = re.search(r'class="score-badge"[^>]*>([^<]+)', block)
        reason_m = re.search(r'class="tt-reason">(.*?)</div>', block, re.S)
        won = None
        if tip_m and 'won' in (tip_m.group(0) if False else ''):
            pass
        if re.search(r'tt-tip\s+won', block):
            won = True
        elif re.search(r'tt-tip\s+lost', block):
            won = False

        if not teams_m:
            continue
        teams_html = teams_m.group(1)
        # strip reason if nested wrongly
        teams_html = re.sub(r'<div class="tt-reason">.*', '', teams_html, flags=re.S)
        parts = re.split(r'<span class="teams-vs">\s*vs\s*</span>', teams_html, flags=re.I)
        if len(parts) < 2:
            continue
        home = html_lib.unescape(re.sub(r'<[^>]+>', '', parts[0])).strip()
        away = html_lib.unescape(re.sub(r'<[^>]+>', '', parts[1])).strip()
        league_raw = html_lib.unescape(league_m.group(1).strip()) if league_m else ''
        # "#1 EPL" -> keep code for lookup
        code = league_raw
        mcode = re.search(r'\b([A-Z]{2,4})\b', league_raw)
        if mcode:
            code = mcode.group(1)
        league_label = league_raw
        if code in LEAGUE_NAMES and not league_raw.startswith('#'):
            league_label = LEAGUE_NAMES[code]
        elif code in LEAGUE_NAMES and league_raw.startswith('#'):
            league_label = f"{league_raw.split()[0]} · {LEAGUE_NAMES[code]}"

        pick_raw = html_lib.unescape(tip_m.group(1).strip()) if tip_m else '—'
        pick = PICK_EXPAND.get(pick_raw, pick_raw)

        game = {
            'time': html_lib.unescape(time_m.group(1).strip()) if time_m else '',
            'league': league_label,
            'home': home,
            'away': away,
            'pick': pick,
            'odds': html_lib.unescape(odds_m.group(1).strip()) if odds_m else '—',
        }
        if score_m:
            score = html_lib.unescape(score_m.group(1).strip())
            if score and score not in ('—', '-', ''):
                game['score'] = score
        if reason_m:
            reason = html_lib.unescape(re.sub(r'<[^>]+>', '', reason_m.group(1))).strip()
            if reason:
                game['reason'] = reason
        if won is not None:
            game['won'] = won
        games.append(game)
    return games


def games_to_php(games, title: str) -> str:
    lines = [
        "<?php",
        "require_once __DIR__ . '/../components/match-cards.php';",
        "echo bao_matches_html([",
    ]
    for g in games:
        parts = [
            "'time' => " + php_str(g.get('time', '')),
            "'league' => " + php_str(g.get('league', '')),
            "'home' => " + php_str(g.get('home', '')),
            "'away' => " + php_str(g.get('away', '')),
            "'pick' => " + php_str(g.get('pick', '')),
            "'odds' => " + php_str(str(g.get('odds', '—'))),
        ]
        if 'score' in g:
            parts.append("'score' => " + php_str(g['score']))
        if 'reason' in g:
            parts.append("'reason' => " + php_str(g['reason']))
        if 'won' in g:
            parts.append("'won' => " + ('true' if g['won'] else 'false'))
        lines.append('  [' + ', '.join(parts) + '],')
    lines.append("], ['title' => " + php_str(title) + "]);")
    lines.append("?>")
    return '\n'.join(lines)


def transform_file(path: Path) -> int:
    text = path.read_text(encoding='utf-8')
    count = 0

    def repl(m):
        nonlocal count
        block = m.group(0)
        title_m = re.search(r'class="tt-head-title"[^>]*>([^<]+)', block)
        title = html_lib.unescape(title_m.group(1).strip()) if title_m else 'Predictions'
        games = parse_rows(block)
        if not games:
            return block
        count += 1
        return games_to_php(games, title)

    new = re.sub(
        r'<div class="tips-table-wrap">.*?</table>\s*</div>\s*</div>',
        repl,
        text,
        flags=re.S,
    )
    if count:
        path.write_text(new, encoding='utf-8')
    return count


def main():
    total = 0
    for path in sorted(PAGES.glob('*.php')):
        n = transform_file(path)
        if n:
            print(f'{path.name}: {n} table(s)')
            total += n
        else:
            # check if has tips-table
            if 'tips-table-wrap' in path.read_text(encoding='utf-8'):
                print(f'{path.name}: FAILED to replace (pattern miss)')
    print(f'done, replaced {total}')


if __name__ == '__main__':
    main()
