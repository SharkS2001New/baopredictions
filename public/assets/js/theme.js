/**
 * Light / dark theme toggle for Bao Predictions.
 * Persists to localStorage key `bao-theme`.
 * Shows a thin top progress bar during same-origin navigation (no full-screen overlay).
 */
(function () {
  var KEY = 'bao-theme';

  function getPreferred() {
    try {
      var saved = localStorage.getItem(KEY);
      if (saved === 'dark' || saved === 'light') return saved;
    } catch (e) {}
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function apply(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    try {
      localStorage.setItem(KEY, theme);
    } catch (e) {}
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      var isDark = theme === 'dark';
      btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
      btn.title = isDark ? 'Light mode' : 'Dark mode';
    });
  }

  function toggle() {
    var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    apply(next);
  }

  apply(getPreferred());

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-theme-toggle]');
    if (btn) {
      e.preventDefault();
      toggle();
    }
  });

  try {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
      try {
        if (!localStorage.getItem(KEY)) apply(e.matches ? 'dark' : 'light');
      } catch (err) {}
    });
  } catch (e) {}

  /* —— Thin top progress (no black full-screen overlay) —— */
  var loader = document.getElementById('bao-page-loader');

  function showLoader() {
    if (!loader) return;
    loader.classList.add('is-active');
    loader.setAttribute('aria-busy', 'true');
    loader.setAttribute('aria-hidden', 'false');
  }

  function hideLoader() {
    if (!loader) return;
    loader.classList.remove('is-active');
    loader.setAttribute('aria-busy', 'false');
    loader.setAttribute('aria-hidden', 'true');
  }

  function isModifiedClick(e) {
    return e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button === 1;
  }

  function shouldShowForLink(a) {
    if (!a) return false;
    if (a.target && a.target !== '' && a.target !== '_self') return false;
    if (a.hasAttribute('download')) return false;
    var href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#') return false;
    if (/^(mailto:|tel:|javascript:)/i.test(href)) return false;
    try {
      var url = new URL(href, window.location.href);
      if (url.origin !== window.location.origin) return false;
      if (url.pathname === window.location.pathname && url.search === window.location.search) {
        return false;
      }
    } catch (err) {
      return false;
    }
    return true;
  }

  document.addEventListener('click', function (e) {
    if (isModifiedClick(e)) return;
    var a = e.target.closest && e.target.closest('a[href]');
    if (!a || !shouldShowForLink(a)) return;
    showLoader();
  }, true);

  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;
    if (form.target && form.target !== '' && form.target !== '_self') return;
    showLoader();
  }, true);

  window.addEventListener('pageshow', hideLoader);
  window.addEventListener('load', hideLoader);
  setTimeout(hideLoader, 12000);
})();
