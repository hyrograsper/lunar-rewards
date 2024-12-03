# A rewards package in the form of a plugin for lunarphp.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hyrograsper/lunar-rewards.svg?style=flat-square)](https://packagist.org/packages/hyrograsper/lunar-rewards)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hyrograsper/lunar-rewards/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hyrograsper/lunar-rewards/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hyrograsper/lunar-rewards/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hyrograsper/lunar-rewards/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hyrograsper/lunar-rewards.svg?style=flat-square)](https://packagist.org/packages/hyrograsper/lunar-rewards)

This package is to add rewards to [Lunar](https://lunarphp.io). We have based this package off of how Lunar has implemented their discounts.

## Installation

You can install the package via composer:

```bash
composer require hyrograsper/lunar-rewards
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="lunar-rewards-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="lunar-rewards-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="lunar-rewards-views"
```

## Usage

```php
Coming soon.
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Brad Fowler](https://github.com/bradfowler)
- [Alec Garcia](https://github.com/alecgarcia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
