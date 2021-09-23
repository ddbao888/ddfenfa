<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class authJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->issetToken($request) ) {
            return response()->json(['code'=>401, 'msg'=>'token 不存在', 'data'=>[]], 401);
        }

        try {
            $user = \Illuminate\Support\Facades\Auth::guard('api')->user();
            /*JWTAuth::parseToken()->authenticate();*/
            if (! auth('api')->parseToken()->checkOrFail()) {
                return response()->json('凭证在黑名单 or 无法正确解析', 401);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['code' => 400, 'msg'=>'Token 验证失败!'], 400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['code' =>401,'msg'=>'Token is Expired'], 401);
            }else{
                return response()->json(['code' => 500, 'msg'=>'Something is wrong'],401);
            }
        }
        return $next($request);
    }

    //判断request是否有token认证
    private function issetToken(Request $request)
    {
        if (auth('api')->parser()->setRequest($request)->hasToken() ) {
            return true;
        } else {
            return false;
        }
    }
}
