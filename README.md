# Ebtekar DCB Payment Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ybreaka98/ebtekardcb.svg?style=flat-square)](https://packagist.org/packages/ybreaka98/ebtekardcb)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ybreaka98/ebtekardcb/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ybreaka98/ebtekardcb/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ybreaka98/ebtekardcb/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ybreaka98/ebtekardcb/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ybreaka98/ebtekardcb.svg?style=flat-square)](https://packagist.org/packages/ybreaka98/ebtekardcb)

A Laravel package for integrating with Ebtekar DCB (Direct Carrier Billing) payment services. This package provides a simple and elegant API for handling mobile subscription payments, user authentication, and subscription management.

## Requirements

- PHP 8.2 or higher
- Laravel 11.0 or 12.0
- ext-json

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/EbtekarDCB.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/EbtekarDCB)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require ybreaka98/ebtekardcb
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="ebtekardcb-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="ebtekardcb-config"
```

This is the contents of the published config file:

```php
return [
    'base_url' => env('EBTEKAR_BASE_URL', 'https://connexlive.ebtekarcloud.com/external-api/'),
    'email' => env('EBTEKAR_DCB_EMAIL', ''),
    'password' => env('EBTEKAR_DCB_PASSWORD', ''),
]
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="ebtekardcb-views"
```

## Usage

```php
use Ybreaka98\EbtekarDCB\Facades\EbtekarDCB;

// Initialize subscription login
$response = EbtekarDCB::login('218912345678', 'unique_transaction_id');

if ($response->isSuccessful()) {
    // Handle successful login request
    $data = $response->getJson();
}

// Confirm login with OTP
$confirmResponse = EbtekarDCB::confirmLogin('218912345678', '1234');

// Get subscription details
$details = EbtekarDCB::subscriptionDetails('218912345678');

// Request protected script
$script = EbtekarDCB::requestProtectedScript('#payment-button');
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

- [ybreaka98](https://github.com/ybreaka98)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
