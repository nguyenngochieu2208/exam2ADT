<?php

namespace App\Helper;

use App\Models\BitrixToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ApiHelper
{
    public static function getAccessToken(){
        $domain = env('BITRIX_DOMAIN');
        $clientId = config('services.bitrix.client_id');

        $authUrl = "http://{$domain}/oauth/authorize/?client_id={$clientId}&response_type=code";

        return $authUrl;
    }

    // Yêu cầu token mới từ Bitrix khi token hết hạn
    public static function renewToken()
    {
        // Lấy token từ DB
        $token = BitrixToken::query()->first();

        // Gửi request lên Bitrix để lấy token mới
        $response = Http::get('https://'.$token->domain.'/oauth/token/', [
            'grant_type' => 'refresh_token',
            'client_id' => config('services.bitrix.client_id'),
            'client_secret' => config('services.bitrix.client_secret'),
            'refresh_token' => $token->refresh_token,
        ]);

        //Lấy token mới thành công
        if ($response->successful()) {
            // Lưu token mới vào DB
            $token->update([
                'access_token' => $response['access_token'],
                'refresh_token' => $response['refresh_token'],
                'expires_at' => Carbon::now()->addSeconds($response['expires_in']),
            ]);

            return true;
        }

        return false;
    }

    public static function callApi($action, $payload = []) {
        try {
            $domain = env('BITRIX_DOMAIN');
            $response = Http::get("https://{$domain}/rest/{$action}", $payload);

            if ($response->successful()) {

                $data = $response->json();
                return $data;

            } elseif ($response->failed()) {

                $statusCode = $response->status();
                $errorBody = json_decode($response->body(), true)['error'] ?? 'Unknown error';

                if ($statusCode === 401 && $errorBody === 'expired_token') {
                    if (self::renewToken()) {
                        $token = BitrixToken::query()->first();

                        $payload['auth'] = $token->access_token;

                        // Gọi lại hàm callApi với cùng action và payload
                        return self::callApi($action, $payload);
                    } else {
                        return response()->json(['error' => 'Không thể làm mới token'], 401);
                    }
                } else {
                    return response()->json(['error' => $errorBody], $statusCode);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}
