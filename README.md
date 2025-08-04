# AGNSTK - Agnostic Glue for Non-Specific ToolKits

**One Core. Any CMS.**

[![License: AGPL-v3](https://img.shields.io/badge/License-AGPLv3-yellow.svg)](https://www.gnu.org/licenses/agpl-3.0)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php)](https://www.php.net/)
[![CMS Agnostic](https://img.shields.io/badge/CMS-Agnostic-ff69b4)](https://agnstk.org)

---

## What is AGNSTK?

AGNSTK (pronounced *"ag-nostic"*) is the **duct tape for your CMS plugins**—except it’s reusable, elegant, and won’t leave sticky residue. It’s a **single codebase** that adapts to WordPress, Drupal, October CMS, Laravel, and more.

Think of it as:
- A **Swiss Army knife** for CMS development.
- A **universal adapter** for your PHP tools.
- The **glue** that binds your logic to any platform.

---

## Why Use AGNSTK?

✅ **Write once, deploy anywhere** – No more rewriting plugins for each CMS.
✅ **Lightweight core** – Only ~50KB of glue code (the rest is your logic).
✅ **No lock-in** – Your business logic stays clean and portable.
✅ **Fun to say** – "AGNSTK" sounds like a robot sneezing.

---

## How It Works

AGNSTK is built **on Laravel**, providing a robust core for your logic. Adapters then bridge this core to other CMS platforms.

```plaintext
core/          # Laravel-based core logic
├── app/       # Your business logic (e.g., Booking, Payments)
├── config/
└── routes/

adapters/      # CMS-specific entry points (glue code)
├── wordpress/
├── drupal/
└── october/
```

(Want another CMS? Open an issue!)

## Installation

* Clone the repo:
```bash
git clone https://github.com/magicoli/agnstk.git
cd agnstk
```

Pick your CMS:
```bash
cp -r adapters/wordpress/ /path/to/your/wp-content/plugins/agnstk/
```

Run Composer (if needed):
```bash
composer install
```

### Example: Hello World
```php
// core/src/Hello.php
namespace App\Core;

class Hello {
    public static function sayHi() {
        return "Hello from AGNSTK!";
    }
}
// adapters/wordpress/wordpress-plugin.php
add_shortcode('agnstk_hello', function() {
    return \App\Core\Hello::sayHi();
});
```

Now use [agnstk_hello] in WordPress!

## ExampleApp - AGNSTK Proof of Concept

This is a CMS-agnostic application framework that allows you to write your core business logic once and deploy it across multiple platforms.

### Current Status
The proof of concept includes:
- **Standalone app**: Access via `index.php`
- **WordPress plugin**: Install as plugin with `exampleapp.php` as main file
- **Drupal module**: Use `exampleapp.module` 
- **October CMS plugin**: Use `Plugin.php`

### Quick Test
1. **Standalone**: Visit the root URL to see the standalone app
2. **WordPress**: Activate the plugin and use shortcode `[exampleapp_membership]`

### Core Features
- `MembershipService`: Returns membership information for a given user ID
- Platform-specific adapters handle user authentication and CMS integration

### File Structure
```
agnstk/
├── index.php              # Standalone entry
├── exampleapp.php         # WordPress plugin entry
├── exampleapp.module      # Drupal module entry  
├── Plugin.php             # October CMS plugin entry
├── composer.json          # Autoloader configuration
├── core/app/Services/     # Core business logic
└── adapters/              # Platform-specific adapters
```

### Setup
Run `composer install` to generate the autoloader, then test each platform.

## Contributing

Found a bug? https://github.com/magicoli/agnstk/issues
Want help or discuss a related matter? https://github.com/magicoli/agnstk/discussions
Want to add a CMS? Open a an issue on GitHub.

**Code of Conduct**: Be nice. We’re all just trying to glue things together.
License: MIT (use it, break it, fix it).


## Why "TransKit"?
Because "ToolKit" was too boring. AGNSTK transforms your code to fit anywhere—like a chameleon, but for PHP.

Made with ❤️ and duct tape by [magicoli](https://github.com/magicoli).
