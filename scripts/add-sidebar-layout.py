#!/usr/bin/env python3
"""Wrap prediction page game sections in Accuratetip-style main-grid + sidebar."""
from pathlib import Path
import re

PAGES = Path('/Users/mac/Documents/projects/baopredictions/pages')

SIDEBAR = "<?php require __DIR__ . '/../components/sidebar.php'; ?>\n"

OPEN = (
    '<div class="main-grid">\n'
    + SIDEBAR
    + '<div class="matches-area">\n'
)
CLOSE = '</div><!-- /.matches-area -->\n</div><!-- /.main-grid -->\n'

GAME_PAGES = [
    'homepage.php',
    'football-predictions-today.php',
    'football-predictions-tomorrow.php',
    'football-predictions-yesterday.php',
    'weekend-football-predictions.php',
    'must-win-teams-today.php',
    'sure-bets-today.php',
    '1x2-predictions.php',
    'double-chance-predictions.php',
    'over-under-predictions.php',
    'btts-predictions.php',
    'ht-ft-predictions.php',
    'results.php',
    'jackpot-predictions.php',
    'sportpesa-mega-jackpot-predictions.php',
    'sportpesa-midweek-jackpot-predictions.php',
    'betika-midweek-jackpot-predictions.php',
    'sportybet-daily-jackpot-predictions.php',
    'odibets-laki-tatu-predictions.php',
    'accumulator-tips.php',
]


def already_wrapped(text: str) -> bool:
    return 'class="main-grid"' in text or "components/sidebar.php" in text


def wrap_section_tight(text: str) -> tuple[str, bool]:
    """Wrap contents of section-tight > .wrap that contains match listings."""
    # Prefer section-tight that has popular or bao_matches
    pattern = re.compile(
        r'(<section class="section-tight">\s*)'
        r'(<div class="wrap">)'
        r'(.*?)'
        r'(</div>\s*</section>)',
        re.S,
    )

    def repl(m):
        inner = m.group(3)
        if 'bao_matches_html' not in inner and 'popular-scroll' not in inner and 'accu-' not in inner and 'jackpot-' not in inner:
            return m.group(0)
        if 'main-grid' in inner:
            return m.group(0)
        wrap_open = '<div class="wrap wrap-wide">'
        return m.group(1) + wrap_open + '\n' + OPEN + inner + CLOSE + m.group(4)

    new, n = pattern.subn(repl, text, count=1)
    return new, n > 0 and new != text


def wrap_after_hero_wrap(text: str) -> tuple[str, bool]:
    """
    For pages where games sit in a .wrap after page-hero (no section-tight),
    or jackpot pages with section-tight already handled.
    Fallback: wrap around bao_matches_html block's parent wrap.
    """
    if 'bao_matches_html' not in text and 'accu-section' not in text and 'jackpot-spotlight' not in text:
        return text, False

    # Find first <div class="wrap"> that contains bao_matches or popular after a closed hero wrap
    matches = list(re.finditer(r'<div class="wrap">', text))
    for m in matches:
        start = m.start()
        # find matching close for this wrap - approximate by next section or footer
        # Better: check if this wrap contains bao_matches
        # Find end of this div by scanning
        rest = text[start:]
        # If this wrap only has breadcrumbs/hero and no games, skip
        # Look ahead until </div> that closes wrap before section-tight
        chunk_end = rest.find('<section class="section-tight">')
        if chunk_end > 0:
            chunk = rest[:chunk_end]
            if 'bao_matches_html' not in chunk and 'popular-scroll' not in chunk:
                continue
        # Find the wrap that contains bao_matches - search for wrap preceding bao_matches
        break

    # Insert before bao_matches_html's nearest preceding structure
    # Pattern: after page-hero header closes, before popular or matches
    m = re.search(
        r'(</header>\s*)'
        r'(?=<div class="section-head">|<div class="popular-scroll|<\?php\s*\nrequire_once __DIR__ \. \'/../components/match-cards)',
        text,
        re.S,
    )
    if not m:
        m = re.search(
            r'(</header>\s*)'
            r'(?=<div class="section-head">|<\?php\s*\nrequire_once __DIR__ \. \'/../components/match-cards)',
            text,
            re.S,
        )
    if not m:
        return text, False

    # Need enclosing wrap to become wrap-wide and add grid - different approach:
    # Insert OPEN right after </header> inside the games wrap, and CLOSE before that wrap ends
    # Find wrap containing this position
    pos = m.end()
    # Insert OPEN at pos
    # Find CLOSE: before the </div> that closes the wrap containing matches
    # Look for pattern after bao_matches: optional p/btn then </div></section>
    close_m = re.search(
        r'(<\?php\s*\nrequire_once __DIR__ \. \'/../components/match-cards\.php\';.*? \?>)'
        r'(.*?)'
        r'(</div>\s*</section>)',
        text,
        re.S,
    )
    if not close_m:
        # try popular then matches
        close_m = re.search(
            r'(<\?php\s*\nrequire_once __DIR__ \. \'/../components/match-cards\.php\';.*? \?>)'
            r'(\s*(?:<p[^>]*>.*?</p>\s*)?)'
            r'(</div>\s*</section>)',
            text,
            re.S,
        )
    if not close_m:
        return text, False

    # Insert open after header of games section - use wrap_section_tight primarily
    return text, False


