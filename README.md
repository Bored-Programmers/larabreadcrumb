# LaraBreadcrumb

LaraBreadcrumb is a Laravel package designed to simplify the creation of breadcrumbs in your Laravel applications.

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

This will generate a breadcrumb like this: `breadcrumb.users / 1 / breadcrumb.comments / 1`.
**It is recommended to use a prefix**, because it will prevent conflicts with translations.
For example if you have route
`Route::get('admin/users')`, it will generate a breadcrumb like this: `admin / users`. It is not a problem, until you
have translation file `admin.php`. Then it will give you an error `array to string conversion`.

### Hide Breadcrumbs

You can also hide certain breadcrumbs.

```php
// Route::get('/users/{user}/comments/{comment}');

 $breadcrumbs = BreadcrumbService::update()->hide('comments');
 $breadcrumbs = BreadcrumbService::update()->hide(['comments', 'users']);
```

This will hide the `comments` from breadcrumb.
The first result will be `Users / {user} / {comment}`, second will be `{user} / {comment}`.

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
