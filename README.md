# LaraBreadcrumb

LaraBreadcrumb is a Laravel package designed to simplify the creation of breadcrumbs in your Laravel applications.

__NOTE__

This was my first try of using a laravel containers and services. If you have any suggestions, please let me know,
I'd like to improve this code.

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
    - [Hide Breadcrumbs](#hide-breadcrumbs)
    - [Publishing views](#publishing-views)
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

By default, LaraBreadcrumb will try to generate breadcrumbs automatically based on the route.
It will use the route parameter values as the title of the breadcrumb.
So for example, if you have a route like this: `admin/customers/1`, it will generate a breadcrumb like this:
`Admin / Customers / 1`.

### Displaying Breadcrumbs

```blade
<x-larabreadcrumb::breadcrumb/>
```

### Customizing Breadcrumbs

`Route::get('/users/{customer}')`

This route will generate a breadcrumb like this: `Users / 1`.
If you want to customize the breadcrumb, you can use the `BreadcrumbService` class like this:

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

This will generate a breadcrumb like this: `Users / John`. Key `customer` is the name of the route parameter, value is
the accessor. You can use a closure or a string.

Or you can add single accessor like this:

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

This is helpful when you want to add an accessor conditionally.

### Prefixing Breadcrumbs

By default, breadcrumb doesn't have any prefix. If you want to add a prefix to the breadcrumb, you can use the
`BreadcrumbService` class like this:

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

# Route::get('/users/{user}/comments/{comment}');

BreadcrumbService::update()
    ->setPrefix('breadcrumb');
])
```

This will generate a breadcrumb like this: `breadcrumb.users / 1 / breadcrumb.comments / 1`. <br><br>
**It is recommended to use a prefix when using translation**, because it will prevent conflicts. <br>
For example if you have route
`Route::get('admin/users')`, it will generate a breadcrumb like this: `admin / users`. It is not a problem, until you
have translation file `admin.php`. Then it will give you an error `array to string conversion`.

### Hide Breadcrumbs

You can also hide certain breadcrumbs.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->hide('comments');
 $breadcrumbs = BreadcrumbService::update()->hide(['comments', 'users']);
```

This will hide the `comments` from breadcrumb.
The first result will be `Users / {user} / {comment}`, second will be `{user} / {comment}`.

If you would like to dynamic segment, you must use curly braces `{}`.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->hide('{comment}');
 $breadcrumbs = BreadcrumbService::update()->hide(['{user}', '{comment}']);
```

This will hide the dynamic segment from breadcrumb.
The first result will be `Users / {user} / Comments`, second will be `Users / Comments`.

### Disable Breadcrumbs

You can also disable click on certain breadcrumbs. <br><br>
_**NOTE: This will not hide the link, it will only disable the click event.**_

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->disable('comments');
 $breadcrumbs = BreadcrumbService::update()->disable(['comments', 'users']);
```

After this, you won't be able to click on `comments` breadcrumb in the first example and on `comments` and `users` in
the second example.

If you would like to dynamic segment, you must use curly braces `{}`.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->disable('{comment}');
 $breadcrumbs = BreadcrumbService::update()->disable(['{user}', '{comment}']);
```

After this, you won't be able to click on `comment` breadcrumb in the first example and on `user` and `comment` in
the second example.

You can add the translation options to the `README.md` file under a new section titled "Translating Breadcrumbs". Here's
how you can do it:

### Translating Breadcrumbs

You can translate certain breadcrumbs or all breadcrumbs by default.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

// Translate certain segments
BreadcrumbService::update()->translate('users');
BreadcrumbService::update()->translate(['users', 'comments']);

// Translate all segments by default
BreadcrumbService::update()->translateAll();
```

If you want to translate all breadcrumbs but don't want to translate certain segments, you can use the `dontTranslate`
method.

```php
use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;

BreadcrumbService::update()->translateAll(true / false);

// Don't translate certain segments
BreadcrumbService::update()->dontTranslate('users');
BreadcrumbService::update()->dontTranslate(['users', 'comments']);
```

This will ensure that the 'users' segment is not translated in the first example and 'users' and 'comments' segments are
not translated in the second example, even if `translateAll` is set to `true`.

If you want to translate dynamic segments, you must use curly braces `{}`.

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
