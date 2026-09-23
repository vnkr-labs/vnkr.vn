/**
 * VNKR.VN — Core Application JS
 * Framework tĩnh — Pillar Brands / LOC IP Core
 * Khởi đầu: 2025
 */

'use strict';

/* ── NAV MAP ─────────────────────────────────────────────────── */
const NAV_PAGES = [
    { href: '/index.html',          icon: 'layers',     label: 'UI Kit'     },
    { href: '/brand-positioning-plan.html', icon: 'target',   label: 'Brand Plan' },
    { href: '/roadmap-loc-ip.html', icon: 'git-branch', label: 'Roadmap'    },
    { href: '/metamask-bridge-12-k-ch-b-n-l-tr-nh-h-p-th-c-ho-l-i-ch.html',
      icon: 'bridge',  label: 'Bridge 12' },
    { href: '/metamask-extension-h-ng-d-n-dev-lu-ng-t-i-ch-nh-t-ng-th.html',
      icon: 'code-2',  label: 'Dev Guide'  },
    { href: '/docs/',               icon: 'book-open',  label: 'Docs'       },
];

/* ── DARK MODE ───────────────────────────────────────────────── */
const ThemeManager = (() => {
    const HTML     = document.documentElement;
    const STORAGE  = 'vnkr-theme';

    function current() {
        return localStorage.getItem(STORAGE) || 'light';
    }

    function apply(theme) {
        if (theme === 'dark') {
            HTML.classList.add('dark');
        } else {
            HTML.classList.remove('dark');
        }
        localStorage.setItem(STORAGE, theme);
        _updateToggles();
        // Re-render Lucide icons if available
        if (window.lucide) lucide.createIcons();
    }

    function toggle() {
        apply(current() === 'dark' ? 'light' : 'dark');
    }

    function _updateToggles() {
        const isDark = HTML.classList.contains('dark');
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            const icon = btn.querySelector('[data-lucide]');
            const span = btn.querySelector('span[data-theme-text]');
            if (icon)  icon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
            if (span)  span.textContent = isDark ? 'Sáng' : 'Tối';
        });
        if (window.lucide) lucide.createIcons();
    }

    function init() {
        apply(current());
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            btn.addEventListener('click', toggle);
        });
    }

    return { init, toggle, current, apply };
})();

/* ── HEADER BUILDER ─────────────────────────────────────────── */
const HeaderBuilder = (() => {
    function build(opts = {}) {
        const { icon = 'layers', title = 'VNKR.VN', activePage = '' } = opts;

        const currentPath = window.location.pathname.replace(/^\//, '');

        const pillsHTML = NAV_PAGES
            .filter(p => {
                const pPath = p.href.replace(/^\//, '').replace(/\/$/, '');
                return pPath !== currentPath.replace(/\/$/, '');
            })
            .map(p => `
                <a href="${p.href}" class="nav-pill">
                    <i data-lucide="${p.icon}"></i>
                    <span>${p.label}</span>
                </a>`)
            .join('');

        return `
        <header class="site-header">
          <div class="container">
            <a href="/index.html" class="header-brand">
              <i data-lucide="${icon}" style="width:20px;height:20px;color:var(--color-accent-1)"></i>
              <span>${title}</span>
            </a>
            <nav class="header-nav">
              ${pillsHTML}
              <button class="theme-toggle" data-theme-toggle aria-label="Toggle theme">
                <i data-lucide="moon" data-lucide></i>
                <span data-theme-text>Tối</span>
              </button>
            </nav>
          </div>
        </header>`;
    }

    function inject(opts) {
        const placeholder = document.getElementById('site-header');
        if (!placeholder) return;
        placeholder.outerHTML = build(opts);
    }

    return { build, inject };
})();

/* ── TOAST ───────────────────────────────────────────────────── */
const Toast = (() => {
    let _el = null;
    let _timer = null;

    function _getEl() {
        if (!_el) {
            _el = document.getElementById('toast');
            if (!_el) {
                _el = document.createElement('div');
                _el.id = 'toast';
                document.body.appendChild(_el);
            }
        }
        return _el;
    }

    function show(msg, duration = 3000) {
        const el = _getEl();
        el.textContent = msg;
        el.classList.add('show');
        clearTimeout(_timer);
        _timer = setTimeout(() => el.classList.remove('show'), duration);
    }

    return { show };
})();

/* ── COPY TO CLIPBOARD ───────────────────────────────────────── */
function copyText(text, label = '') {
    const area = document.createElement('textarea');
    area.value = text;
    area.style.cssText = 'position:fixed;top:-9999px;left:-9999px';
    document.body.appendChild(area);
    area.select();
    try {
        document.execCommand('copy');
        Toast.show(label ? `Đã sao chép: ${label}` : 'Đã sao chép!');
    } catch (e) {
        Toast.show('Sao chép thất bại');
    }
    document.body.removeChild(area);
}

/* ── ACTIVE NAV HIGHLIGHT ───────────────────────────────────── */
function highlightActiveNav() {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-link[href]').forEach(link => {
        const href = link.getAttribute('href');
        const isActive = path.endsWith(href) || path.includes(href.replace(/^\//, ''));
        link.classList.toggle('active', isActive && href !== '/');
    });
}

/* ── SCROLL SPY (sidebar) ───────────────────────────────────── */
function initScrollSpy(containerSel = '.page-sidebar nav', sectionSel = 'section[id]') {
    const nav = document.querySelector(containerSel);
    if (!nav) return;

    const sections = Array.from(document.querySelectorAll(sectionSel));
    if (!sections.length) return;

    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            nav.querySelectorAll('.nav-link').forEach(link => {
                link.classList.toggle(
                    'active',
                    link.getAttribute('href') === `#${e.target.id}`
                );
            });
        });
    }, { rootMargin: '-20% 0px -70% 0px' });

    sections.forEach(s => obs.observe(s));
}

/* ── INIT ────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
    highlightActiveNav();
    initScrollSpy();
    if (window.lucide) lucide.createIcons();
});

/* ── EXPORTS (for inline scripts) ───────────────────────────── */
window.VNKR = {
    ThemeManager,
    HeaderBuilder,
    Toast,
    copyText,
    NAV_PAGES,
};
