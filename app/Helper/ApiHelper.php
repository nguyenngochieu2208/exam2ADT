<?php

namespace App\Helper;

use App\Models\BitrixToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ApiHelper
{
    // hàm này trả về url để lấy token từ Bitrix24
    public static function getAccessToken(){
        $domain = env('BITRIX_DOMAIN');
        $clientId = config('services.bitrix.client_id');

        $authUrl = "http://{$domain}/oauth/authorize/?client_id={$clientId}&response_type=code";

        return $authUrl;
    }

    // Yêu cầu token mới từ Bitrix24 khi token hết hạn
    public static function renewToken()
    {
        // Lấy token từ DB
        $token = BitrixToken::query()->first();

        // Gửi request lên Bitrix24 để lấy token mới
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

    // hàm call api tới Bitrix24
    public static function callApi($action, $payload = []) {
        $domain = env('BITRIX_DOMAIN');

        $timeout = 10;
        $retry = 3;

        while ($retry > 0) {
            try {
                $response = Http::timeout($timeout)->get("https://{$domain}/rest/{$action}", $payload);

                if ($response->successful()) {
                    // Trường hợp gọi API thành công
                    return $response->json();

                } elseif ($response->failed()) {
                    // Trường hợp gọi thất bại
                    $statusCode = $response->status();
                    $errorBody = json_decode($response->body(), true)['error'] ?? 'Unknown error';

                    if ($statusCode === 401 && $errorBody === 'expired_token') {
                        // Xử lý khi token hết hạn

                        if (self::renewToken()) {
                            $token = BitrixToken::query()->first();
                            $payload['auth'] = $token->access_token;

                            continue;
                        } else {
                            return ['error' => 'Không thể làm mới token'];
                        }
                    } elseif ($statusCode === 408) {
                        // Xử lý lỗi timeout

                        $retry--;
                        if ($retry <= 0) {
                            return ['error' => 'Lỗi timeout sau khi thử lại'];
                        }

                        sleep(1);
                    } else {
                        return [['error' => $errorBody], $statusCode];
                    }
                }
            } catch (\Exception $e) {
                return ['error' => 'Lỗi: ' . $e->getMessage()];
            }
        }

        return ['error' => 'Lỗi không xác định'];
    }

}
