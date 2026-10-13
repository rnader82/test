import './bootstrap';

/* Novalyo portal controls. Existing page forms, routes and Bootstrap plugins remain authoritative. */
(function () {
  'use strict';
  function initPortal() {
    const body = document.body;
    if (!body.classList.contains('novalyo-portal') || body.dataset.portalInitialized) return;
    body.dataset.portalInitialized = 'true';
    const root = document.documentElement;
    const menu = document.getElementById('layout-menu');
    if (!menu) return;
    const labelsNode = document.getElementById('novalyo-labels');
    let labels = {};
    try { labels = JSON.parse(labelsNode ? labelsNode.textContent : '{}'); } catch (_) { /* No dynamic labels. */ }
    const storage = {
      get(key, fallback) { try { const value = JSON.parse(localStorage.getItem('novalyo.portal.' + key)); return value === null ? fallback : value; } catch (_) { return fallback; } },
      set(key, value) { try { localStorage.setItem('novalyo.portal.' + key, JSON.stringify(value)); } catch (_) { /* Controls work when browser storage is unavailable. */ } }
    };
    const drawerToggle = document.getElementById('mobile-menu-toggle');
    const desktopToggle = document.getElementById('desktop-menu-toggle');
    const closeToggle = document.getElementById('mobile-menu-close');
    const overlay = document.getElementById('mobile-menu-overlay');
    const desktop = window.matchMedia('(min-width: 1200px)');
    let collapsed = storage.get('collapsed', false) === true;
    let previousFocus = null;
    let inertBeforeDrawer = null;
    const page = document.querySelector('.layout-page');
    function syncNavigation() {
      menu.querySelectorAll('.menu-item').forEach(item => {
        const toggle = item.querySelector(':scope > .menu-toggle');
        const sub = item.querySelector(':scope > .menu-sub');
        if (!toggle || !sub) return;
        toggle.setAttribute('role', 'button');
        toggle.setAttribute('aria-expanded', String(item.classList.contains('open')));
        if (!sub.id) sub.id = 'portal-submenu-' + Array.from(menu.querySelectorAll('.menu-sub')).indexOf(sub);
        toggle.setAttribute('aria-controls', sub.id);
      });
    }
    menu.querySelectorAll('.menu-item.active').forEach(item => {
      let parent = item.parentElement;
      while (parent && parent !== menu) {
        if (parent.classList.contains('menu-item')) parent.classList.add('open');
        parent = parent.parentElement;
      }
      const link = item.querySelector(':scope > a:not(.menu-toggle)');
      if (link) link.setAttribute('aria-current', 'page');
    });
    function setCollapsed(value) {
      collapsed = value;
      root.classList.toggle('layout-menu-collapsed', value);
      body.classList.remove('layout-menu-collapsed');
      root.classList.remove('layout-menu-hover');
      if (desktopToggle) desktopToggle.setAttribute('aria-expanded', String(!value));
      if (closeToggle) closeToggle.setAttribute('aria-expanded', String(!value));
      storage.set('collapsed', value);
    }
    function setDrawer(open, restoreFocus = true) {
      root.classList.toggle('layout-menu-expanded', open);
      body.classList.remove('layout-menu-expanded');
      if (drawerToggle) drawerToggle.setAttribute('aria-expanded', String(open));
      if (open) {
        previousFocus = document.activeElement;
        if (page) { inertBeforeDrawer = page.inert; page.inert = true; }
        menu.querySelector('button, a[href]')?.focus();
      } else {
        if (page && inertBeforeDrawer !== null) { page.inert = inertBeforeDrawer; inertBeforeDrawer = null; }
        if (restoreFocus) previousFocus?.focus();
        previousFocus = null;
      }
    }
    document.querySelectorAll('.portal-menu-toggle').forEach(button => {
      button.addEventListener('click', event => {
        event.preventDefault(); event.stopImmediatePropagation();
        if (desktop.matches) setCollapsed(!collapsed);
        else setDrawer(button === closeToggle ? false : !root.classList.contains('layout-menu-expanded'));
      });
    });
    overlay?.addEventListener('click', () => setDrawer(false));
    menu.addEventListener('click', event => {
      const toggle = event.target.closest('.menu-toggle');
      if (toggle) {
        event.preventDefault(); event.stopImmediatePropagation();
        if (desktop.matches && collapsed) setCollapsed(false);
        const item = toggle.closest('.menu-item');
        item.classList.toggle('open');
        item.style.height = ''; item.style.overflow = '';
        const sub = item.querySelector(':scope > .menu-sub');
        if (sub) sub.style.removeProperty('display');
        syncNavigation();
      } else if (!desktop.matches && event.target.closest('a[href]')) setDrawer(false, false);
    }, true);
    menu.addEventListener('keydown', event => {
      if (event.key === ' ' && event.target.matches('.menu-toggle')) { event.preventDefault(); event.target.click(); }
    });
    document.addEventListener('keydown', event => {
      if (!desktop.matches && root.classList.contains('layout-menu-expanded')) {
        if (event.key === 'Escape') { setDrawer(false); return; }
        if (event.key === 'Tab') {
          const focusable = Array.from(menu.querySelectorAll('a[href],button,[tabindex="0"]')).filter(el => el.getClientRects().length);
          const first = focusable[0], last = focusable[focusable.length - 1];
          if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last?.focus(); }
          else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first?.focus(); }
        }
      }
    });
    function applyViewport() {
      setDrawer(false, false);
      if (desktop.matches) setCollapsed(collapsed);
      else root.classList.remove('layout-menu-collapsed');
    }
    desktop.addEventListener('change', applyViewport);
    applyViewport(); syncNavigation();

    // Index only real, currently permission-visible navigation links. Never import prototype data.
    const links = [];
    menu.querySelectorAll('.menu-inner a[href]:not(.menu-toggle)').forEach(anchor => {
      const rawHref = anchor.getAttribute('href');
      if (!rawHref || rawHref.startsWith('#') || rawHref.startsWith('javascript:')) return;
      const url = new URL(anchor.href, location.href);
      if (url.origin !== location.origin || !/^https?:$/.test(url.protocol)) return;
      const href = url.pathname + url.search;
      if (!links.some(link => link.href === href)) links.push({ href, title: anchor.textContent.trim().replace(/\s+/g, ' '), icon: anchor.querySelector('i')?.className || 'bi bi-link-45deg' });
    });
    function createLink(link) {
      const a = document.createElement('a'); a.href = link.href;
      const i = document.createElement('i'); i.className = link.icon; i.setAttribute('aria-hidden', 'true');
      const span = document.createElement('span'); span.textContent = link.title;
      a.append(i, span); return a;
    }
    const current = links.find(link => link.href === location.pathname + location.search) || links.find(link => new URL(link.href, location.origin).pathname === location.pathname);
    const favoriteButton = document.getElementById('portal-favorite-toggle');
    const savedPages = document.getElementById('portal-saved-pages');
    // Scope preferences by account to avoid sharing a previous user's page history on the same browser.
    const account = body.dataset.portalUser || 'guest';
    const savedKey = 'pages.' + account;
    let saved = storage.get(savedKey, {});
    if (!saved || typeof saved !== 'object' || Array.isArray(saved)) saved = {};
    const visibleHref = href => links.some(link => link.href === href);
    saved.favorites = Array.isArray(saved.favorites) ? saved.favorites.filter(visibleHref).slice(0,12) : [];
    saved.recent = Array.isArray(saved.recent) ? saved.recent.filter(visibleHref).slice(0,3) : [];
    if (current) saved.recent = [current.href, ...saved.recent.filter(href => href !== current.href)].slice(0,3);
    function renderSavedPages() {
      if (!savedPages) return;
      savedPages.replaceChildren();
      [['favorites', 'empty_favorites'], ['recent', 'empty_recent']].forEach(([key, empty]) => {
        const heading = document.createElement('h2'); heading.textContent = labels[key]; savedPages.append(heading);
        if (!saved[key].length) { const p = document.createElement('p'); p.textContent = labels[empty]; savedPages.append(p); }
        saved[key].forEach(href => { const link = links.find(entry => entry.href === href); if (link) savedPages.append(createLink(link)); });
      });
      savedPages.hidden = links.length === 0;
      if (favoriteButton && current) {
        favoriteButton.hidden = false;
        const favorite = saved.favorites.includes(current.href);
        favoriteButton.setAttribute('aria-pressed', String(favorite));
        favoriteButton.querySelector('i').className = favorite ? 'bi bi-star-fill' : 'bi bi-star';
      }
      storage.set(savedKey, saved);
    }
    favoriteButton?.addEventListener('click', () => {
      if (!current) return;
      const removed = saved.favorites.includes(current.href);
      saved.favorites = removed ? saved.favorites.filter(href => href !== current.href) : [...saved.favorites,current.href].slice(-12);
      renderSavedPages();
      const status = document.getElementById('portal-live-status');
      if (status) status.textContent = labels[removed ? 'favorite_removed' : 'favorite_added'];
    });
    renderSavedPages();
    const searchDialog = document.getElementById('portal-search-dialog');
    const searchInput = document.getElementById('portal-search-input');
    const searchResults = document.getElementById('portal-search-results');
    const normalize = value => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    function renderSearch() {
      if (!searchResults || !searchInput) return;
      const query = normalize(searchInput.value.trim());
      const found = links.filter(link => normalize(link.title).includes(query));
      searchResults.replaceChildren();
      found.forEach(link => searchResults.append(createLink(link)));
      if (!found.length) { const p = document.createElement('p'); p.textContent = labels.no_results; p.setAttribute('role', 'status'); searchResults.append(p); }
    }
    function showSearch() { if (searchDialog && !searchDialog.open) { renderSearch(); searchDialog.showModal(); searchInput?.focus(); } }
    document.getElementById('portal-search-toggle')?.addEventListener('click', showSearch);
    searchInput?.addEventListener('input', renderSearch);
    searchInput?.addEventListener('keydown', event => {
      if (event.key === 'ArrowDown') { event.preventDefault(); searchResults?.querySelector('a')?.focus(); }
      if (event.key === 'Enter') { event.preventDefault(); searchResults?.querySelector('a')?.click(); }
    });
    document.addEventListener('keydown', event => {
      if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k' && !root.classList.contains('layout-menu-expanded')) { event.preventDefault(); showSearch(); }
    });
    document.querySelectorAll('.portal-dialog').forEach(dialog => {
      dialog.addEventListener('keydown', event => {
        if (event.key === 'Escape') { event.preventDefault(); event.stopPropagation(); dialog.close(); }
      });
      dialog.querySelector('[data-portal-close]')?.addEventListener('click', () => dialog.close());
      dialog.addEventListener('click', event => {
        const rect = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
      });
    });

    const densitySelect = document.getElementById('portal-density');
    let density = storage.get('density','comfortable');
    if (!['compact','comfortable','large'].includes(density)) density = 'comfortable';
    body.dataset.novalyoDensity = density;
    if (densitySelect) {
      densitySelect.value = density;
      densitySelect.addEventListener('change', () => { body.dataset.novalyoDensity = densitySelect.value; storage.set('density',densitySelect.value); });
    }
    const content = document.querySelector('.content-area-main');
    const columnsButton = document.getElementById('portal-columns-toggle');
    const columnsDialog = document.getElementById('portal-columns-dialog');
    const options = document.getElementById('portal-column-options');
    let columnTable = null;
    const tableStates = new WeakMap();
    function headers(table) { return table.tHead && table.tHead.rows.length === 1 ? Array.from(table.tHead.rows[0].cells) : []; }
    function usable(table) { const cells = headers(table); return cells.length > 1 && cells.every(cell => cell.colSpan === 1 && cell.rowSpan === 1); }
    function dataTableApi(table) { const jq = window.jQuery; return jq?.fn?.dataTable?.isDataTable(table) ? jq(table).DataTable() : null; }
    function applyColumns(table, state) {
      const api = dataTableApi(table);
      if (api) {
        let changed = false;
        state.forEach((visible, index) => { if (api.column(index).visible() !== visible) { api.column(index).visible(visible, false); changed = true; } });
        if (changed) api.columns.adjust().draw(false);
      } else {
        Array.from(table.rows).forEach(row => {
          // Preserve empty-state and grouped rows that span columns.
          if (row.cells.length !== state.length || Array.from(row.cells).some(cell => cell.colSpan !== 1)) return;
          Array.from(row.cells).forEach((cell,index) => cell.classList.toggle('novalyo-column-hidden',!state[index]));
        });
      }
    }
    function stateFor(table) {
      if (tableStates.has(table)) return tableStates.get(table);
      const cells = headers(table);
      const key = 'columns.' + location.pathname + '.' + (table.id || Array.from(content.querySelectorAll('table')).indexOf(table));
      const names = cells.map(cell => cell.textContent.trim().replace(/\s+/g,' '));
      const savedColumns = storage.get(key, null);
      let state = names.map(() => true);
      if (savedColumns && JSON.stringify(savedColumns.names) === JSON.stringify(names) && Array.isArray(savedColumns.visible) && savedColumns.visible.length === names.length) state = savedColumns.visible.map(value => value !== false);
      // Keep checkbox and action columns usable, and at least one named data column visible.
      names.forEach((name,index) => { if (!name) state[index] = true; });
      if (!names.some((name,index) => name && state[index])) state = names.map(() => true);
      const entry = { key,names,state }; tableStates.set(table,entry); return entry;
    }
    function refreshTables() {
      if (!content) return;
      const tables = Array.from(content.querySelectorAll('table')).filter(table => !table.closest('.modal') && table.getClientRects().length);
      if (densitySelect) densitySelect.hidden = !tables.length;
      columnTable = tables.find(table => tableStates.has(table) || usable(table)) || null;
      if (columnsButton) columnsButton.hidden = !columnTable;
      if (columnTable) applyColumns(columnTable,stateFor(columnTable).state);
    }
    function saveColumns(entry) { storage.set(entry.key,{names:entry.names,visible:entry.state}); }
    function renderColumns() {
      if (!columnTable || !options) return;
      const entry = stateFor(columnTable); options.replaceChildren();
      entry.names.forEach((name,index) => {
        if (!name) return;
        const label = document.createElement('label');
        const checkbox = document.createElement('input'); checkbox.type = 'checkbox'; checkbox.className = 'form-check-input'; checkbox.checked = entry.state[index];
        const span = document.createElement('span'); span.textContent = name; label.append(checkbox,span); options.append(label);
        checkbox.addEventListener('change', () => {
          entry.state[index] = checkbox.checked;
          if (!entry.names.some((name,i) => name && entry.state[i])) { entry.state[index] = true; checkbox.checked = true; }
          applyColumns(columnTable,entry.state); saveColumns(entry);
        });
      });
    }
    columnsButton?.addEventListener('click', () => { refreshTables(); renderColumns(); if (columnTable && columnsDialog && !columnsDialog.open) columnsDialog.showModal(); });
    document.getElementById('portal-columns-reset')?.addEventListener('click', () => {
      if (!columnTable) return;
      const entry = stateFor(columnTable); entry.state.fill(true); applyColumns(columnTable,entry.state); saveColumns(entry); renderColumns();
    });
    let refreshPending = false;
    function scheduleRefresh() { if (refreshPending) return; refreshPending = true; requestAnimationFrame(() => { refreshPending = false; refreshTables(); }); }
    if (content) new MutationObserver(scheduleRefresh).observe(content,{childList:true,subtree:true});
    if (window.jQuery) window.jQuery(document).on('draw.dt shown.bs.tab shown.bs.collapse', scheduleRefresh);
    window.addEventListener('load',scheduleRefresh);
    refreshTables();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded',initPortal);
  else initPortal();
})();
