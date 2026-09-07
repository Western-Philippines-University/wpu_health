/**
 * WPU Medical — HIS UI enhancements (presentation only)
 * Does not alter form posts, AJAX payloads, or existing function contracts.
 */
(function () {
  'use strict';

  var THEME_KEY = 'wpu_his_theme';
  var SIDEBAR_KEY = 'wpu_his_sidebar_collapsed';

  function qs(sel, root) { return (root || document).querySelector(sel); }
  function qsa(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  /* ---------- Theme ---------- */
  function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    try { localStorage.setItem(THEME_KEY, theme); } catch (e) {}
    qsa('[data-his-theme-icon]').forEach(function (el) {
      el.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });
  }

  function initTheme() {
    var saved = null;
    try { saved = localStorage.getItem(THEME_KEY); } catch (e) {}
    if (!saved) {
      saved = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    applyTheme(saved === 'dark' ? 'dark' : 'light');
  }

  function toggleTheme() {
    var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    applyTheme(cur === 'dark' ? 'light' : 'dark');
  }

  /* ---------- Sidebar collapse (desktop) ---------- */
  function initSidebarCollapse() {
    var btn = qs('[data-his-collapse-sidebar]');
    if (!btn) return;
    var collapsed = false;
    try { collapsed = localStorage.getItem(SIDEBAR_KEY) === '1'; } catch (e) {}
    if (collapsed && window.innerWidth > 992) {
      document.body.classList.add('sidebar-collapsed');
    }
    btn.addEventListener('click', function () {
      if (window.innerWidth <= 992) return;
      document.body.classList.toggle('sidebar-collapsed');
      try {
        localStorage.setItem(SIDEBAR_KEY, document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
      } catch (e) {}
    });
  }

  /* ---------- Live clock ---------- */
  function initClock() {
    var el = qs('[data-his-clock]');
    if (!el) return;
    function tick() {
      var d = new Date();
      el.textContent = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    tick();
    setInterval(tick, 1000);
  }

  /* ---------- Dropdowns ---------- */
  function initDropdowns() {
    qsa('[data-his-dropdown]').forEach(function (wrap) {
      var btn = qs('[data-his-dropdown-btn]', wrap);
      var menu = qs('[data-his-dropdown-menu]', wrap);
      if (!btn || !menu) return;
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = menu.classList.contains('open');
        closeAllDropdowns();
        if (!open) menu.classList.add('open');
      });
    });
    document.addEventListener('click', closeAllDropdowns);
  }

  function closeAllDropdowns() {
    qsa('.topbar-dropdown.open, .notif-panel.open').forEach(function (el) {
      el.classList.remove('open');
    });
  }

  /* ---------- Alerts auto-dismiss ---------- */
  function initAlerts() {
    qsa('.alert-container .alert').forEach(function (alert) {
      setTimeout(function () {
        if (!alert.parentNode) return;
        alert.style.transition = 'opacity .3s, transform .3s';
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(12px)';
        setTimeout(function () { if (alert.parentNode) alert.remove(); }, 320);
      }, 5000);
    });
  }

  /* ---------- Command palette ---------- */
  var COMMANDS = [
    { label: 'Dashboard', href: '?page=dashboard', icon: 'fa-tachometer-alt' },
    { label: 'Medical Certificates', href: '?page=certificates_referrals&tab=certificates', icon: 'fa-file-medical' },
    { label: 'Referrals', href: '?page=certificates_referrals&tab=referrals', icon: 'fa-ambulance' },
    { label: 'Dental Records', href: '?page=health_dental_records&records_tab=dental', icon: 'fa-tooth' },
    { label: 'Health Records', href: '?page=health_dental_records&records_tab=health', icon: 'fa-heartbeat' },
    { label: 'Reports', href: '?page=reports', icon: 'fa-chart-bar' },
    { label: 'System Settings', href: '?page=settings', icon: 'fa-cog' },
    { label: 'Admin Management', href: '?page=admin_management', icon: 'fa-users-cog' },
    { label: 'User Logs', href: '?page=user_logs', icon: 'fa-history' },
    { label: 'Backup', href: '?page=backup', icon: 'fa-database' },
    { label: 'Create Certificate', action: 'createCert', icon: 'fa-plus' },
    { label: 'Create Referral', action: 'createRef', icon: 'fa-plus' },
    { label: 'Toggle Theme', action: 'theme', icon: 'fa-moon' },
    { label: 'Lock Session', href: '?lock', icon: 'fa-lock' }
  ];

  function ensureCommandPalette() {
    if (qs('#his-cmd')) return;
    var div = document.createElement('div');
    div.id = 'his-cmd';
    div.className = 'his-cmd';
    div.setAttribute('role', 'dialog');
    div.setAttribute('aria-modal', 'true');
    div.setAttribute('aria-label', 'Command palette');
    div.innerHTML =
      '<div class="his-cmd__panel">' +
      '<input type="search" id="his-cmd-input" placeholder="Search pages and actions…" autocomplete="off" aria-label="Command search">' +
      '<div class="his-cmd__list" id="his-cmd-list" role="listbox"></div>' +
      '</div>';
    document.body.appendChild(div);

    div.addEventListener('click', function (e) {
      if (e.target === div) closeCmd();
    });

    var input = qs('#his-cmd-input');
    input.addEventListener('input', renderCmd);
    input.addEventListener('keydown', function (e) {
      var items = qsa('.his-cmd__item');
      var active = qs('.his-cmd__item.active');
      var idx = items.indexOf(active);
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (items.length) {
          if (active) active.classList.remove('active');
          items[Math.min(idx + 1, items.length - 1)].classList.add('active');
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (items.length) {
          if (active) active.classList.remove('active');
          items[Math.max(idx - 1, 0)].classList.add('active');
        }
      } else if (e.key === 'Enter') {
        e.preventDefault();
        var target = qs('.his-cmd__item.active') || items[0];
        if (target) target.click();
      } else if (e.key === 'Escape') {
        closeCmd();
      }
    });
  }

  function renderCmd() {
    var q = (qs('#his-cmd-input').value || '').toLowerCase().trim();
    var list = qs('#his-cmd-list');
    var filtered = COMMANDS.filter(function (c) {
      return !q || c.label.toLowerCase().indexOf(q) !== -1;
    });
    list.innerHTML = filtered.map(function (c, i) {
      return '<div class="his-cmd__item' + (i === 0 ? ' active' : '') + '" role="option" data-idx="' + i + '">' +
        '<i class="fas ' + c.icon + '" aria-hidden="true"></i><span>' + c.label + '</span></div>';
    }).join('') || '<div class="his-cmd__item" style="cursor:default;opacity:.6">No matches</div>';

    qsa('.his-cmd__item[data-idx]', list).forEach(function (el) {
      el.addEventListener('click', function () {
        runCommand(filtered[parseInt(el.getAttribute('data-idx'), 10)]);
      });
    });
  }

  function runCommand(cmd) {
    closeCmd();
    if (!cmd) return;
    if (cmd.action === 'theme') { toggleTheme(); return; }
    if (cmd.action === 'createCert' && typeof window.openCreateCertificateModal === 'function') {
      window.openCreateCertificateModal();
      return;
    }
    if (cmd.action === 'createRef' && typeof window.openCreateReferralModal === 'function') {
      window.openCreateReferralModal();
      return;
    }
    if (cmd.href) window.location.href = cmd.href;
  }

  function openCmd() {
    ensureCommandPalette();
    qs('#his-cmd').classList.add('open');
    renderCmd();
    setTimeout(function () { qs('#his-cmd-input').focus(); }, 10);
  }

  function closeCmd() {
    var el = qs('#his-cmd');
    if (el) el.classList.remove('open');
  }

  /* ---------- Back to top ---------- */
  function initBackTop() {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'his-backtop';
    btn.setAttribute('aria-label', 'Back to top');
    btn.innerHTML = '<i class="fas fa-arrow-up" aria-hidden="true"></i>';
    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    document.body.appendChild(btn);
    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) btn.classList.add('show');
      else btn.classList.remove('show');
    }, { passive: true });
  }

  /* ---------- Global search (topbar) → command palette ---------- */
  function initTopSearch() {
    var input = qs('#his-global-search');
    if (!input) return;
    input.addEventListener('focus', function () { openCmd(); input.blur(); });
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); openCmd(); }
    });
  }

  /* ---------- Keyboard shortcuts ---------- */
  function initShortcuts() {
    document.addEventListener('keydown', function (e) {
      var tag = (e.target && e.target.tagName) || '';
      var typing = tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || (e.target && e.target.isContentEditable);
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        openCmd();
        return;
      }
      if (e.key === 'Escape') {
        closeCmd();
        closeAllDropdowns();
      }
      if (!typing && (e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 'l') {
        e.preventDefault();
        toggleTheme();
      }
    });
  }

  /* ---------- Mobile table labels (best-effort) ---------- */
  function initMobileTableLabels() {
    qsa('table.data-table').forEach(function (table) {
      var headers = qsa('thead th', table).map(function (th) { return (th.textContent || '').trim(); });
      qsa('tbody tr', table).forEach(function (tr) {
        qsa('td', tr).forEach(function (td, i) {
          if (!td.getAttribute('data-label') && headers[i]) {
            td.setAttribute('data-label', headers[i]);
          }
        });
      });
    });
  }

  /* ---------- Public hooks used by HTML ---------- */
  window.hisToggleTheme = toggleTheme;
  window.hisOpenCommandPalette = openCmd;
  window.hisToggleNotif = function (e) {
    if (e) e.stopPropagation();
    var panel = qs('#his-notif-panel');
    if (!panel) return;
    var wasOpen = panel.classList.contains('open');
    closeAllDropdowns();
    if (!wasOpen) panel.classList.add('open');
  };

  document.addEventListener('DOMContentLoaded', function () {
    initTheme();
    initSidebarCollapse();
    initClock();
    initDropdowns();
    initAlerts();
    initTopSearch();
    initShortcuts();
    initBackTop();
    initMobileTableLabels();
  });
})();
