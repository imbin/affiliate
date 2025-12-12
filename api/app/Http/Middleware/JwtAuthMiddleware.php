<?php
/**
 * jwt token校验
 */

namespace App\Http\Middleware;

use Closure;
use App\Enum\CodeEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthMiddleware
{
    /**
     * 检验token
     *
     * @param $request
     * @param Closure $next
     * @param string $guard 看守器
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $code = CodeEnum::BASE_INVALID_TOKEN;
        $result = [
            'code' => $code,
            'msg' => __('base.invalid_token'),
            'data' => null
        ];
        try {
            Log::debug('init jwt auth');
            if (!JWTAuth::parseToken()->check()) {
                Log::error('parseToken');
                return response()->json($result);
            }

            if (!auth($guard)->check()) {
                Log::debug('auth guard check');
                return response()->json($result);
            }
            Log::debug('passed jwt auth');

            // 设置用户信息
            Auth::setUser(auth($guard)->user());

        } catch (\Exception $e) {
            $code = CodeEnum::BASE_INVALID_TOKEN;
            $result = [
                'code' => $code,
                'msg' => __('base.server_error').$e->getMessage(),
                'data' => null
            ];
            return response()->json($result);
        }
        return $next($request);
    }

}
