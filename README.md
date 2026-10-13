# Novalyo portal views and assets

This repository contains the Laravel views and public assets used by an existing Novalyo application. It does not contain its backend or dependency manifests. The interface follows the supplied Portail Pro Novalyo V01 design through the shared layout, reusable components, authentication screens, and document branding.

Copy `public/` and `resources/` into the corresponding directories of the existing application, preserving these paths. Keep the application's backend, routes, configuration, Composer dependencies, and package manifests. Run `php artisan view:clear` in that application after copying the views. Copy the language files together with the views so the new controls have their French and English labels.

The theme is served directly from `public/css/demo.css`, and the interface controller from `public/js/main.js`, so these changes work with the existing Vite build. The same controller is included in `resources/js/app.js` for subsequent application builds; an initialization guard prevents duplicate handlers. Keep its Novalyo controller block synchronized with the block in `public/js/main.js` when changing those controls. The source stylesheet imports the public theme.

The interface provides navigation search (Ctrl/Command K), favorites and recent navigation pages scoped to the signed-in account, persistent sidebar collapse, mobile navigation with keyboard support, table density, and column preferences for simple tables, including DataTables. Preferences use browser storage and still work for the current page if storage is unavailable. Search and saved pages use the existing permission-visible navigation links. Existing Laravel routes, form actions, CSRF fields, permissions, pagination, and page-specific scripts are retained.

PDF and email views retain their self-contained layouts and renderer-compatible fonts, with matching Novalyo colors. The shared interface uses the already included local icon font for its core navigation and controls. Existing page integrations retain their original service dependencies.

Validation: all 368 Blade views were compiled and PHP syntax checked using an isolated Laravel 12 view engine. Fifteen browser checks passed against the real shared layout and authentication/welcome views with fixture data, including responsive navigation, preferences, DataTables, Bootstrap dialogs/filters, password visibility, and operation without browser storage. Full backend integration must be verified in the existing Laravel application; this repository cannot run that application by itself.
