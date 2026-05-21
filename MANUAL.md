# tigress/controller — Programmer's Manual

## Overview

`tigress/controller` is the base controller module of the **Tigress Framework** (PHP 8.5+). It provides a lightweight `Tigress\Controller` class that other application controllers extend. Responsibilities include:

- **User rights checking** — guard pages behind permission levels
- **Table-name-to-class-name conversion** — dynamically resolve repository class names from database table names
- **Version reporting** — expose the module version

## Installation

```bash
composer require tigress/controller
```

This library requires PHP >= 8.5 and the `ext-pdo` extension. It is autoloaded via PSR-4: `Tigress\` → `src/`.

## The `Controller` Class

Full path: `src/Controller.php` — Namespace: `Tigress`

### `Controller::version(): string`

Returns the module version string.

```php
echo Controller::version(); // "2025.12.09"
```

### `$controller->checkRights(string $rights = 'access'): void`

Verifies the current user has the required permission. If the check fails, an error message is stored in `$_SESSION['error']` and the user is redirected to `/login`.

**Dependencies** (provided by `tigress/core`):
- `RIGHTS` — global object exposing `checkRights(string): bool`
- `TWIG` — global object exposing `redirect(string): void`
- `__()` — translation function

**Usage:**

```php
class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->checkRights('access');
        // user is authorized — proceed
    }
}
```

The method acts as a **guard clause**: it halts execution on failure via redirect, so no `else` branch is needed after the call.

### `$controller->tableNameToClass(string $tableName, string $suffix = 'Repo'): string`

Converts a snake_case database table name into a CamelCase class name with a configurable suffix.

| Input (`tableName`) | `suffix` | Output |
|---|---|---|
| `'user_roles'` | `'Repo'` (default) | `'UserRolesRepo'` |
| `'products'` | `'Model'` | `'ProductsModel'` |
| `'order_items'` | `'Repository'` | `'OrderItemsRepository'` |

**Usage:**

```php
$controller = new Controller();
$class = $controller->tableNameToClass('user_roles');
// $class = 'UserRolesRepo'

$repo = new $class(); // if the class exists
```

This is useful for dynamic repository resolution — mapping database tables to their corresponding repository/model classes without manual configuration.

## Extending the Controller

Your application controllers extend `Controller` and inherit all methods:

```php
<?php

namespace App\Controller;

use Tigress\Controller;

class UserController extends Controller
{
    public function list(): void
    {
        $this->checkRights('write');

        $repoClass = $this->tableNameToClass('users');
        $repo = new $repoClass();
        $users = $repo->loadAll();

        // render view...
    }
}
```

## Architecture & Dependencies

```
tigress/controller ─── requires ─── php >= 8.5
                                        └── ext-pdo
                         dev ───── tigress/core ^2025
                                      └── provides RIGHTS, TWIG, __()
```

The `tigress/core` package is a **dev dependency** but is expected to be available at runtime in any real application using the full Tigress Framework.

## Version

Current version: **2025.12.09** (returned by `Controller::version()`)

## License

GNU General Public License v3.0 or later (GPL-3.0-or-later).

## Future Plans

This module is intentionally minimalistic. It will probably grow when certain actions become repetitive across controllers.