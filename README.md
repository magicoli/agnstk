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

- ✅ **Write once, deploy anywhere** – No more rewriting plugins for each CMS.
- ✅ **Lightweight core** – Only ~50KB of glue code (the rest is your logic).
- ✅ **No lock-in** – Your business logic stays clean and portable.
- ✅ **Fun to say** – "AGNSTK" sounds like a robot sneezing.

---

## How It Works

AGNSTK is built **on Laravel**, providing a robust core for your logic. Adapters then bridge this core to other CMS platforms.

```plaintext
agnstk/
├── index.php              # Standalone entry
├── exampleapp.php         # WordPress plugin entry
├── exampleapp.module      # Drupal module entry  
├── Plugin.php             # October CMS plugin entry
...                        # Other specific frameworks
├── adapters/              # CMS-specific entry points (glue code)
│   ├── drupal/
│   ├── joomla/
│   ├── octobercms/
│   └── wordpress/
├── app/                   # Laravel core business logic
│   ├── Blocks/
│   ├── Http/
│   ├── Models/
│   ├── Providers/
│   ├── Services/
│   └── helpers.php
├── config/
├── database/
├── routes/
...                         # Other core codes, should not be modified
└── resources/
```

(Want another CMS? Open an issue!)

## Installation

* Clone the repo:
```bash
git clone https://github.com/magicoli/agnstk.git
cd agnstk
composer install
```

Then start the development server (Laravel-based standalone app):
```bash
composer run dev
```
The app will be available at `http://localhost:8000`.

### Example: Hello World
```php
// src/Blocks/Hello.php
namespace App\Services;

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
AGNSTK is currently implemented as a **Laravel 12** application with CMS adapters:
- **Laravel standalone app**: Available at `http://localhost:8000` when running `composer run dev`
- **WordPress plugin**: Install as plugin with `exampleapp.php` as main file
- **Drupal module**: Available in `adapters/drupal/` 
- **October CMS plugin**: Available in `adapters/octobercms/`

### Quick Test
1. **Standalone Laravel**: Run `composer run dev` and visit `http://localhost:8000`
2. **WordPress**: Copy the full project folder to your plugins directory and activate (for production, delete the irrelevant adapters and entry points)

### Core Features
- **Laravel 12** framework providing robust architecture
- **PageService**: Configurable page system with dynamic routing
- **Bootstrap UI**: Clean, responsive interface
- **Markdown support**: README and content rendering with syntax highlighting
- Platform-specific adapters handle CMS integration and user authentication

## Contributing

- Found a bug? [github.com/magicoli/agnstk/issues](https://github.com/magicoli/agnstk/issues)
- Want help or discuss a related matter? [github.com/magicoli/agnstk/discussions](https://github.com/magicoli/agnstk/discussions)
- Want to add a CMS? Open a an issue on GitHub.

**Code of Conduct**: Be nice. We’re all just trying to glue things together.
License: MIT (use it, break it, fix it).


## Why "TransKit"?
Because "ToolKit" was too boring. AGNSTK transforms your code to fit anywhere—like a chameleon, but for PHP.

Made with ❤️ and duct tape by [magicoli](https://github.com/magicoli).
