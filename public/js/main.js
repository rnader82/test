/* The legacy menu also needs safe reads when browser storage is restricted. */
function novalyoReadLegacyPreference(key) { try { return window.localStorage.getItem(key); } catch (_) { return null; } }
"use strict";window.isRtl=window.Helpers.isRtl(),window.isDarkStyle=window.Helpers.isDarkStyle();let menu,animate,isHorizontalLayout=!1;document.getElementById("layout-menu")&&(isHorizontalLayout=document.getElementById("layout-menu").classList.contains("menu-horizontal")),function(){function e(){var e=document.querySelector(".layout-page");e&&(0<window.pageYOffset?e.classList.add("window-scrolled"):e.classList.remove("window-scrolled"))}"undefined"!=typeof Waves&&(Waves.init(),Waves.attach(".btn[class*='btn-']:not(.position-relative):not([class*='btn-outline-']):not([class*='btn-label-'])",["waves-light"]),Waves.attach("[class*='btn-outline-']:not(.position-relative)"),Waves.attach("[class*='btn-label-']:not(.position-relative)"),Waves.attach(".pagination .page-item .page-link"),Waves.attach(".dropdown-menu .dropdown-item"),Waves.attach(".light-style .list-group .list-group-item-action"),Waves.attach(".dark-style .list-group .list-group-item-action",["waves-light"]),Waves.attach(".nav-tabs:not(.nav-tabs-widget) .nav-item .nav-link"),Waves.attach(".nav-pills .nav-item .nav-link",["waves-light"]),Waves.attach(".menu-vertical .menu-item .menu-link.menu-toggle")),setTimeout(()=>{e()},200),window.onscroll=function(){e()},setTimeout(function(){window.Helpers.initCustomOptionCheck()},1e3),document.querySelectorAll("#layout-menu").forEach(function(e){menu=new Menu(e,{orientation:isHorizontalLayout?"horizontal":"vertical",closeChildren:!!isHorizontalLayout,showDropdownOnHover:novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--ShowDropdownOnHover")?"true"===novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--ShowDropdownOnHover"):void 0===window.templateCustomizer||window.templateCustomizer.settings.defaultShowDropdownOnHover}),window.Helpers.scrollToActive(animate=!1),window.Helpers.mainMenu=menu}),document.querySelectorAll(".layout-menu-toggle").forEach(e=>{e.addEventListener("click",e=>{if(e.preventDefault(),window.Helpers.toggleCollapsed(),config.enableMenuLocalStorage&&!window.Helpers.isSmallScreen())try{localStorage.setItem("templateCustomizer-"+templateName+"--LayoutCollapsed",String(window.Helpers.isCollapsed()));var t,a=document.querySelector(".template-customizer-layouts-options");a&&(t=window.Helpers.isCollapsed()?"collapsed":"expanded",a.querySelector(`input[value="${t}"]`).click())}catch(e){}})}),window.Helpers.swipeIn(".drag-target",function(e){window.Helpers.setCollapsed(!1)}),window.Helpers.swipeOut("#layout-menu",function(e){window.Helpers.isSmallScreen()&&window.Helpers.setCollapsed(!0)});let t=document.getElementsByClassName("menu-inner"),a=document.getElementsByClassName("menu-inner-shadow")[0];0<t.length&&a&&t[0].addEventListener("ps-scroll-y",function(){this.querySelector(".ps__thumb-y").offsetTop?a.style.display="block":a.style.display="none"});var n=document.querySelector(".dropdown-style-switcher");const o=document.documentElement.getAttribute("data-style");var s,i=novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--Style")||(window.templateCustomizer?.settings?.defaultStyle??"light"),n=(window.templateCustomizer&&n&&([].slice.call(n.children[1].querySelectorAll(".dropdown-item")).forEach(function(e){e.classList.remove("active"),e.addEventListener("click",function(){var e=this.getAttribute("data-theme");"light"===e?window.templateCustomizer.setStyle("light"):"dark"===e?window.templateCustomizer.setStyle("dark"):window.templateCustomizer.setStyle("system")}),e.getAttribute("data-theme")===o&&e.classList.add("active")}),n=n.querySelector("i"),"light"===i?(n.classList.add("ri-sun-line"),new bootstrap.Tooltip(n,{title:"Light Mode",fallbackPlacements:["bottom"]})):"dark"===i?(n.classList.add("ri-moon-clear-line"),new bootstrap.Tooltip(n,{title:"Dark Mode",fallbackPlacements:["bottom"]})):(n.classList.add("ri-computer-line"),new bootstrap.Tooltip(n,{title:"System Mode",fallbackPlacements:["bottom"]}))),"system"===(s=i)&&(s=window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"),[].slice.call(document.querySelectorAll("[data-app-"+s+"-img]")).map(function(e){var t=e.getAttribute("data-app-"+s+"-img");e.src=assetsPath+"img/"+t}),"undefined"!=typeof i18next&&"undefined"!=typeof i18NextHttpBackend&&i18next.use(i18NextHttpBackend).init({lng:window.templateCustomizer?window.templateCustomizer.settings.lang:"en",debug:!1,fallbackLng:"en",backend:{loadPath:assetsPath+"json/locales/{{lng}}.json"},returnObjects:!0}).then(function(e){r()}),document.getElementsByClassName("dropdown-language"));if(n.length){var l=n[0].querySelectorAll(".dropdown-item");for(let e=0;e<l.length;e++)l[e].addEventListener("click",function(){let a=this.getAttribute("data-language"),n=this.getAttribute("data-text-direction");for(var e of this.parentNode.children)for(var t=e.parentElement.parentNode.firstChild;t;)1===t.nodeType&&t!==t.parentElement&&t.querySelector(".dropdown-item").classList.remove("active"),t=t.nextSibling;this.classList.add("active"),i18next.changeLanguage(a,(e,t)=>{if(window.templateCustomizer&&window.templateCustomizer.setLang(a),"rtl"===n?"true"!==novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--Rtl")&&window.templateCustomizer&&window.templateCustomizer.setRtl(!0):"true"===novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--Rtl")&&window.templateCustomizer&&window.templateCustomizer.setRtl(!1),e)return console.log("something went wrong loading",e);r()})})}function r(){var e=document.querySelectorAll("[data-i18n]"),t=document.querySelector('.dropdown-item[data-language="'+i18next.language+'"]');t&&t.click(),e.forEach(function(e){e.innerHTML=i18next.t(e.dataset.i18n)})}i=document.querySelector(".dropdown-notifications-all");function d(e){"show.bs.collapse"==e.type||"show.bs.collapse"==e.type?(e.target.closest(".accordion-item").classList.add("active"),e.target.closest(".accordion-item").previousElementSibling?.classList.add("previous-active")):(e.target.closest(".accordion-item").classList.remove("active"),e.target.closest(".accordion-item").previousElementSibling?.classList.remove("previous-active"))}const c=document.querySelectorAll(".dropdown-notifications-read");i&&i.addEventListener("click",e=>{c.forEach(e=>{e.closest(".dropdown-notifications-item").classList.add("marked-as-read")})}),c&&c.forEach(t=>{t.addEventListener("click",e=>{t.closest(".dropdown-notifications-item").classList.toggle("marked-as-read")})}),document.querySelectorAll(".dropdown-notifications-archive").forEach(t=>{t.addEventListener("click",e=>{t.closest(".dropdown-notifications-item").remove()})}),[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(e){return new bootstrap.Tooltip(e)});[].slice.call(document.querySelectorAll(".accordion")).map(function(e){e.addEventListener("show.bs.collapse",d),e.addEventListener("hide.bs.collapse",d)});window.Helpers.setAutoUpdate(!0),window.Helpers.initPasswordToggle(),window.Helpers.initSpeechToText(),window.Helpers.navTabsAnimation(),window.Helpers.initNavbarDropdownScrollbar();let u=document.querySelector("[data-template^='horizontal-menu']");if(u&&(window.innerWidth<window.Helpers.LAYOUT_BREAKPOINT?window.Helpers.setNavbarFixed("fixed"):window.Helpers.setNavbarFixed("")),window.addEventListener("resize",function(e){window.innerWidth>=window.Helpers.LAYOUT_BREAKPOINT&&document.querySelector(".search-input-wrapper")&&(document.querySelector(".search-input-wrapper").classList.add("d-none"),document.querySelector(".search-input").value=""),u&&(window.innerWidth<window.Helpers.LAYOUT_BREAKPOINT?window.Helpers.setNavbarFixed("fixed"):window.Helpers.setNavbarFixed(""),setTimeout(function(){window.innerWidth<window.Helpers.LAYOUT_BREAKPOINT?document.getElementById("layout-menu")&&document.getElementById("layout-menu").classList.contains("menu-horizontal")&&menu.switchMenu("vertical"):document.getElementById("layout-menu")&&document.getElementById("layout-menu").classList.contains("menu-vertical")&&menu.switchMenu("horizontal")},100)),window.Helpers.navTabsAnimation()},!0),!isHorizontalLayout&&!window.Helpers.isSmallScreen()&&("undefined"!=typeof TemplateCustomizer&&(window.templateCustomizer.settings.defaultMenuCollapsed?window.Helpers.setCollapsed(!0,!1):window.Helpers.setCollapsed(!1,!1)),"undefined"!=typeof config)&&config.enableMenuLocalStorage)try{null!==novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--LayoutCollapsed")&&window.Helpers.setCollapsed("true"===novalyoReadLegacyPreference("templateCustomizer-"+templateName+"--LayoutCollapsed"),!1)}catch(e){}}(),"undefined"!=typeof $&&$(function(){window.Helpers.initSidebarToggle();var t,a,e,n=$(".search-toggler"),o=$(".search-input-wrapper"),s=$(".search-input"),i=$(".content-backdrop");n.length&&n.on("click",function(){o.length&&(o.toggleClass("d-none"),s.focus())}),$(document).on("keydown",function(e){var t=e.ctrlKey,e=191===e.which;t&&e&&o.length&&(o.toggleClass("d-none"),s.focus())}),setTimeout(function(){var e=$(".twitter-typeahead");s.on("focus",function(){o.hasClass("container-xxl")?(o.find(e).addClass("container-xxl"),e.removeClass("container-fluid")):o.hasClass("container-fluid")&&(o.find(e).addClass("container-fluid"),e.removeClass("container-xxl"))})},10),s.length&&(t=function(n){return function(t,e){let a;a=[],n.filter(function(e){if(e.name.toLowerCase().startsWith(t.toLowerCase()))a.push(e);else{if(e.name.toLowerCase().startsWith(t.toLowerCase())||!e.name.toLowerCase().includes(t.toLowerCase()))return[];a.push(e),a.sort(function(e,t){return t.name<e.name?1:-1})}}),e(a)}},n="search-vertical.json",$("#layout-menu").hasClass("menu-horizontal")&&(n="search-horizontal.json"),a=$.ajax({url:assetsPath+"json/"+n,dataType:"json",async:!1}).responseJSON,s.each(function(){var e=$(this);s.typeahead({hint:!1,classNames:{menu:"tt-menu navbar-search-suggestion",cursor:"active",suggestion:"suggestion d-flex justify-content-between px-3 py-2 w-100"}},{name:"pages",display:"name",limit:5,source:t(a.pages),templates:{header:'<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">Pages</h6>',suggestion:function({url:e,icon:t,name:a}){return'<a href="'+e+'"><div><i class="'+t+' me-2"></i><span class="align-middle">'+a+"</span></div></a>"},notFound:'<div class="not-found px-3 py-2"><h6 class="suggestions-header text-primary mb-2">Pages</h6><p class="py-2 mb-0"><i class="ri-warning-line me-2 ri-14px"></i> No Results Found</p></div>'}},{name:"files",display:"name",limit:4,source:t(a.files),templates:{header:'<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">Files</h6>',suggestion:function({src:e,name:t,subtitle:a,meta:n}){return'<a href="javascript:;"><div class="d-flex w-50"><img class="me-3" src="'+assetsPath+e+'" alt="'+t+'" height="32"><div class="w-75"><h6 class="mb-0">'+t+'</h6><small class="text-muted">'+a+'</small></div></div><small class="text-muted">'+n+"</small></a>"},notFound:'<div class="not-found px-3 py-2"><h6 class="suggestions-header text-primary mb-2">Files</h6><p class="py-2 mb-0"><i class="ri-warning-line me-2 ri-14px"></i> No Results Found</p></div>'}},{name:"members",display:"name",limit:4,source:t(a.members),templates:{header:'<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">Members</h6>',suggestion:function({name:e,src:t,subtitle:a}){return'<a href="app-user-view-account.html"><div class="d-flex align-items-center"><img class="rounded-circle me-3" src="'+assetsPath+t+'" alt="'+e+'" height="32"><div class="user-info"><h6 class="mb-0">'+e+'</h6><small class="text-muted">'+a+"</small></div></div></a>"},notFound:'<div class="not-found px-3 py-2"><h6 class="suggestions-header text-primary mb-2">Members</h6><p class="py-2 mb-0"><i class="ri-warning-line me-2 ri-14px"></i> No Results Found</p></div>'}}).bind("typeahead:render",function(){i.addClass("show").removeClass("fade")}).bind("typeahead:select",function(e,t){t.url&&(window.location=t.url)}).bind("typeahead:close",function(){s.val(""),e.typeahead("val",""),o.addClass("d-none"),i.addClass("fade").removeClass("show")}),s.on("keyup",function(){""==s.val()&&i.addClass("fade").removeClass("show")})}),$(".navbar-search-suggestion").each(function(){e=new PerfectScrollbar($(this)[0],{wheelPropagation:!1,suppressScrollX:!0})}),s.on("keyup",function(){e.update()}))});
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
