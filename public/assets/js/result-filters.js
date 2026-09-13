/**
 * Client filter for settled tip boards (Yesterday / Results-style).
 * Buttons: [data-result-filter="all|won|lost"] inside [data-bao-result-filters]
 * Cards: article.at-match-card[data-outcome="won|lost|pending"]
 */
(function () {
  function apply(root, filter) {
    var cards = root.querySelectorAll('.at-match-card[data-outcome]');
    var shown = 0;
    cards.forEach(function (card) {
      var outcome = card.getAttribute('data-outcome') || 'pending';
      var show = filter === 'all' || outcome === filter;
      card.hidden = !show;
      if (show) shown++;
    });
    var countEl = root.querySelector('[data-result-count]');
    if (countEl) {
      var total = cards.length;
      countEl.textContent =
        filter === 'all'
          ? 'Showing ' + total + ' result' + (total === 1 ? '' : 's')
          : 'Showing ' + shown + ' of ' + total;
    }
  }

  function bind(wrap) {
    var buttons = wrap.querySelectorAll('[data-result-filter]');
    if (!buttons.length) return;
    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var filter = btn.getAttribute('data-result-filter') || 'all';
        buttons.forEach(function (b) {
          var on = b === btn;
          b.classList.toggle('is-active', on);
          b.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        apply(wrap, filter);
      });
    });
    // Re-apply after Show More injects cards
    wrap.addEventListener('bao:matches-updated', function () {
      var active = wrap.querySelector('[data-result-filter].is-active');
      apply(wrap, (active && active.getAttribute('data-result-filter')) || 'all');
    });
  }

  document.querySelectorAll('[data-bao-result-filters]').forEach(bind);
})();
