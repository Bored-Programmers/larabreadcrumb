# LaraBreadcrumb

LaraBreadcrumb is a Laravel package that simplifies the creation of breadcrumbs in Laravel applications.

__NOTE__

This was my first attempt at using Laravel containers and services. If you have any suggestions for improvement, please
let me know.

[![Laravel Version](https://img.shields.io/static/v1?label=laravel&message=%E2%89%A510.0&color=0078BE&logo=laravel)](https://laravel.com)
[![Version](http://poser.pugx.org/bored-programmers/larabreadcrumb/version)](https://packagist.org/packages/bored-programmers/larabreadcrumb)
[![Total Downloads](http://poser.pugx.org/bored-programmers/larabreadcrumb/downloads)](https://packagist.org/packages/bored-programmers/larabreadcrumb)
[![License](http://poser.pugx.org/bored-programmers/larabreadcrumb/license)](https://packagist.org/packages/bored-programmers/larabreadcrumb)
[![PHP Version Require](http://poser.pugx.org/bored-programmers/larabreadcrumb/require/php)](https://packagist.org/packages/bored-programmers/larabreadcrumb)

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)
    - [Displaying Breadcrumbs](#displaying-breadcrumbs)
    - [Customizing Breadcrumbs](#customizing-breadcrumbs)
    - [Prefixing Breadcrumbs](#prefixing-breadcrumbs)
    - [Hiding Breadcrumbs](#hiding-breadcrumbs)
    - [Disabling Breadcrumbs](#disabling-breadcrumbs)
    - [Translating Breadcrumbs](#translating-breadcrumbs)
    - [Publishing Views](#publishing-views)
- [Contributing](#contributing)
- [Changelog](#changelog)
- [License](#license)
- [Contact Information](#contact-information)
- [Credits](#credits)
- [Acknowledgments](#acknowledgments)

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher

## Installation

To install LaraBreadcrumb, use the following command:

```bash
composer require bored-programmers/larabreadcrumb
```

## Basic Usage

By default, LaraBreadcrumb generates breadcrumbs automatically based on the route.
It uses the route parameter values as the breadcrumb titles. For example, for a route like `admin/customers/1`,
it generates a breadcrumb like this: `Admin / Customers / 1`.

### Displaying Breadcrumbs

```blade
<x-larabreadcrumb::breadcrumb/>
```

### Customizing Breadcrumbs

For a route like `Route::get('/users/{customer}')`, LaraBreadcrumb generates a breadcrumb like this: `Users / 1`.
To customize the breadcrumb, you can use the `BreadcrumbService` class:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

BreadcrumbService::update()
    ->setAccessors([
        'customer' => fn($model) => $model->name
        'customer' => fn(User $user) => $user->name
        'customer' => 'name'
    ]);
])
```

This generates a breadcrumb like this: `Users / John`. The key `customer` is the name of the route parameter, and the
value is the accessor. You can use a closure or a string.

You can also add a single accessor conditionally:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

BreadcrumbService::update()
    ->setAccessors([
        'customer' => fn($model) => $model->name
        'customer' => fn(User $user) => $user->name
        'customer' => 'name'
    ]);
])

if (true) {
  BreadcrumbService::update()
      ->addAccessor('customer', fn($model) => $model->name);
  ])
}
```

### Prefixing Breadcrumbs

By default, breadcrumbs don't have a prefix. To add a prefix to the breadcrumbs, use the `BreadcrumbService` class:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

# Route::get('/users/{user}/comments/{comment}');

BreadcrumbService::update()
    ->setPrefix('breadcrumb');
])
```

This generates a breadcrumb like this: `breadcrumb.users / 1 / breadcrumb.comments / 1`.

**Note:** It's recommended to use a prefix when using translation to prevent conflicts. For example, for a route
like `Route::get('admin/users')`, it generates a breadcrumb like this: `admin / users`. This isn't a problem until you
have a translation file `admin.php`. Then it gives you an error `array to string conversion`.

### Hiding Breadcrumbs

You can hide certain breadcrumbs:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->hide('comments');
 $breadcrumbs = BreadcrumbService::update()->hide(['comments', 'users']);
```

This hides the `comments` breadcrumb. The first result will be `Users / {user} / {comment}`, and the second will
be `{user} / {comment}`.

**To hide dynamic segments, use curly braces `{}`:**

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->hide('{comment}');
 $breadcrumbs = BreadcrumbService::update()->hide(['{user}', '{comment}']);
```

This hides the dynamic segment from the breadcrumb. The first result will be `Users / {user} / Comments`, and the second
will be `Users / Comments`.

### Disabling Breadcrumbs

You can disable click events on certain breadcrumbs.

**Note:** This doesn't hide the link; it only disables the click event.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->disable('comments');
 $breadcrumbs = BreadcrumbService::update()->disable(['comments', 'users']);
```

After this, you won't be able to click on the `comments` breadcrumb in the first example and on the `comments`
and `users` breadcrumbs in the second example.

To disable dynamic segments, use curly braces `{}`:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->disable('{comment}');
 $breadcrumbs = BreadcrumbService::update()->disable(['{user}', '{comment}']);
```

After this, you won't be able to click on the `comment` breadcrumb in the first example and on the `user` and `comment`
breadcrumbs in the second example.

### Translating Breadcrumbs

You can translate certain breadcrumbs or all breadcrumbs by default:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Translate certain segments
BreadcrumbService::update()->translate('users');
BreadcrumbService::update()->translate(['users', 'comments']);

// Translate all segments by default
BreadcrumbService::update()->translateAll();
```

If you want to translate all breadcrumbs but don't want to translate certain segments, you can use the `dontTranslate`
method:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

BreadcrumbService::update()->translateAll(true);

// Don't translate certain segments
BreadcrumbService::update()->dontTranslate('users');
BreadcrumbService::update()->dontTranslate(['users', 'comments']);
```

This ensures that the 'users' segment isn't translated in the first example, and the 'users' and 'comments' segments
aren't translated in the second example, even if translateAll is set to true.

If you want to translate dynamic segments, you must use curly braces {}.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

BreadcrumbService::update()->translate('{user}');
BreadcrumbService::update()->translate(['{user}', '{comment}']);
```

## Publishing views

If you want to customize the views, you can publish them with this command:

```bash
php artisan vendor:publish --tag=larabreadcrumb-views
```

## Contributing

We welcome contributions to LaraBreadcrumb. If you'd like to contribute, please fork the repository, make your changes,
and
submit a pull request. We have a few requirements for contributions:

- Follow the PSR-2 coding standard.
- Only use pull requests for contributions.

## Changelog

For a detailed history of changes, see [releases](https://github.com/Bored-Programmers/larabreadcrumb/releases) on
GitHub.

## License

This project is licensed under
the [MIT license](https://github.com/Bored-Programmers/larabreadcrumb/blob/main/LICENSE.md).

## Contact Information

For any questions or concerns, please feel free to create
a [discussion](https://github.com/Bored-Programmers/larabreadcrumb/discussions) on GitHub.

## Credits

Created by [Matěj Černý](https://github.com/LeMatosDeFuk)
from [Bored Programmers](https://github.com/Bored-Programmers).

## Acknowledgments

We would like to thank all the contributors who have helped to make LaraBreadcrumb a better package.
