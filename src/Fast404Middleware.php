<?php
declare(strict_types=1);

namespace PHPWatch\LaravelFast404;

use Illuminate\Http\Request;

use function http_response_code;
use function preg_match;
use function strpos;

class Fast404Middleware {
    private $error_message;
    private $regex;
    private $exclude_regex;

    protected const ALLOW_MIME = 'text/html';

    final public function __construct(string $error_message = 'Not found', string $regex = null, ?string $exclude_regex = null) {
        $this->error_message = $error_message;
        $this->regex = $regex ?? '/\.(?:js|css|jpg|jpeg|gif|png|webp|ico|exe|bin|dmg|woff|woff2)$/i';
        $this->exclude_regex = $exclude_regex;
    }

    public function handle(Request $request, callable $next) {
        if ($this->isFast404($request)) {
            $this->terminate();
        }

        return $next($request);
    }

    public function isFast404(Request $request): bool {
        $uri = $request->path();
        return
            $this->regex
            && strpos($request->header('Accept'), static::ALLOW_MIME) === false
            && preg_match($this->regex, $uri)
            && !(isset($this->exclude_regex) && preg_match($this->exclude_regex, $uri));
    }

    /**
     * Terminate the request with an HTTP 404 code. This method is mocked when tested.
     * @codeCoverageIgnore
     */
    protected function terminate(): void {
        http_response_code(404);
        die($this->error_message);
    }
}
