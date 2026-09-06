/**
 * Convert kickoff times to the visitor's timezone (Pitch Predictions pattern).
 * Server renders Africa/Nairobi times; this upgrades [data-kickoff-utc] in the browser.
 * Readable format: "2:30 PM" today, or "Sun · 2:30 PM" on other days.
 */
(function () {
  function normalizeDateInput(dateValue) {
    if (dateValue == null || dateValue === '') return null;
    if (dateValue instanceof Date) {
      return Number.isNaN(dateValue.getTime()) ? null : dateValue;
    }
    var dateInput = String(dateValue).trim();
    if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}/.test(dateInput)) {
      dateInput = dateInput.replace(' ', 'T');
      if (/T\d{2}:\d{2}$/.test(dateInput)) dateInput += ':00';
      if (dateInput.indexOf('Z') === -1 && dateInput.indexOf('+') === -1) {
        dateInput += 'Z';
      }
    }
    var date = new Date(dateInput);
    return Number.isNaN(date.getTime()) ? null : date;
  }

  function pad2(n) {
    return n < 10 ? '0' + n : String(n);
  }

  function ymdInZone(date, timeZone) {
    var parts = new Intl.DateTimeFormat('en-CA', {
      timeZone: timeZone,
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
    }).formatToParts(date);
    var y = parts.find(function (p) { return p.type === 'year'; });
    var m = parts.find(function (p) { return p.type === 'month'; });
    var d = parts.find(function (p) { return p.type === 'day'; });
    return (y ? y.value : '') + '-' + (m ? m.value : '') + '-' + (d ? d.value : '');
  }

  function formatReadableKickoff(date, timeZone) {
    var parts = new Intl.DateTimeFormat('en-US', {
      timeZone: timeZone,
      weekday: 'short',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    }).formatToParts(date);

    var weekday = '';
    var hour = '';
    var minute = '';
    var dayPeriod = '';
    parts.forEach(function (p) {
      if (p.type === 'weekday') weekday = p.value;
      if (p.type === 'hour') hour = p.value;
      if (p.type === 'minute') minute = p.value;
      if (p.type === 'dayPeriod') dayPeriod = p.value.toUpperCase();
    });

    var clock = hour + ':' + minute + (dayPeriod ? ' ' + dayPeriod : '');
    var kickYmd = ymdInZone(date, timeZone);
    var todayYmd = ymdInZone(new Date(), timeZone);
    if (kickYmd === todayYmd) {
      return clock;
    }
    return weekday + ' · ' + clock;
  }

  function applyKickoffTimes() {
    var userTz;
    try {
      userTz = Intl.DateTimeFormat().resolvedOptions().timeZone || 'Africa/Nairobi';
    } catch (e) {
      userTz = 'Africa/Nairobi';
    }

    document.querySelectorAll('[data-kickoff-utc]').forEach(function (el) {
      var iso = el.getAttribute('data-kickoff-utc');
      var date = normalizeDateInput(iso);
      if (!date) return;
      var label = el.querySelector('.bao-kickoff-label') || el;
      try {
        label.textContent = formatReadableKickoff(date, userTz);
        el.setAttribute('title', 'Kick-off · ' + userTz.replace(/_/g, ' '));
      } catch (err) {
        /* keep server-rendered Nairobi time */
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyKickoffTimes);
  } else {
    applyKickoffTimes();
  }
})();
