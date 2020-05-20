<?php

namespace PHPWatch\LaravelFast404;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class Fast404ServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->singleton(Fast404Middleware::class, static function ($app): Fast404Middleware {
            return new Fast404Middleware(config('app.fast404.message', 'Not Found'), config('app.fast404.regex', null), config('app.fast404.exclude_regex', null));
        });
    }

    public function boot(): void {
        $this->app->get(Kernel::class)->prependMiddleware(Fast404Middleware::class);
    }
}
