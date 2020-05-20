<div align="center">
<img width="1000" height="350" src="https://st.ayesh.me/files/code/laravel-fast404/header.png" alt="Laravel Fast 404">
</div>

Laravel Fast 404
================

Laravel Fast 404 is a Laravel package that adds a global middleware to your Laravel application to immediately terminate HTTP(s) requests for non-HTML 404 requests. This prevents unnecessary database connections and your application from fully bootstrapping to serve an HTML page that nobody will see. 

This is done by inspecting every incoming HTTP request's "Accept" header and the URI path. If the URI path ends with a known static file extension (such as `.jpg`, `.png`, `.woff`, etc), and the `Accept` header does not mention `text/html` (which is the case when browsers request images, web fonts, JS files, CSS files, etc), the request is immediately terminated by this middleware.

[![Latest Stable Version](https://poser.pugx.org/phpwatch/laravel-fast404/v)](https://packagist.org/packages/phpwatch/laravel-fast404) [![Total Downloads](https://poser.pugx.org/phpwatch/laravel-fast404/downloads)](https://packagist.org/packages/phpwatch/laravel-fast404) [![License](https://poser.pugx.org/phpwatch/laravel-fast404/license)](https://packagist.org/packages/phpwatch/laravel-fast404) ![CI](https://github.com/PHPWatch/Laravel-Fast404/workflows/CI/badge.svg)

## Requirements

 - Laravel 5.5+, 6, or 7
 - PHP 7.4
 
## Installation

```bash
composer require phpwatch/laravel-fast404
```

Upon installation, Laravel will automatically register the Service Provider bundled with this package, which will in turn register middleware and the `Fast404Middleware` service automatically. 

## Configuration

You can configure the message, the regular expression used to match URI patterns (e.g a list of file extensions), and optionally an exclusion regular expression.

### From configuration files
 
Update your `config/app.php` file, and add the following configuration to the existing array:

```php
<?php
return [
    // ...
    'fast404' => [
        'message' => 'Not Found',
        'regex' => '',
        'exclude_regex' => '',
    ],
];

```

All configuration values **must** be strings. 

|Configuration|Default Value|Description|
|---|---|---|
|`message`|`Not Found`|The message to be shown when this package terminates a request. It might contain HTML. Try to keep the message short.|
|`regex`|[`/\.(?:js\|css\...woff2)$/i`](#file-type-extensions)|A full regular expression to match against the request URI (without base-URI and URL parameters). If matched, this package will attempt to terminate the request. The default value will be used if `null` is passed. Make sure to include expression delimiters and flags if necessary. It is recommended to keep the default value.|
|`exclude_regex`|``|An optional regular expression to match, and if matched, this package will **not** terminate the request even if the `exclude` expression matched positive. This can be used to declare exclusion patterns if your Laravel application generates images on-the-fly, provides dynamic `.js` files, etc.|

### File type extensions

The default regular expression is:

```regexp
/\.(?:js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2)$/i
```

This creates a non-capturing group of file types separated by the pipe (`|`) symbol above.

## By updating the service provider (advanced)

This package bundles a Service Provider that conveniently enables middleware. You can turn this feature off if you wish to configure the middleware to your liking.

### Step 1: Remove the automatic provider discovery for this library

In your root `composer.json` file, add/merge configuration directives:

```json
{
    "extra": {
        "dont-discover": [
            "phpwatch/laravel-fast404"
        ]
    }
}

```
 
### Step 2: Add the middleware

In your application `App/Http/Kernel.php` file, prepend the Middleware provided by this package.

```php
<?php
class Kernel extends HttpKernel
{
    //
    protected $middleware = [
        \PHPWatch\LaravelFast404\Fast404Middleware::class,
        // other middleware
    ];
    // ...
}
```
 
Make sure to add `\PHPWatch\LaravelFast404\Fast404Middleware::class,` to the top because middlewares are run in the order they are declared.

### Optional step 3: Register a service provider for further custimizations

If you would like to configure the middleware to change the message, file extensions, or the exclusion pattern, you will need to register it in the Service Container.

To do so, you can either create a new service provider, or update an existing one to declare how the `\PHPWatch\LaravelFast404\Fast404Middleware` class is instantiated.


```php
// at the top
use PHPWatch\LaravelFast404\Fast404Middleware;



// in register() method:
$this->app->bind(Fast404Middleware::class, static function ($app): Fast404Middleware {
          return new Fast404Middleware('Not Found', '/\.(?:js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2)$/i');
        });
```
 
## Contributions

Contributions are welcome! Please feel free to send a PR or open an issue. Please note that this Laravel pacakge is in the same line as [`phpwatch/fast404`](https://github.com/PHPWatch/fast404) and [`phpwatch/wordpress-fast404`](https://github.com/PHPWatch/WordPress-Fast404) packages, and the extensions list updates will be made to all packages in a framework-agnostic way.
 
