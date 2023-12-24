# LaraBreadcrumb

LaraBreadcrumb is a Laravel package designed to simplify the creation of breadcrumbs in your Laravel applications.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)
  - [Creating Breadcrumbs](#creating-breadcrumbs)
  - [Displaying Breadcrumbs](#displaying-breadcrumbs)
- [Advanced Usage](#advanced-usage)
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

### Creating Breadcrumbs

```php
 $this->breadcrumbs = BreadcrumbService::createFromRequest()->generate();
```

### Displaying Breadcrumbs

This is an example of how to display breadcrumbs. You can customize the rendering logic to suit your needs.

```php
@props([
    /** @var \App\Services\BreadcrumbLink[] */
    'breadcrumbs' => []
])

<div style="display: flex">
    @foreach ($breadcrumbs as $breadcrumb)
        <a
                style="white-space: pre"
                href="{{ $breadcrumb->url }}"
        >{{ str($breadcrumb->title)->ucfirst() }} @if($loop->remaining)> @endif</a>
    @endforeach
</div>
```

## Advanced Usage

You can customize the title of the breadcrumb by passing a callback to the `createFromRequest()` method.
The key of the array is the name of the route parameter and the value is a callback that accepts the route model and returns the
title of the breadcrumb.

```php
// Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

$breadcrumbs = BreadcrumbService::createFromRequest([
            'user' => fn(User $user) => $user->full_name,
        ])->generate();
```

You can also hide certain breadcrumbs.

```php
// Route::get('/users/{user}/comments/{comment}', [UserController::class, 'show'])->name('users.show');

 $breadcrumbs = BreadcrumbService::createFromRequest()->hide('comments')->generate();
```

This will hide the `comments` from breadcrumb. The result will be `Users / {user} / {comment}`.
The `hide` method accepts a string or an array of strings `->hide(['comments', 'users', etc...])`.

__Note:__

_If you don't pass any callbacks to the `createFromRequest()` method, the package will try to generate breadcrumbs
automatically. It will use the route parameter values as the title of the breadcrumb.
So for example, if you have a route like this: `admin/customers/132321`, it will generate a breadcrumb like this:
`Admin / Customers / 132321`._

## Contributing

We welcome contributions to LaraBreadcrumb. If you'd like to contribute, please fork the repository, make your changes,
and
submit a pull request. We have a few requirements for contributions:

- Follow the PSR-2 coding standard.
- Only use pull requests for contributions.

## Changelog

For a detailed history of changes, see [releases](https://github.com/Bored-Programmers/larabreadcrumb/releases) on GitHub.

## License

This project is licensed under the [MIT license](https://github.com/Bored-Programmers/larabreadcrumb/blob/main/LICENSE.md).

## Contact Information

For any questions or concerns, please feel free to create
a [discussion](https://github.com/Bored-Programmers/larabreadcrumb/discussions) on GitHub.

## Credits

Created by [Matěj Černý](https://github.com/LeMatosDeFuk)
from [Bored Programmers](https://github.com/Bored-Programmers).

## Acknowledgments

We would like to thank all the contributors who have helped to make LaraBreadcrumb a better package.
