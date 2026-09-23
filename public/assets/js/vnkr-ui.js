/**
 * VNKR.VN — UI Library JavaScript
 * ==================================
 * File: vnkr-ui.js
 * Phụ thuộc: vnkr-tokens.css, vnkr-ui.css
 * Exposed global: window.VNKRUI
 *
 * Modules:
 *   1. Theme Toggle     — dark/light mode với localStorage + prefers-color-scheme
 *   2. Toast            — thông báo nổi (success / error / info / warning)
 *   3. Modal            — open/close dialog, focus trap, ESC để đóng
 *   4. Accordion        — toggle show/hide vnkr-accordion-item
 *   5. Dropdown         — open/close vnkr-dropdown, click-outside
 *   6. Scroll Watcher   — thêm class .vnkr-nav--scrolled khi cuộn > 60px
 */

(function (w, d) {
  'use strict';

  /* ══════════════════════════════════════════════
     1. THEME TOGGLE
     ══════════════════════════════════════════════ */

  var ThemeModule = (function () {
    var KEY    = 'vnkr-theme';
    var DARK   = 'dark';
    var LIGHT  = 'light';

    /** Đọc theme hiện tại từ html[data-theme] */
    function current() {
      return d.documentElement.dataset.theme || LIGHT;
    }

    /** Áp dụng theme vào DOM + lưu localStorage */
    function apply(theme) {
      d.documentElement.dataset.theme = theme;
      try { localStorage.setItem(KEY, theme); } catch (e) {}
      _updateIcon(theme);
      _updateMetaTheme(theme);
    }

    /** Toggle giữa dark & light */
    function toggle() {
      apply(current() === DARK ? LIGHT : DARK);
    }

    /** Sync icon nút toggle */
    function _updateIcon(theme) {
      var icon = d.getElementById('vnkr-theme-icon');
      if (!icon) return;
      if (theme === DARK) {
        icon.className = 'bi bi-moon-stars-fill';
      } else {
        icon.className = 'bi bi-sun-fill';
      }
    }

    /** Cập nhật <meta name="theme-color"> cho PWA */
    function _updateMetaTheme(theme) {
      var meta = d.querySelector('meta[name="theme-color"]');
      if (!meta) return;
      meta.content = theme === DARK ? '#141a22' : '#0A3D62';
    }

    /** Khởi tạo: bind nút, sync icon từ DOM hiện tại */
    function init() {
      _updateIcon(current());
      _updateMetaTheme(current());

      var btn = d.getElementById('vnkr-theme-toggle');
      if (btn) {
        btn.addEventListener('click', function () { toggle(); });
      }
    }

    return { init: init, toggle: toggle, current: current, apply: apply };
  })();


  /* ══════════════════════════════════════════════
     2. TOAST
     ══════════════════════════════════════════════ */

  var ToastModule = (function () {
    var _container = null;

    function _getContainer() {
      if (_container) return _container;
      _container = d.getElementById('vnkr-toast-container');
      if (!_container) {
        _container = d.createElement('div');
        _container.id = 'vnkr-toast-container';
        _container.setAttribute('aria-live', 'polite');
        _container.setAttribute('aria-atomic', 'false');
        _container.style.cssText = [
          'position:fixed',
          'bottom:20px',
          'right:20px',
          'z-index:9000',
          'display:flex',
          'flex-direction:column',
          'gap:10px',
          'pointer-events:none'
        ].join(';');
        d.body.appendChild(_container);
      }
      return _container;
    }

    var COLORS = {
      success: { bg: 'var(--success-light)', color: 'var(--success-text)', border: 'var(--success)' },
      error:   { bg: 'var(--error-light)',   color: 'var(--error-text)',   border: 'var(--error)' },
      warning: { bg: 'var(--warning-light)', color: 'var(--warning-text)', border: 'var(--warning)' },
      info:    { bg: 'var(--info-light)',     color: 'var(--info-text)',    border: 'var(--brand-alt)' }
    };

    var ICONS = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };

    /**
     * Hiển thị toast
     * @param {string} message  — nội dung
     * @param {string} type     — 'success' | 'error' | 'warning' | 'info'
     * @param {number} duration — ms, mặc định 3500
     */
    function show(message, type, duration) {
      type     = type     || 'info';
      duration = duration || 3500;
      var c    = COLORS[type] || COLORS.info;
      var container = _getContainer();

      var toast = d.createElement('div');
      toast.style.cssText = [
        'display:flex',
        'align-items:center',
        'gap:10px',
        'padding:12px 16px',
        'border-radius:10px',
        'border-left:4px solid ' + c.border,
        'background:' + c.bg,
        'color:' + c.color,
        'font-size:13.5px',
        'font-weight:500',
        'box-shadow:0 4px 16px rgba(0,0,0,.12)',
        'pointer-events:all',
        'opacity:0',
        'transform:translateY(8px)',
        'transition:opacity .2s ease,transform .2s ease',
        'max-width:320px',
        'word-break:break-word'
      ].join(';');
      toast.innerHTML = '<span style="font-weight:700;flex-shrink:0;">' + (ICONS[type] || '') + '</span>'
                      + '<span>' + message + '</span>';

      container.appendChild(toast);

      // Trigger reflow để transition hoạt động
      toast.getBoundingClientRect();
      toast.style.opacity   = '1';
      toast.style.transform = 'translateY(0)';

      // Auto-dismiss
      setTimeout(function () {
        toast.style.opacity   = '0';
        toast.style.transform = 'translateY(8px)';
        setTimeout(function () {
          if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 250);
      }, duration);
    }

    return { show: show };
  })();


  /* ══════════════════════════════════════════════
     3. MODAL
     ══════════════════════════════════════════════ */

  var ModalModule = (function () {
    var _active = null;

    /** Mở modal theo id */
    function open(id) {
      var modal = d.getElementById(id);
      if (!modal) return;
      _active = modal;
      modal.classList.add('vnkr-modal--open');
      d.body.style.overflow = 'hidden';
      // Focus vào phần tử đầu tiên có thể focus được
      var focusable = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      if (focusable.length) focusable[0].focus();
    }

    /** Đóng modal */
    function close(modal) {
      if (!modal) modal = _active;
      if (!modal) return;
      modal.classList.remove('vnkr-modal--open');
      d.body.style.overflow = '';
      _active = null;
    }

    function init() {
      // data-modal-open="id" triggers
      d.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-modal-open]');
        if (btn) { open(btn.dataset.modalOpen); return; }

        // data-modal-close
        var close_btn = e.target.closest('[data-modal-close]');
        if (close_btn) {
          var modal = close_btn.closest('.vnkr-modal');
          close(modal);
          return;
        }

        // Click backdrop (chính là .vnkr-modal, không phải .vnkr-modal-dialog)
        if (e.target.classList.contains('vnkr-modal')) {
          close(e.target);
        }
      });

      // ESC để đóng
      d.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && _active) close(_active);
      });
    }

    return { init: init, open: open, close: close };
  })();


  /* ══════════════════════════════════════════════
     4. ACCORDION
     ══════════════════════════════════════════════ */

  var AccordionModule = (function () {
    function init() {
      d.addEventListener('click', function (e) {
        var trigger = e.target.closest('.vnkr-accordion-trigger');
        if (!trigger) return;
        var item = trigger.closest('.vnkr-accordion-item');
        if (!item) return;

        var isOpen = item.classList.contains('open');

        // Đóng tất cả các item trong cùng accordion-group
        var group = item.closest('.vnkr-accordion');
        if (group) {
          group.querySelectorAll('.vnkr-accordion-item.open').forEach(function (el) {
            if (el !== item) el.classList.remove('open');
          });
        }

        item.classList.toggle('open', !isOpen);
        trigger.setAttribute('aria-expanded', String(!isOpen));
      });
    }

    return { init: init };
  })();


  /* ══════════════════════════════════════════════
     5. DROPDOWN
     ══════════════════════════════════════════════ */

  var DropdownModule = (function () {
    function _close(el) {
      el.removeAttribute('data-open');
      var trigger = el.querySelector('[data-dropdown-trigger]');
      if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }

    function _open(el) {
      el.setAttribute('data-open', '');
      var trigger = el.querySelector('[data-dropdown-trigger]');
      if (trigger) trigger.setAttribute('aria-expanded', 'true');
    }

    function init() {
      d.addEventListener('click', function (e) {
        var trigger = e.target.closest('[data-dropdown-trigger]');
        if (trigger) {
          var dropdown = trigger.closest('[data-dropdown]');
          if (!dropdown) return;
          var isOpen = dropdown.hasAttribute('data-open');
          // Đóng tất cả dropdowns
          d.querySelectorAll('[data-dropdown][data-open]').forEach(_close);
          if (!isOpen) _open(dropdown);
          e.stopPropagation();
          return;
        }

        // Click bên ngoài — đóng tất cả
        d.querySelectorAll('[data-dropdown][data-open]').forEach(_close);
      });

      // ESC để đóng
      d.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          d.querySelectorAll('[data-dropdown][data-open]').forEach(_close);
        }
      });
    }

    return { init: init };
  })();


  /* ══════════════════════════════════════════════
     6. SCROLL WATCHER — sticky nav glassmorphism
     ══════════════════════════════════════════════ */

  var ScrollWatcher = (function () {
    function init() {
      var nav = d.querySelector('.vnkr-nav');
      if (!nav) return;

      var ticking = false;
      w.addEventListener('scroll', function () {
        if (!ticking) {
          w.requestAnimationFrame(function () {
            nav.classList.toggle('vnkr-nav--scrolled', w.scrollY > 60);
            ticking = false;
          });
          ticking = true;
        }
      }, { passive: true });
    }

    return { init: init };
  })();


  /* ══════════════════════════════════════════════
     BOOT — khởi chạy tất cả modules khi DOM ready
     ══════════════════════════════════════════════ */

  function boot() {
    ThemeModule.init();
    ModalModule.init();
    AccordionModule.init();
    DropdownModule.init();
    ScrollWatcher.init();
  }

  if (d.readyState === 'loading') {
    d.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  /* Expose public API */
  w.VNKRUI = {
    theme:   ThemeModule,
    toast:   function (msg, type, dur) { return ToastModule.show(msg, type, dur); },
    modal:   ModalModule,
    version: '1.0.0'
  };

})(window, document);
