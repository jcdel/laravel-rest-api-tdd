<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
             'name'     => $request->name,
             'email'    => $request->email,
             'password' => bcrypt($request->password),
         ]);

        $token = auth()->login($user);

        return response()->json($user, 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Email or Password'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_at' => Carbon::now()->addWeeks(1)->toDateTimeString()
        ], 200);
    }

    public function logout()
    {
        if(!auth()->user())

            return response()->json([
                'message' => 'Unable to Logout'
            ], 401);

        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
}
}