def transform(path: Path) -> str:
    text = path.read_text(encoding='utf-8')
    if already_wrapped(text):
        return 'skip (already)'

    new, ok = wrap_section_tight(text)
    if ok:
        path.write_text(new, encoding='utf-8')
        return 'wrapped section-tight'

    # Jackpot / pages: look for section with wrap containing match-cards after page hero outside
    # Try converting first wrap that contains match-cards after section-tight opening fails
    # Alternate: wrap around content starting at popular-scroll or match-cards require inside any wrap
    alt = re.compile(
        r'(<div class="wrap">)'
        r'(\s*(?:<div class="section-head">|<div class="popular-scroll|<\?php\s*\nrequire_once __DIR__ \. \'/../components/match-cards).*?)'
        r'(</div>\s*</section>)',
        re.S,
    )

    def alt_repl(m):
        inner = m.group(2)
        if 'main-grid' in inner:
            return m.group(0)
        return '<div class="wrap wrap-wide">\n' + OPEN + inner + CLOSE + m.group(3)

    new2, n = alt.subn(alt_repl, text, count=1)
    if n and new2 != text:
        path.write_text(new2, encoding='utf-8')
        return 'wrapped alt wrap'

    # Accumulator: section with accu
    acc = re.compile(
        r'(<section class="section[^"]*">\s*<div class="wrap">)'
        r'(.*?(?:accu-section|accu-card|bao_matches_html).*?)'
        r'(</div>\s*</section>)',
        re.S,
    )

    def acc_repl(m):
        if 'main-grid' in m.group(2):
            return m.group(0)
        return (
            '<section class="section-tight">\n<div class="wrap wrap-wide">\n'
            + OPEN
            + m.group(2)
            + CLOSE
            + '</section>'
        )

    # Only if accumulator page
    if path.name == 'accumulator-tips.php':
        # find the block with accu after hero
        m = re.search(
            r'(</div>\s*\n\s*<section class="section[^"]*">\s*<div class="wrap">)'
            r'(.*?)'
            r'(</div>\s*</section>\s*\n\s*<section class="section section-muted">)',
            text,
            re.S,
        )
        if m and 'main-grid' not in m.group(2):
            text = (
                text[: m.start()]
                + '</div>\n<section class="section-tight">\n<div class="wrap wrap-wide">\n'
                + OPEN
                + m.group(2)
                + CLOSE
                + m.group(3)
                + text[m.end() :]
            )
            # Fix: we may have duplicated - let's do simpler for accumulator
            pass

    return 'FAILED'


def main():
    for name in GAME_PAGES:
        path = PAGES / name
        if not path.exists():
            print('missing', name)
            continue
        print(f'{name}: {transform(path)}')


if __name__ == '__main__':
    main()
