<?php


namespace App\Http\Middleware;

use App\Services\Api\FailsService;
use Closure;
use Illuminate\Http\Request;

class ServiceFails
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
        if ($this->getService()->isServiceFailedToday()) {
            abort(403);
        }

        return $next($request);
    }

    private function getService(): FailsService
    {
        return app(FailsService::class);
    }
}
