<?php

namespace PHPWatch\LaravelFast404\Tests;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use PHPWatch\LaravelFast404\Fast404Middleware;

class Fast404MiddlewareTest extends TestCase {
    public function testRequestsNotTerminatedOnHtml(): void {
        $middleware = new Fast404Middleware();

        $request = Request::create('test.jpg', 'GET', [], [], [], [
            'HTTP_ACCEPT' => 'image/jpeg'
        ]);
        $this->assertTrue($middleware->isFast404($request));

        $request = Request::create('test.jpg', 'GET', [], [], [], [
            'HTTP_ACCEPT' => 'text/html'
        ]);
        $this->assertFalse($middleware->isFast404($request));
    }

    public function testCallsNextOnNon404(): void {
        $request = Request::create('test.jpg', 'GET', [], [], [], [
            'HTTP_ACCEPT' => 'text/html'
        ]);

        $next = static function() {
            throw new \RuntimeException('Called');
        };

        $middleware = new Fast404Middleware();
        $this->expectExceptionMessage('Called');
        $this->expectException(\RuntimeException::class);
        $middleware->handle($request, $next);
    }

    public function testTerminatesOnNon404(): void {
        $request = Request::create('test.jpg', 'GET', [], [], [], [
            'HTTP_ACCEPT' => 'image/jpeg'
        ]);

        $next = static function() {
            throw new \RuntimeException('Called');
        };

        $middleware = new class extends Fast404Middleware {
            protected function terminate(): void {
                throw new \RuntimeException('Terminated');
            }
        };

        $this->expectExceptionMessage('Terminated');
        $this->expectException(\RuntimeException::class);
        $middleware->handle($request, $next);
    }
}
