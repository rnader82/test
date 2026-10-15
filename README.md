# Novalyo portal views and assets

The supplied Portail Pro Novalyo V01 HTML is the interface design. Its stylesheet is included in `public/css/demo.css`, with integration rules for the existing Laravel/Bootstrap components and the local icon font. The previous application stylesheet, inline layout rules, and legacy menu initialization are no longer used by the shared layout. Chart colors follow the same palette.

Copy `public/` and `resources/` into the corresponding directories of the existing Laravel application, preserving these paths. Keep the backend, routes, application configuration, Composer dependencies, and package manifests. Run `php artisan view:clear` after copying the views. Copy the language files together with the views. The new stylesheet and controller are served directly, with updated cache versions, so deployment does not require rebuilding the existing Vite assets. Vite continues to load the JavaScript entry; the source CSS imports the same public stylesheet for future builds.

The sidebar follows the HTML's navigation groups and uses only links already rendered by the application's permission checks. Display profiles organize that navigation without changing permissions. Existing routes, form actions, CSRF fields, method overrides, AJAX creation/editing dialogs, company switching, and pagination remain connected to the backend.

The ticket list uses the HTML's overview cards, header controls, five tabs, type column, bookmark stars, badges, and selection bar. Navigation favorites, recent pages, ticket bookmarks, and table preferences use browser storage. Filters over bookmarks, callbacks, and overview metrics operate on the current page of records. CSV export downloads selected rows without changing backend records. The existing server-side priority, user, search, client-group, and advanced filters remain in use.

Overview counts default to the current page's real ticket records. A controller may provide a `ticketOverview` array with `overdue`, `sla`, `today`, `unassigned`, `priority`, and `mine` counts for wider totals. Callback counts use stored `callback_at`, `reminder_at`, or `next_callback_at` attributes when present; no prototype demonstration counts are inserted into the application. Tooltip labels identify the page scope when global totals are not supplied.

The public controller in `public/js/main.js` matches the controller in `resources/js/app.js` (apart from its bootstrap import). Keep them synchronized when changing interface behavior. An initialization guard prevents duplicate handlers after a Vite build.

Sidebar navigation styles are scoped to the sidebar so they do not affect Bootstrap tabs. Detail tabs stay in a horizontal row and scroll within their container on narrower screens; long page titles wrap within the header.

The ticket detail view now uses the HTML's full-screen workspace: compact action header, status/SLA summary, workflow row, progress steps, six tabs, conversation bubbles, chronological events, and client/contract/equipment panels. Replies, internal messages, state/status changes, assignments, notes, editing, interventions, repairs, and PDF export use the existing Laravel routes. The close action selects an actual closing state supplied by the application and opens the existing confirmation form. Customer views exclude internal messages, notes, private interventions, and internal statuses.

The workflow's next-action text is saved as the existing state-change `note`; assignment and status controls open their existing forms. The SLA deadline is read-only because this checkout contains no endpoint for updating it. No demo workflow values, coverage promises, contacts, equipment, or quote records are fabricated. Optional `contact`, `equipment`, and `quotes` relationships are displayed when already loaded by the controller; stored contact/equipment attributes provide fallbacks. The existing document field is shown under Documents & history, with upload available through the existing edit page.

Ticket-detail validation: eighteen browser checks compare the rendered Blade view with the uploaded HTML and verify the six tabs, keyboard navigation, templates, composer validation, exact POST/PUT form payloads and CSRF fields, modal layering, state/assignment controls, visibility conversion, customer/employee restrictions, English labels, missing optional data, and responsive layouts from 1882 to 390 pixels. These use fixture data and a local form-capture server; they do not verify the absent backend controllers or database.

Validation: all 368 Blade views compile and pass PHP syntax checks using an isolated Laravel 12 view engine. Eighteen browser checks pass against the actual ticket, shared layout, authentication, and welcome views with fixture data, including visual geometry, permission-restricted navigation, search, preferences, real record filters, selection/export, DataTables, existing modal loading, and mobile navigation. Ten additional browser checks reproduce the previous tab bug and verify the actual client and equipment detail views with fixture data: content visibility, every tab's pane, sidebar collapse, related equipment tabs, hidden tabs, and layouts at 1882, 1024, 768, and 390 pixels. The full Laravel backend is not included here, so complete backend integration must be checked in the existing application.

## Module comparison and coverage

Compared the uploaded HTML with all 284 views that extend the application layout, covering 71 modules. Every application view now declares its module, page type and reference family, so the shared layout selects the appropriate structure rather than relying on a global color override. The 84 other Blade files are partials, standalone authentication/landing pages, print/PDF/email views or pagination templates; their form/document contracts remain intact.

The shared module list uses the reference header, description, toolbar, table card, density and column controls. Search forms keep their existing server actions. Page status filters and CSV exports use loaded rows only. List/Kanban views use the original records and links, with account/page-scoped preferences; they do not write workflow changes. Customer orders, purchases, supplier orders and deliveries include real page/total summary cards. Company records use permission-derived category/subsection navigation and overview cards; article records use the reference article and related-data tab shells. Repair records use a full-screen header and summary cards while preserving their existing per-equipment update forms. All existing field names, routes, CSRF fields, method overrides, includes and print exports were checked against the previous revision.

Legacy type/status badges now have explicit theme colors, and all 260 static Bootstrap icon classes used by the views have local glyph mappings. The old static `dashboard2` template now links to the real dashboard instead of showing sample sales figures. The direct controller initializes before a previously built Vite bundle, so copying these files does not require rebuilding assets. Table action menus use fixed Popper positioning, and repair dialogs are mounted outside the full-screen record layer.

