# This is my package laravel-camunda-client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcrud/laravel-camunda-client.svg?style=flat-square)](https://packagist.org/packages/beyondcrud/laravel-camunda-client)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/beyondcrud/laravel-camunda-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/beyondcrud/laravel-camunda-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/beyondcrud/laravel-camunda-client/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/beyondcrud/laravel-camunda-client/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcrud/laravel-camunda-client.svg?style=flat-square)](https://packagist.org/packages/beyondcrud/laravel-camunda-client)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

> Review usage of LaravelData, apakah lebih baik demikian atau ganti jadi [Validated DTO for Laravel](https://wendell-adriel.gitbook.io/laravel-validated-dto/)?

## Installation

You can install the package via composer:

```bash
composer require beyondcrud/laravel-camunda-client
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-camunda-client-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-camunda-client-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-camunda-client-views"
```

## Usage

```php
$laravelCamundaClient = new BeyondCRUD\LaravelCamundaClient();
echo $laravelCamundaClient->echoPhrase('Hello, BeyondCRUD!');
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

- [rama](https://github.com/ramaID)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## References

- [Camunda Platform REST API (7.19.6-ee)](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/)
