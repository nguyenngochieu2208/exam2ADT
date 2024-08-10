<?php

namespace App\Http\Controllers;

use App\Models\BitrixToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
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
        // return redirect()->route('index')->with('success','App installed successfully');
    }

    public function renewToken(Request $request)
    {
        $token = BitrixToken::where('domain', $request->domain)->first();
        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }

        $response = Http::post('https://oauth.bitrix.info/oauth/token/', [
            'grant_type' => 'refresh_token',
            'client_id' => config('services.bitrix.client_id'),
            'client_secret' => config('services.bitrix.client_secret'),
            'refresh_token' => $token->refresh_token,
        ]);

        if ($response->successful()) {
            $token->update([
                'access_token' => $response['access_token'],
                'refresh_token' => $response['refresh_token'],
                'expires_at' => Carbon::now()->addSeconds($response['expires_in']),
            ]);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['error' => 'Failed to renew token'], 400);
    }

    public function callApi(Request $request)
    {
        $token = BitrixToken::where('domain', $request->domain)->first();
        if (!$token || $token->expires_at->isPast()) {
            return response()->json(['error' => 'Token expired or not found'], 401);
        }

        $response = Http::withToken($token->access_token)
            ->post($request->input('url'), $request->input('payload', []));

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to call API', 'details' => $response->json()], 500);
        }

        return response()->json($response->json());
    }
}
