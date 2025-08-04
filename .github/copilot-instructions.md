# AGNSTK Coding Standards & Adapter Naming

- Make sure to take advantage of the Laravel environment present in core.
- Make sure to do everything in a scalable way (e.g. a block displaying Hello World will would share a lot of procedures with a block displaying the app name and version, avoid writing several times the same code)
- Do not rewrite procedures that are already provided by Laravel framework

## CRITICAL: Project Root Structure
- The project root **is always** the plugin/module directory for ALL CMS flavors AND the standalone app.
- All adapter entry files must be placed in the project root for development and testing.
- This ensures instant access to the core and avoids publishing/syncing packages during development.
- **NEVER FORGET**: The root directory serves as WordPress plugin, Drupal module, October CMS plugin, AND standalone app simultaneously.
- **ARCHITECTURE**: Core handles main app logic, adapters handle CMS-specific interfaces
- **COMPOSER**: Main composer.json is for AGNSTK library, each adapter has its own composer.json for CMS-specific dependencies

## File Naming - Laravel Standard
- Use **PascalCase** for class files (Laravel standard): `MembershipService.php`, `Controller.php`, `WordPressAdapter.php`
- Follow PSR-4: Namespace matches directory structure exactly
- Example: `AGNSTK\Core\Services` → `core/app/Services/`

## Example App Naming
- Use a generic name for the example app (e.g., `exampleapp`) in all adapters and standalone implementations.
- Do not use the library name (`agnstk`) for example app entry points.
- Example:
  - Standalone: `index.php` (loads Controller)
  - WordPress: `exampleapp.php` (with proper WP plugin headers)
  - Drupal: `exampleapp.module`
  - October CMS: `Plugin.php`

## Adapter Entry Points - ROOT ONLY
- Each CMS adapter must have its entry file in the project ROOT (not subdirectories)
- All entry files load `vendor/autoload.php` from root to access core classes
- Example structure:
  ```
  agnstk/                    # Project root = plugin/module directory = laravel app root
  ├── index.php              # Standalone entry
  ├── exampleapp.php         # WordPress plugin entry (with headers)
  ├── exampleapp.module      # Drupal module entry
  ├── Plugin.php             # October CMS plugin entry
  ├── app/                  # Core business logic
  └── .../                  # other Laravel directories
  ```

## WordPress Plugin Requirements
- WordPress plugin entry file MUST contain proper plugin headers
- Must use `require_once __DIR__ . '/vendor/autoload.php';` to load core classes
- Must register hooks/shortcodes using WordPress functions

## Core Logic
- Place shared business logic in `core/app/Services/`
- Keep core logic completely CMS-agnostic
- Use proper PSR-4 namespacing: `AGNSTK\Core\Services`

## For Automated Agents
- **ALWAYS** read .github/copilot-instructions.md for every request - THIS IS CRITICAL
- **NEVER FORGET**: The project root IS the plugin/module directory for ALL platforms
- **ALWAYS** use PascalCase for PHP class files (Laravel standard)
- **ALWAYS** ensure proper autoloading setup before creating any classes
- **REMEMBER**: Main composer.json is for AGNSTK library, each adapter has its own composer.json
