# Staging mode for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andriymiz/laravel-staging-mode.svg?style=flat-square)](https://packagist.org/packages/andriymiz/laravel-staging-mode)
[![Total Downloads](https://img.shields.io/packagist/dt/andriymiz/laravel-staging-mode.svg?style=flat-square)](https://packagist.org/packages/andriymiz/laravel-staging-mode)

This package allows you to enable a "staging mode" for your Laravel application. This mode is similar to [maintenance mode](https://laravel.com/docs/10.x/configuration#maintenance-mode), but without blocking scheduled tasks and queues.

## Installation

You can install the package via composer:

```bash
composer require andriymiz/laravel-staging-mode
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="laravel-staging-mode-config"
```

Add middleware in `app/Http/Kernel.php`:
```php
protected $middleware = [
    // ...
    \StagingMode\Http\Middleware\PreventRequestsDuringStaging::class,
];
```

## Usage

Add the `STAGING_MODE_SECRET` environment variable to your `.env` file:
```bash
STAGING_MODE_SECRET=your-secret
```

Go to the `/your-secret` URL for allowing requests to your application.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
