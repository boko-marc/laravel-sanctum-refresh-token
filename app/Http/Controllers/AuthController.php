<?php

namespace App\Http\Controllers;

use App\Enums\TokenAbility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        $data = ['name' => "Marc BOKO", "password" => "123456789", "email" => "marcboko.uriel@gmail.com"];
        $user = User::create($data);
        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

        return [
            'token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }
    public function login()
    {
    }
    public function refreshToken(Request $request)
    {
        $accessToken = $request->user()->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        return response(['message' => "Token généré", 'token' => $accessToken->plainTextToken]);
    }
}
