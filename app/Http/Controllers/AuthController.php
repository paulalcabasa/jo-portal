<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('jwt', ['except' => ['login','refresh']]);
    }

    public function login(Request $request) {

        $user = User::where([
            'username' => $request->employee_no,
            'password' => $request->password
        ])->first();

        if(empty($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = JWTAuth::fromUser($user);
        
        return response()->json([
            'accessToken' => $token,
            'userData'    => $user
        ]);
    }

    public function refresh_token() {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        $user  = JWTAuth::setToken($token)->toUser();

        return response()->json([
            'accessToken' => $token,
            'userData'    => $user
        ]);
    }

    public function me()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
