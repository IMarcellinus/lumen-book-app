# Lumen Book App 

Rekomendasi dari buku :
[lumen programming guide: writing php microservices, rest and web service apis paul redmond](https://download.e-bookshelf.de/download/0008/0228/16/L-G-0008022816-0015784950.pdf)

## Tech Stack

**Server:** PHP "^8.1", laravel/lumen-framework "^10.0"

**Require Dev:** faker ^1.91, mockery ^1.6, phpunit/phpunit ^10.5

## Run Locally

Clone the project

```bash
  git clone https://github.com/IMarcellinus/lumen-book-app.git
```

Go to the project directory

```bash
  cd lumen-book-app
```

Install dependencies

```bash
  composer update
```

```bash
  composer install
```

[!WARNING]
Dont forget fill name data in .env

Running database migrations
```bash
  php artisan migrate
```

Running BookSeeder
```bash
  php artisan db:seed BookSeeder 
```

Start the server

```bash
  php -S localhost:8000 -t public
```

## Running Tests

To run tests, run the following command

```bash
  vendor/bin/phpunit 
```

# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

> **Note:** In the years since releasing Lumen, PHP has made a variety of wonderful performance improvements. For this reason, along with the availability of [Laravel Octane](https://laravel.com/docs/octane), we no longer recommend that you begin new projects with Lumen. Instead, we recommend always beginning new projects with [Laravel](https://laravel.com).

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