| Repository module | Application views | Reference pattern |
| --- | ---: | --- |
| `accounting_families` | 4 | Article directory, article tabs and related data panels |
| `brands` | 4 | Article directory, article tabs and related data panels |
| `categories` | 4 | Administration module list, record and form patterns |
| `client_document_types` | 4 | Company/contact directory and company category navigation |
| `clients` | 4 | Company/contact directory and company category navigation |
| `combos` | 4 | Article directory, article tabs and related data panels |
| `commercials` | 3 | Prospecting / generic module list and record forms |
| `companies` | 4 | Administration module list, record and form patterns |
| `construction_reports` | 3 | Customer orders, deliveries and generic operations modules |
| `contacts` | 4 | Company/contact directory and company category navigation |
| `contract_clause_templates` | 4 | Commercial templates / generic module forms |
| `contract_templates` | 4 | Commercial templates / generic module forms |
| `contracts` | 4 | Company/contact directory and company category navigation |
| `countries` | 4 | Administration module list, record and form patterns |
| `dashboard` | 1 | General dashboard and task modules |
| `dashboard2` | 1 | General dashboard and task modules |
| `deliveries` | 4 | Customer orders, deliveries and generic operations modules |
| `employee_equipments` | 4 | Technical modules, planning and preventive workflow panels |
| `employees` | 4 | Technical modules, planning and preventive workflow panels |
| `equipment_categories` | 4 | Article directory, article tabs and related data panels |
| `equipment_types` | 4 | Article directory, article tabs and related data panels |
| `equipments` | 7 | Article directory, article tabs and related data panels |
| `errors` | 1 | Administration module list, record and form patterns |
| `installers` | 4 | Technical modules, planning and preventive workflow panels |
| `issue_criticities` | 4 | Administration module list, record and form patterns |
| `issue_severities` | 4 | Administration module list, record and form patterns |
| `issue_types` | 4 | Administration module list, record and form patterns |
| `issues` | 4 | Technical modules, planning and preventive workflow panels |
| `issues_groups` | 4 | Administration module list, record and form patterns |
| `knowledge_base` | 4 | Technical modules, planning and preventive workflow panels |
| `languages` | 4 | Administration module list, record and form patterns |
| `loginwelcome` | 1 | Administration module list, record and form patterns |
| `logs` | 1 | Administration module list, record and form patterns |
| `message_templates` | 4 | Administration module list, record and form patterns |
| `odoo` | 1 | Administration module list, record and form patterns |
| `odoo_categories` | 4 | Article directory, article tabs and related data panels |
| `odoo_fiscal_positions` | 4 | Article directory, article tabs and related data panels |
| `orders` | 4 | Customer orders, deliveries and generic operations modules |
| `origines` | 4 | Administration module list, record and form patterns |
| `permissions` | 4 | Administration module list, record and form patterns |
| `planning` | 2 | Technical modules, planning and preventive workflow panels |
| `pricelists` | 4 | Article directory, article tabs and related data panels |
| `process_stages` | 4 | Administration module list, record and form patterns |
| `projects` | 4 | Customer orders, deliveries and generic operations modules |
| `prospects` | 4 | Prospecting / generic module list and record forms |
| `purchases` | 4 | Purchasing workspaces, suppliers and supplier orders |
| `quote-reserves` | 3 | Commercial templates / generic module forms |
| `quote-templates` | 4 | Commercial templates / generic module forms |
| `remises_ascom` | 4 | Article directory, article tabs and related data panels |
| `repair_diagnoses` | 4 | Administration module list, record and form patterns |
| `repairs` | 6 | Technical modules, planning and preventive workflow panels |
| `reports` | 4 | Performance cards and reports |
| `roles` | 4 | Administration module list, record and form patterns |
| `seeds` | 2 | Administration module list, record and form patterns |
| `states` | 4 | Administration module list, record and form patterns |
| `statuses` | 4 | Administration module list, record and form patterns |
| `supplier_contacts` | 4 | Purchasing workspaces, suppliers and supplier orders |
| `supplier_orders` | 4 | Purchasing workspaces, suppliers and supplier orders |
| `suppliers` | 4 | Purchasing workspaces, suppliers and supplier orders |
| `tech_intervention_severities` | 4 | Administration module list, record and form patterns |
| `tech_intervention_states` | 4 | Administration module list, record and form patterns |
| `tech_intervention_statuses` | 4 | Administration module list, record and form patterns |
| `tech_intervention_types` | 4 | Administration module list, record and form patterns |
| `tech_intervs` | 4 | Technical modules, planning and preventive workflow panels |
| `tenants` | 4 | Administration module list, record and form patterns |
| `todos` | 4 | General dashboard and task modules |
| `user_assignments` | 4 | Technical modules, planning and preventive workflow panels |
| `users` | 5 | Administration module list, record and form patterns |
| `visite_prev` | 24 | Technical modules, planning and preventive workflow panels |
| `warehouses` | 3 | Article directory, article tabs and related data panels |
| `warranty_types` | 4 | Administration module list, record and form patterns |

Validation for this revision: all 368 Blade templates compile and pass PHP syntax checks. Twenty-eight actual application pages are rendered with fixture data across directory, form, record, purchasing, administration, client and article layouts and checked at desktop/mobile widths. Eighteen module interaction checks cover native form submissions and validation, server search, status filters, CSV exports, Kanban, DataTables, column/density persistence, company permissions, article tabs, repair dialogs, and an older deferred Vite bundle. Eighteen ticket-detail regression checks also pass. This checkout contains no controllers, database, routes file or application manifests; the full Laravel runtime still needs integration validation in the host application.
