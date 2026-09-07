/**
 * Pitch-style Show More Matches: refetch next start_index/end_index window and append cards.
 */
(function () {
  function applyKickoffs() {
    if (typeof window.baoApplyKickoffTimes === 'function') {
      window.baoApplyKickoffTimes();
    }
  }

  function setBusy(btn, busy) {
    btn.disabled = !!busy;
    btn.setAttribute('aria-busy', busy ? 'true' : 'false');
    var label = btn.querySelector('.bao-load-more-label');
    var wait = btn.querySelector('.bao-load-more-busy');
    if (label) label.hidden = !!busy;
    if (wait) wait.hidden = !busy;
  }

  async function onClick(btn) {
    if (btn.disabled || btn.getAttribute('aria-busy') === 'true') return;

    var api = btn.getAttribute('data-api') || '';
    var start = parseInt(btn.getAttribute('data-start') || '0', 10);
    var chunk = parseInt(btn.getAttribute('data-chunk') || '20', 10);
    var showDate = btn.getAttribute('data-show-date') === '1';
    var knownTotal = parseInt(btn.getAttribute('data-known-total') || '0', 10);
    if (!api || Number.isNaN(start) || chunk < 1) return;

    var end = start + chunk - 1;
    if (knownTotal > 0) {
      end = Math.min(end, knownTotal - 1);
    }
    if (end < start) {
      btn.closest('.bao-load-more-wrap')?.remove();
      return;
    }

    var block = btn.closest('.matches-block');
    var grid = block && block.querySelector('[data-bao-matches]');
    if (!grid) return;

    var url = api
      + (api.indexOf('?') >= 0 ? '&' : '?')
      + 'start_index=' + encodeURIComponent(String(start))
      + '&end_index=' + encodeURIComponent(String(end))
      + '&format=html'
      + (showDate ? '&show_date=1' : '');

    setBusy(btn, true);
    try {
      var res = await fetch(url, { headers: { Accept: 'application/json' } });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      var data = await res.json();
      var html = (data && data.html) || '';
      var count = data && typeof data.count === 'number' ? data.count : 0;
      var hasMore = !!(data && data.has_more);

      if (html) {
        grid.insertAdjacentHTML('beforeend', html);
        applyKickoffs();
      }

      if (!html || count < 1 || !hasMore) {
        var wrap = btn.closest('.bao-load-more-wrap');
        if (wrap) wrap.remove();
        return;
      }

      var next = data.next_start != null ? parseInt(data.next_start, 10) : end + 1;
      btn.setAttribute('data-start', String(next));
      if (typeof data.max === 'number' && data.max > 0) {
        btn.setAttribute('data-known-total', String(data.max));
      }
    } catch (e) {
      btn.setAttribute('data-error', '1');
    } finally {
      setBusy(btn, false);
    }
  }

  function bind(root) {
    (root || document).querySelectorAll('.bao-load-more').forEach(function (btn) {
      if (btn.dataset.baoBound === '1') return;
      btn.dataset.baoBound = '1';
      btn.addEventListener('click', function () {
        onClick(btn);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      bind(document);
    });
  } else {
    bind(document);
  }

  window.baoBindLoadMore = bind;
})();
