<?php
/**
 * description: 多语环境设置
 */

namespace App\Http\Middleware;

use App\Enum\LangEnum;
use Closure;

class LangDetect
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        app('translator')->setLocale(config('app.locale'));
        return $next($request);
    }
}
