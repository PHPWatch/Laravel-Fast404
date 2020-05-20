Laravel Fast 404
================

Laravel Fast 404 is a Laravel package that adds a global middleware to your Laravel application to immediately terminate HTTP(s) requests for non-HTML 404 requests. This prevents unnecessary database connections and your application from fully bootstrapping to serve an HTML page that nobody will see. 

This is done by inspecting every incoming HTTP request's "Accept" header and the URI path. If the URI path ends with a known static file extension (such as `.jpg`, `.png`, `.woff`, etc), and the `Accept` header does not mention `text/html` (which is the case when browsers request images, web fonts, JS files, CSS files, etc), the request is immediately terminated by this middleware.

## Requirements

 - Laravel 5, 6, or 7
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
|---|---|---|---|---|
|`message`|`Not Found`|The message to be shown when this package terminates a request. It might contain HTML. Try to keep the message short.|
|`regex`|`/\.(?:js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2)$/i`|A full regular expression to match against the request URI (without base-URI and URL parameters). If matched, this package will attempt to terminate the request. The default value will be used if `null` is passed. Make sure to include expression delimiters and flags if necessary. It is recommended to keep the default value.|
|`exclude_regex`|``|An optional regular expression to match, and if matched, this package will **not** terminate the request even if the `exclude` expression matched positive. This can be used to declare exclusion patterns if your Laravel application generates images on-the-fly, provides dynamic `.js` files, etc.|


 

 
 
