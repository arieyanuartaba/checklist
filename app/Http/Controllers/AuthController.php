<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'register sucessfully',
                'data' => $user
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'register failed'
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (Hash::check($password, $user->password)){
            $apiToken = base64_encode(str_random(40));

            $user->update([
                'api_token' => $apiToken
            ]);

            return response()->json([
                'success' => true,
                'message' => 'login successfully',
                'data' => [
                    'user' => $user,
                    'api token' => $apiToken
                ]
            ],201);
        }
        else {
            
            return response()->json([
                'success' => false,
                'message' => 'login failed',
            ], 401);
        }
    }
}
