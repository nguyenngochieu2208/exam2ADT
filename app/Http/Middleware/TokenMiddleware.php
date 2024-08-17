<?php

namespace App\Http\Middleware;

use App\Helper\ApiHelper;
use App\Models\BitrixToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = Route::currentRouteName();
        $token = BitrixToken::query()->first();

        if (empty($token)) {

            if (empty($request->code)) {
                $authUrl = ApiHelper::getAccessToken();
                return redirect()->away($authUrl);

            }else{
                $domain = env('BITRIX_DOMAIN');

                $response = Http::get("http://{$domain}/oauth/token/", [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('services.bitrix.client_id'),
                    'client_secret' => config('services.bitrix.client_secret'),
                    'code' => $request->code,
                ]);

                $token = BitrixToken::create([
                    'domain' => env('BITRIX_DOMAIN'),
                    'access_token' => $response['access_token'],
                    'refresh_token' => $response['refresh_token'],
                    'application_token' => null,
                    'expires_at' => Carbon::now()->addSeconds($response['expires_in']),
                ]);

                return redirect()->route($route)->with('success', 'Token của bạn đã hết hạn, token mới đã được tạo');
            }
            return $next($request);
        }
        return $next($request);
    }
}
