<?php

namespace App\Http\Controllers;

use App\Models\BitrixToken;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    public function getToken(Request $request){

        $data = $request->all();
        $token = BitrixToken::create([
            'domain' => env('BITRIX_DOMAIN'),
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'expires_at' => Carbon::now()->addSeconds($data['expires_in']),
        ]);

        return $token->access_token;
    }

    public function handleInstall(Request $request)
    {
        $data = $request->all();

        if ($data['event'] === 'ONAPPINSTALL') {
            $domain = $data['auth']['domain'];
            $accessToken = $data['auth']['access_token'];
            $refreshToken = $data['auth']['refresh_token'];
            $applicationToken = $data['auth']['application_token'];
            $expiresIn = $data['auth']['expires_in'];

            BitrixToken::updateOrCreate(
                ['domain' => $domain],
                [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'application_token' => $applicationToken,
                    'expires_at' => Carbon::now()->addSeconds($expiresIn)
                ]
            );
        }

        // return response()->json(['status' => 'success']);
    }
}
