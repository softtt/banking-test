<?php

namespace App\Http\Middleware;

use App\Services\Api\RequestSignatureVerificationService;
use Closure;
use Illuminate\Http\Request;

class EnsureSignatureIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->getRequestSignatureVerification()->verify($request);

        return $next($request);
    }

    private function getRequestSignatureVerification(): RequestSignatureVerificationService
    {
        return app(RequestSignatureVerificationService::class);
    }
}
