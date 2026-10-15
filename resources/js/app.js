import './bootstrap';

/* Novalyo portal controls. Existing page forms, routes and Bootstrap plugins remain authoritative. */
(function () {
  'use strict';
  function initPortal() {
    const body = document.body;
    if (!body.classList.contains('novalyo-portal') || body.dataset.portalInitialized) return;
    body.dataset.portalInitialized = 'true';
    const root = document.documentElement;
    document.querySelectorAll('.form-password-toggle .input-group-text').forEach(toggle => {
      toggle.setAttribute('role','button'); toggle.setAttribute('tabindex','0');
      const activate = () => { const input=toggle.closest('.form-password-toggle')?.querySelector('input[type="password"],input[type="text"]'); if(!input)return; input.type=input.type==='password'?'text':'password'; toggle.setAttribute('aria-pressed',String(input.type==='text')); const icon=toggle.querySelector('i'); if(icon)icon.className=input.type==='text'?'ri-eye-line':'ri-eye-off-line'; };
      toggle.addEventListener('click',activate); toggle.addEventListener('keydown',event=>{if(event.key==='Enter'||event.key===' '){event.preventDefault();activate();}});
    });
    if(window.bootstrap) {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>bootstrap.Tooltip.getOrCreateInstance(el));
      document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el=>bootstrap.Popover.getOrCreateInstance(el));
    }
    const menu = document.getElementById('layout-menu');
    if (!menu) return;
    const labelsNode = document.getElementById('novalyo-labels');
    let labels = {};
    try { labels = JSON.parse(labelsNode ? labelsNode.textContent : '{}'); } catch (_) { /* No dynamic labels. */ }
    // Regroup only links already rendered by Laravel's permission checks.
    const navDefinitions = [
      { key:'dashboard', label:'dashboard', icon:'house', routes:['dashboard','todos.'] },
      { key:'clients', section:'relations', label:'nav_clients', icon:'users', routes:['clients.','contacts.','contracts.'] },
      { key:'articles', section:'relations', label:'nav_articles', icon:'tags', routes:['equipments.','combos.','warehouses.','equipment_categories.','equipment_types.','brands.'] },
      { key:'commerce', section:'relations', label:'nav_commerce', icon:'arrow-trend-up', routes:['commerce.','pricelists.'] },
      { key:'quotes', section:'relations', label:'nav_quotes', icon:'file-lines', routes:['issues.devis','quote-templates.','quote-reserves.'] },
      { key:'adv', section:'operations', label:'nav_adv', icon:'boxes-stacked', routes:['orders.','deliveries.','projects.','construction-reports.'] },
      { key:'purchasing', section:'operations', label:'nav_purchasing', icon:'cart-flatbed', routes:['suppliers.','supplier-contacts.','supplier-orders.','purchases.'] },
      { key:'technical', section:'operations', label:'nav_technical', icon:'screwdriver-wrench', routes:['reports.','issues.index','tech_intervs.','planning.','repairs.','visite-prev.','knowledge-base.'] },
      { key:'billing', section:'operations', label:'nav_billing', icon:'file-invoice-dollar', routes:['facturation.'] },
      { key:'collection', section:'operations', label:'nav_collection', icon:'money-bill-transfer', routes:['recouvrement.'] },
      { key:'admin', section:'configuration', label:'nav_admin', icon:'gear', routes:[] }
    ];
    const list = menu.querySelector('.menu-inner');
    const originalLinks = Array.from(list?.querySelectorAll('a[data-portal-route]:not(.menu-toggle)') || []);
    const used = new Set();
    const items = originalLinks.map(anchor => ({ anchor, route:anchor.dataset.portalRoute, active:anchor.closest('.menu-item')?.classList.contains('active') }));
    const groups = [];
    const navLabels = { 'clients.index':'nav_companies', 'equipments.index':'nav_catalog', 'reports.index':'nav_technical_overview', 'issues.index':'tickets', 'knowledge-base.index':'nav_knowledge', 'tech_intervs.index':'nav_interventions', 'planning.week':'nav_planning' };
    const navIcons = { 'clients.index':'building', 'contacts.index':'address-book', 'equipments.index':'box-open', 'reports.index':'gauge-high', 'issues.index':'headset', 'tech_intervs.index':'user-gear', 'planning.week':'calendar-days', 'repairs.index':'wrench', 'visite-prev.index':'shield-heart', 'knowledge-base.index':'book-open' };
    navDefinitions.forEach(definition => {
      const selected = items.filter(item => !used.has(item) && (definition.key==='admin' || definition.routes.some(prefix => item.route === prefix || item.route.startsWith(prefix))));
      if(definition.key==='technical') {
        const order=['reports.index','issues.index','planning.week','tech_intervs.index','repairs.index','visite-prev.index','knowledge-base.index'];
        selected.sort((a,b)=>(order.indexOf(a.route)<0?100:order.indexOf(a.route))-(order.indexOf(b.route)<0?100:order.indexOf(b.route)));
      }
      selected.forEach(item => used.add(item));
      if (!selected.length) return;
      const group = document.createElement('li'); group.className = 'menu-item nav-group'; group.dataset.navGroup=definition.key;
      const toggle = document.createElement('button'); toggle.type='button'; toggle.className='menu-toggle nav-item'; toggle.title=labels[definition.label];
      const icon=document.createElement('i'); icon.className='fa-solid fa-'+definition.icon; icon.setAttribute('aria-hidden','true');
      const name=document.createElement('span'); name.className='nav-label'; name.textContent=labels[definition.label];
      const chevron=document.createElement('i'); chevron.className='fa-solid fa-chevron-right nav-chevron'; chevron.setAttribute('aria-hidden','true');
      toggle.append(icon,name,chevron); group.append(toggle);
      const sub=document.createElement('ul'); sub.className='menu-sub nav-submenu'; sub.hidden=true;
      selected.forEach(item => {
        const raw=item.anchor.getAttribute('href');
        if (!raw || raw.startsWith('javascript:') || raw==='#') return;
        const li=document.createElement('li'); li.className='menu-item'; if(item.active) { li.classList.add('active'); group.classList.add('active','open'); }
        const a=item.anchor; a.className='menu-link nav-subitem'; if(item.active)a.classList.add('active');
        const caption=a.querySelector('div'); if(caption) { const span=document.createElement('span'); span.textContent=labels[navLabels[item.route]] || caption.textContent; caption.replaceWith(span); }
        if (navIcons[item.route] && a.querySelector('i')) a.querySelector('i').className='fa-solid fa-'+navIcons[item.route];
        a.querySelectorAll('i').forEach(i=>{i.style.removeProperty('margin-right');i.setAttribute('aria-hidden','true');});
        li.append(a);sub.append(li);
      });
      if (!sub.children.length) { toggle.disabled=true; toggle.setAttribute('aria-disabled','true'); chevron.remove(); }
      group.append(sub);groups.push({definition,group});
    });
    if(list) {
      list.replaceChildren(); let section='';
      groups.forEach(({definition,group})=>{
        if(definition.section && definition.section!==section) { section=definition.section; const heading=document.createElement('li');heading.className='nav-section-label';heading.dataset.navSection=section;heading.textContent=labels[section];list.append(heading); }
        list.append(group);
      });
    }

    const storage = {
      get(key, fallback) { try { const value = JSON.parse(localStorage.getItem('novalyo.portal.' + key)); return value === null ? fallback : value; } catch (_) { return fallback; } },
      set(key, value) { try { localStorage.setItem('novalyo.portal.' + key, JSON.stringify(value)); } catch (_) { /* Controls work when browser storage is unavailable. */ } }
    };
    const drawerToggle = document.getElementById('mobile-menu-toggle');
    const desktopToggle = document.getElementById('desktop-menu-toggle');
    const closeToggle = document.getElementById('mobile-menu-close');
    const overlay = document.getElementById('mobile-menu-overlay');
    const desktop = window.matchMedia('(min-width: 801px)');
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
        toggle.classList.toggle('expanded',item.classList.contains('open')); toggle.classList.toggle('current',item.classList.contains('active')); sub.hidden=!item.classList.contains('open');
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
      document.querySelector('.app')?.classList.toggle('sidebar-collapsed',value);
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
      else { root.classList.remove('layout-menu-collapsed'); document.querySelector('.app')?.classList.remove('sidebar-collapsed'); }
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
      const a = document.createElement('a'); a.href = link.href; a.className='nav-quick-link';
      const i = document.createElement('i'); i.className = link.icon; i.setAttribute('aria-hidden', 'true');
      const span = document.createElement('span'); span.textContent = link.title;
      a.append(i, span); return a;
    }
    const activeGroup=menu.querySelector('[data-nav-group].active');
    const crumb=document.getElementById('portal-breadcrumb-group');
    if(activeGroup&&crumb) { crumb.textContent=activeGroup.querySelector('.nav-label')?.textContent||'';crumb.hidden=false;document.getElementById('portal-breadcrumb-separator').hidden=false; }
    const workspace=document.getElementById('portal-workspace');
    const workspaces={manager:navDefinitions.map(d=>d.key),technician:['dashboard','technical'],commerce:['dashboard','clients','articles','commerce','quotes'],adv:['dashboard','clients','articles','adv'],purchasing:['dashboard','articles','purchasing'],billing:['dashboard','clients','billing'],collection:['dashboard','clients','collection']};
    function applyWorkspace(value) { const allowed=workspaces[value]||workspaces.manager;groups.forEach(({definition,group})=>group.hidden=!allowed.includes(definition.key)&&group!==activeGroup);menu.querySelectorAll('[data-nav-section]').forEach(heading=>{const section=heading.dataset.navSection;heading.hidden=!groups.some(({definition,group})=>definition.section===section&&!group.hidden);});storage.set('workspace.'+(body.dataset.portalUser||'guest'),value); }
    if(workspace) { const savedWorkspace=storage.get('workspace.'+(body.dataset.portalUser||'guest'),'manager');workspace.value=workspaces[savedWorkspace]?savedWorkspace:'manager';applyWorkspace(workspace.value);workspace.addEventListener('change',()=>applyWorkspace(workspace.value)); }
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
        const heading=document.createElement('div');heading.className='nav-quick-title';
        const caption=document.createElement('span');const icon=document.createElement('i');icon.className=key==='favorites'?'fa-solid fa-thumbtack':'fa-solid fa-clock-rotate-left';icon.setAttribute('aria-hidden','true');caption.append(icon,document.createTextNode(' '+labels[key]));heading.append(caption);
        if(key==='favorites') { const count=document.createElement('span');count.textContent=saved[key].length;heading.append(count); }
        else heading.style.marginTop='8px'; savedPages.append(heading);
        const list=document.createElement('div');list.className='nav-quick-list';savedPages.append(list);
        if (!saved[key].length) { const p = document.createElement('p');p.className='nav-quick-empty'; p.textContent = labels[empty]; list.append(p); }
        saved[key].forEach(href => { const link = links.find(entry => entry.href === href); if (link) list.append(createLink(link)); });
      });
      const recentItems=document.getElementById('portal-recent-items');
      if(recentItems) { recentItems.replaceChildren();saved.recent.forEach(href=>{const link=links.find(entry=>entry.href===href);if(link){const li=document.createElement('li');const a=createLink(link);a.className='dropdown-item d-flex align-items-center gap-2';li.append(a);recentItems.append(li);}});if(!saved.recent.length){const li=document.createElement('li');li.className='dropdown-item-text';li.textContent=labels.empty_recent;recentItems.append(li);} }
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
    body.classList.toggle('density-compact',density==='compact'); body.classList.toggle('density-large',density==='large');
    if (densitySelect) {
      densitySelect.value = density;
      densitySelect.addEventListener('change', () => { body.dataset.novalyoDensity = densitySelect.value; body.classList.toggle('density-compact',densitySelect.value==='compact'); body.classList.toggle('density-large',densitySelect.value==='large'); storage.set('density',densitySelect.value); });
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
      if(body.classList.contains('portal-module-page') && !['list','report'].includes(body.dataset.portalKind)){if(densitySelect)densitySelect.hidden=true;if(columnsButton)columnsButton.hidden=true;columnTable=null;return;}
      const tables = Array.from(content.querySelectorAll('table')).filter(table => !table.closest('.modal') && (table.getClientRects().length || body.dataset.portalModuleView==='kanban' && table.dataset.modulePrimary==='true'));
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
    // The HTML's shared module workspace, using the existing rendered records and forms.
    const moduleLabels = labels.modules || {};
    const moduleWorkspace = document.querySelector('[data-module-workspace]');
    if (moduleWorkspace) {
      // Full-screen repair records must not trap their dialogs below the backdrop.
      if(body.dataset.portalModule==='repairs' && body.dataset.portalPage==='show') {
        moduleWorkspace.querySelectorAll('.modal').forEach(modal=>{if(!modal.closest('form'))document.body.append(modal);});
      }
      function initTableDropdowns(){
        if(!window.bootstrap?.Dropdown)return;
        moduleWorkspace.querySelectorAll('table [data-bs-toggle="dropdown"]').forEach(toggle=>bootstrap.Dropdown.getOrCreateInstance(toggle,{popperConfig:config=>({...config,strategy:'fixed',modifiers:[...(config.modifiers||[]),{name:'preventOverflow',options:{boundary:document.body}}]})}));
      }
      initTableDropdowns();
      moduleWorkspace.querySelectorAll('table').forEach(table => {
        if (table.closest('.modal') || table.classList.contains('resource-grid')) return;
        if (!table.closest('.table-responsive,.module-table-wrap,.dataTables_wrapper,.dt-container')) {
          const wrap = document.createElement('div'); wrap.className = 'module-table-wrap'; table.before(wrap); wrap.append(table);
        }
      });
      const moduleToolbar = moduleWorkspace.querySelector('[data-module-toolbar]');
      const moduleTable = moduleWorkspace.querySelector('.portal-module-list-body table:not(.modal table)');
      if (moduleToolbar) {
        moduleWorkspace.querySelectorAll('.portal-original-toolbar').forEach(toolbar => { if(toolbar.closest('form'))return; toolbar.classList.add('portal-module-existing-tools'); moduleToolbar.append(toolbar); });
        const searchHost = moduleToolbar.querySelector('.portal-module-search');
        if (searchHost && moduleTable && usable(moduleTable) && !searchHost.querySelector('input,select')) {
          const search = document.createElement('input'); search.type='search'; search.className='form-control'; search.placeholder=moduleLabels.search_page || labels.search_placeholder; search.setAttribute('aria-label',moduleLabels.search_page || labels.search_placeholder); search.dataset.moduleSearch=''; searchHost.append(search);
        }
        [densitySelect,columnsButton].forEach(control => { if (control) moduleToolbar.append(control); });
      }
      if (moduleToolbar && moduleTable && usable(moduleTable)) {
        moduleTable.dataset.modulePrimary='true';
        const columnNames=headers(moduleTable).map(cell=>cell.textContent.trim().replace(/\s+/g,' '));
        const statusIndex=columnNames.findIndex(name=>/^(statut|status|état|etat|stage)(\b|$)/i.test(name) || body.dataset.portalModule==='clients' && /^contrat/i.test(name));
        const cellsFor=row=>{const api=dataTableApi(moduleTable);return api ? columnNames.map((_,index)=>api.cell(row,index).node()) : Array.from(row.cells);};
        const columnVisible=index=>{const api=dataTableApi(moduleTable);return api ? api.column(index).visible() : !moduleTable.tHead.rows[0].cells[index]?.classList.contains('novalyo-column-hidden');};
        const rows=()=>{const api=dataTableApi(moduleTable);return api ? Array.from(api.rows({search:'applied',page:'current'}).nodes()) : Array.from(moduleTable.tBodies).flatMap(tbody=>Array.from(tbody.rows)).filter(row=>row.cells.length===columnNames.length && Array.from(row.cells).every(cell=>cell.colSpan===1));};
        const text=cell=>cell?.textContent.trim().replace(/\s+/g,' ') || '';
        rows().forEach(row=>{if(row.hidden)row.dataset.moduleInitiallyHidden='true';});
        const statuses=statusIndex<0?[]:Array.from(new Set(rows().map(row=>text(cellsFor(row)[statusIndex])).filter(Boolean))).sort((a,b)=>a.localeCompare(b));
        let statusFilter=null;
        if (statuses.length) {
          statusFilter=document.createElement('select'); statusFilter.className='module-filter'; statusFilter.setAttribute('aria-label',moduleLabels.status_page); statusFilter.title=moduleLabels.page_scope;
          const all=document.createElement('option');all.value='';all.textContent=moduleLabels.all_statuses;statusFilter.append(all);
          statuses.forEach(status=>{const option=document.createElement('option');option.value=status;option.textContent=status;statusFilter.append(option);});moduleToolbar.append(statusFilter);
        }
        const empty=document.createElement('p');empty.className='module-empty portal-module-empty';empty.textContent=labels.no_results;empty.hidden=true;moduleTable.closest('.module-table-wrap,.table-responsive')?.after(empty);
        const kanban=moduleWorkspace.querySelector('[data-module-kanban]');
        const canKanban=['clients','contacts','prospects','projects','orders','repairs','tech_intervs','todos'].includes(body.dataset.portalModule);
        const viewKey='module-view.'+account+'.'+location.pathname;
        let view=canKanban&&storage.get(viewKey,'list')==='kanban'?'kanban':'list';
        let switcher=null;
        if(canKanban && kanban) {
          switcher=document.createElement('div');switcher.className='module-view-switch';switcher.setAttribute('role','group');switcher.setAttribute('aria-label',moduleLabels.view);
          ['list','kanban'].forEach(mode=>{const button=document.createElement('button');button.type='button';button.className='module-view-button';button.dataset.moduleView=mode;button.textContent=moduleLabels[mode];switcher.append(button);button.addEventListener('click',()=>{view=mode;storage.set(viewKey,mode);renderModuleView();});});moduleToolbar.prepend(switcher);
        }
        function visibleRows(){return rows().filter(row=>!row.hidden&&row.style.display!=='none');}
        function renderModuleView(){
          body.dataset.portalModuleView=view;
          switcher?.querySelectorAll('button').forEach(button=>{const active=button.dataset.moduleView===view;button.classList.toggle('active',active);button.setAttribute('aria-pressed',String(active));});
          const wrap=moduleTable.closest('.module-table-wrap,.table-responsive');
          if(wrap)wrap.hidden=view==='kanban';
          if(kanban) { kanban.hidden=view!=='kanban'; if(view==='kanban') {
            kanban.replaceChildren();const buckets=new Map();
            visibleRows().forEach(row=>{const state=statusIndex<0?moduleLabels.records:text(cellsFor(row)[statusIndex]);if(!buckets.has(state))buckets.set(state,[]);buckets.get(state).push(row);});
            buckets.forEach((records,state)=>{
              const column=document.createElement('section');column.className='module-kanban-column';
              const heading=document.createElement('h3');heading.className='module-kanban-head';heading.textContent=state;const count=document.createElement('span');count.className='module-kanban-count';count.textContent=String(records.length);heading.append(count);column.append(heading);
              const cards=document.createElement('div');cards.className='module-kanban-cards';column.append(cards);
              records.forEach(row=>{
                const card=document.createElement('article');card.className='module-kanban-card';
                columnNames.forEach((name,index)=>{if(!name||/^(actions?|sélection)$/i.test(name)||!columnVisible(index))return;const field=document.createElement('div');field.className='module-kanban-field';const label=document.createElement('span');label.textContent=name;const value=document.createElement('b');value.textContent=text(cellsFor(row)[index]);field.append(label,value);card.append(field);});
                const source=Array.from(row.querySelectorAll('a[href]')).find(link=>{try{const url=new URL(link.href,location.href);return url.origin===location.origin && /^https?:$/.test(url.protocol);}catch(_){return false;}});
                if(source){const link=document.createElement('a');link.className='btn module-record-link';link.href=source.href;link.textContent=moduleLabels.open_record;card.append(link);}cards.append(card);
              });kanban.append(column);
            });
          }}
          scheduleRefresh();
        }
        function filterModuleRows(){
          const query=moduleToolbar.querySelector('[data-module-search]')?.value.trim().toLocaleLowerCase() || '';
          const status=statusFilter?.value || '';const api=dataTableApi(moduleTable);
          if(api){api.search(query);if(statusIndex>=0)api.column(statusIndex).search(status ? '^'+status.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+'$' : '',true,false);api.draw();}
          else rows().forEach(row=>{row.hidden=row.dataset.moduleInitiallyHidden==='true'||(query&&!text(row).toLocaleLowerCase().includes(query))||(status&&text(cellsFor(row)[statusIndex])!==status);});
          empty.hidden=visibleRows().length>0;renderModuleView();
        }
        moduleToolbar.querySelector('[data-module-search]')?.addEventListener('input',filterModuleRows);statusFilter?.addEventListener('change',filterModuleRows);
        if(window.jQuery)jQuery(moduleTable).on('draw.dt',()=>{initTableDropdowns();empty.hidden=visibleRows().length>0;renderModuleView();});
        options?.addEventListener('change',()=>{if(view==='kanban')renderModuleView();});
        const exportButton=document.createElement('button');exportButton.type='button';exportButton.className='btn portal-module-export';exportButton.textContent=moduleLabels.export_page;exportButton.title=moduleLabels.page_scope;
        document.querySelector('.top-actions')?.prepend(exportButton);
        exportButton.addEventListener('click',()=>{
          const indexes=columnNames.map((name,index)=>name&&!/^(actions?|sélection)$/i.test(name)&&columnVisible(index)?index:-1).filter(index=>index>=0);
          const quote=value=>{let cell=String(value??'').trim();if(/^[=+@\-]/.test(cell))cell="'"+cell;return '"'+cell.replace(/"/g,'""')+'"';};
          const csv=[indexes.map(index=>quote(columnNames[index])).join(','),...visibleRows().map(row=>indexes.map(index=>quote(text(cellsFor(row)[index]))).join(','))].join('\r\n');
          const url=URL.createObjectURL(new Blob(['\ufeff'+csv],{type:'text/csv;charset=utf-8;'}));const link=document.createElement('a');link.href=url;link.download='novalyo-'+body.dataset.portalModule+'.csv';link.click();setTimeout(()=>URL.revokeObjectURL(url),1000);
        });
        renderModuleView();
      }
      // Company categories are built exclusively from permission-checked Laravel tabs.
      const categoryNav=moduleWorkspace.querySelector('[data-company-categories]');
      const companyTabs=moduleWorkspace.querySelector('#clientTab');
      if(categoryNav && companyTabs && body.dataset.portalPage==='show'){
        const definitions=[['overview',['general-tab'],'company_overview','chart-pie'],['relation',['contacts-tab','contracts-tab'],'company_relation','users'],['commercial',['orders-tab'],'company_commercial','file-lines'],['support',['issues-tab','interventions-tab','repairs-tab'],'company_support','headset'],['assets',['equipments-tab','sites-tab'],'company_assets','boxes-stacked'],['resources',['documents-tab','users-tab','portal-tab'],'company_resources','folder-open']];
        const tabs=Array.from(companyTabs.querySelectorAll('[data-bs-toggle="tab"]'));const groups=definitions.map(([key,ids,label,icon])=>({key,ids,label,icon,tabs:tabs.filter(tab=>ids.includes(tab.id))})).filter(group=>group.tabs.length);
        const buttons=new Map();
        function selectCategory(group,activate){
          groups.forEach(item=>{const active=item===group;buttons.get(item.key).classList.toggle('active',active);buttons.get(item.key).setAttribute('aria-pressed',String(active));});
          tabs.forEach(tab=>{tab.closest('li').hidden=!group.tabs.includes(tab);});companyTabs.hidden=group.key==='overview';
          if(activate)bootstrap.Tab.getOrCreateInstance(group.tabs[0]).show();
        }
        groups.forEach(group=>{const button=document.createElement('button');button.type='button';button.className='company-detail-category-button';const icon=document.createElement('i');icon.className='fa-solid fa-'+group.icon;icon.setAttribute('aria-hidden','true');button.append(icon,document.createTextNode(moduleLabels[group.label]));button.addEventListener('click',()=>selectCategory(group,true));categoryNav.append(button);buttons.set(group.key,button);group.tabs.forEach(tab=>{tab.classList.add('company-detail-nav-button');tab.addEventListener('shown.bs.tab',()=>selectCategory(group,false));});});
        const active=groups.find(group=>group.tabs.some(tab=>tab.classList.contains('active')))||groups[0];if(active)selectCategory(active,false);
      }
    }

    const ticketsPanel=document.querySelector('[data-portal-tickets]');
    if(ticketsPanel) {
      const ticketTable=document.getElementById('portal-ticket-table');
      const ticketRows=Array.from(ticketsPanel.querySelectorAll('tr[data-ticket-id]'));
      const ticketFavoritesKey='ticket-favorites.'+(body.dataset.portalUser||'guest');
      let ticketFavorites=storage.get(ticketFavoritesKey,[]);
      if(!Array.isArray(ticketFavorites))ticketFavorites=[];
      ticketFavorites=ticketFavorites.filter(id=>typeof id==='string');
      let ticketFilter=null;
      const tabs=Array.from(ticketsPanel.querySelectorAll('[data-ticket-tab]'));
      const originalTabState=tabs.map(tab=>tab.classList.contains('active'));
      const pager=ticketsPanel.querySelector('#pagerLabel');const originalPager=pager?.textContent;
      const selectAll=ticketsPanel.querySelector('#checkAll');
      const empty=document.getElementById('portal-ticket-empty');
      function selectedRows(){return ticketRows.filter(row=>row.querySelector('input[type="checkbox"]')?.checked);}
      function syncSelection(){
        const selected=selectedRows();const bar=document.getElementById('bulkActionBar');if(bar){bar.hidden=!selected.length;bar.classList.toggle('show',selected.length>0);}
        const count=document.getElementById('bulkSelectionCount');if(count)count.textContent=selected.length+' '+labels.selected_tickets;
        const visible=ticketRows.filter(row=>!row.hidden);const visibleSelected=visible.filter(row=>row.querySelector('input[type="checkbox"]')?.checked);
        if(selectAll){selectAll.checked=visible.length>0&&visibleSelected.length===visible.length;selectAll.indeterminate=visibleSelected.length>0&&visibleSelected.length<visible.length;}
      }
      function applyTicketFilter(){
        ticketRows.forEach(row=>{
          const matches=!ticketFilter||(ticketFilter==='favorites'?ticketFavorites.includes(row.dataset.ticketId):row.dataset['ticket'+ticketFilter.charAt(0).toUpperCase()+ticketFilter.slice(1)]==='1');
          row.hidden=!matches;
        });
        tabs.forEach((tab,index)=>tab.classList.toggle('active',ticketFilter?tab.dataset.ticketTab===(['overdue','today','callback'].includes(ticketFilter)?'callbacks':ticketFilter):originalTabState[index]));
        ticketsPanel.querySelectorAll('[data-overview-filter]').forEach(button=>button.classList.toggle('active',button.dataset.overviewFilter===ticketFilter));
        const visible=ticketRows.filter(row=>!row.hidden);if(pager)pager.textContent=ticketFilter?visible.length+' / '+ticketRows.length:originalPager;
        if(empty)empty.hidden=!ticketRows.length||visible.length>0;
        syncSelection();
      }
      function syncTicketFavorites(){
        ticketsPanel.querySelectorAll('[data-ticket-favorite]').forEach(button=>{const active=ticketFavorites.includes(button.dataset.ticketFavorite);button.classList.toggle('active',active);button.setAttribute('aria-pressed',String(active));const icon=button.querySelector('i');if(icon)icon.className=active?'bi bi-star-fill':'bi bi-star';});
        const count=document.getElementById('ticket-favorite-count');if(count)count.textContent=ticketRows.filter(row=>ticketFavorites.includes(row.dataset.ticketId)).length;
        storage.set(ticketFavoritesKey,ticketFavorites);applyTicketFilter();
      }
      ticketsPanel.querySelectorAll('[data-ticket-favorite]').forEach(button=>button.addEventListener('click',event=>{event.preventDefault();event.stopPropagation();const id=button.dataset.ticketFavorite;ticketFavorites=ticketFavorites.includes(id)?ticketFavorites.filter(value=>value!==id):[...ticketFavorites,id];syncTicketFavorites();}));
      ticketsPanel.querySelectorAll('button[data-ticket-tab]').forEach(button=>button.addEventListener('click',()=>{const filter=button.dataset.ticketTab==='callbacks'?'callback':button.dataset.ticketTab;ticketFilter=ticketFilter===filter?null:filter;applyTicketFilter();}));
      ticketsPanel.querySelectorAll('[data-overview-filter]').forEach(button=>button.addEventListener('click',()=>{ticketFilter=ticketFilter===button.dataset.overviewFilter?null:button.dataset.overviewFilter;applyTicketFilter();}));
      ticketTable?.addEventListener('change',event=>{if(event.target===selectAll)ticketRows.filter(row=>!row.hidden).forEach(row=>{const checkbox=row.querySelector('input[type="checkbox"]');if(checkbox)checkbox.checked=selectAll.checked;});syncSelection();});
      document.getElementById('portal-clear-selection')?.addEventListener('click',()=>{ticketRows.forEach(row=>{const checkbox=row.querySelector('input[type="checkbox"]');if(checkbox)checkbox.checked=false;});syncSelection();});
      document.getElementById('portal-export-tickets')?.addEventListener('click',()=>{
        const selected=selectedRows();if(!selected.length||!ticketTable)return;
        const allHeaders=Array.from(ticketTable.tHead.rows[0].cells);const indexes=allHeaders.map((cell,index)=>({cell,index})).filter(({cell})=>cell.textContent.trim()&&!cell.classList.contains('novalyo-column-hidden')).map(({index})=>index);
        const quote=value=>{let text=value.trim().replace(/\s+/g,' ');if(/^[=+\-@]/.test(text))text="'"+text;return '"'+text.replace(/"/g,'""')+'"';};
        const csv=[indexes.map(index=>quote(allHeaders[index].textContent)).join(','),...selected.map(row=>indexes.map(index=>quote(row.cells[index].textContent)).join(','))].join('\r\n');
        const url=URL.createObjectURL(new Blob(['\ufeff'+csv],{type:'text/csv;charset=utf-8;'}));const a=document.createElement('a');a.href=url;a.download='novalyo-tickets.csv';a.click();setTimeout(()=>URL.revokeObjectURL(url),1000);
      });
      syncTicketFavorites();
    }

  }
  // The direct asset runs before an older deferred Vite bundle can claim initialization.
  if (document.body && document.getElementById('novalyo-labels')) initPortal();
  else if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded',initPortal);
  else initPortal();
})();
