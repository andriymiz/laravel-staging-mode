<?php

namespace StagingMode\Http\Middleware;

use Closure;
use StagingMode\Http\StagingModeBypassCookie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PreventRequestsDuringStaging
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        $stagingSecret = Config::get('staging-mode.secret');
        if ($stagingSecret) {
            if (isset($stagingSecret) && $request->path() === $stagingSecret) {
                return $this->bypassResponse($stagingSecret);
            }

            if ($this->hasValidBypassCookie($request, $stagingSecret)) {
                return $next($request);
            }

            throw new HttpException(503, 'Service Staging');
        }

        return $next($request);
    }

    /**
     * Determine if the incoming request has a staging mode bypass cookie.
     */
    protected function hasValidBypassCookie(Request $request, string $secret): bool
    {
        $cookieName = Config::get('staging-mode.cookie_name');

        return isset($secret) &&
                $request->cookie($cookieName) &&
                StagingModeBypassCookie::isValid(
                    $request->cookie($cookieName),
                    $secret
                );
    }

    /**
     * Determine if the request has a URI that should be accessible in maintenance mode.
     */
    protected function inExceptArray(Request $request): bool
    {
        foreach (Config::get('staging-mode.except') as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Redirect the user back to the root of the application with a maintenance mode bypass cookie.
     */
    protected function bypassResponse(string $secret): RedirectResponse
    {
        return redirect('/')->withCookie(
            StagingModeBypassCookie::create($secret)
        );
    }
}
