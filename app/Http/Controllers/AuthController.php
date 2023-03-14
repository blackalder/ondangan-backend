<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function index(Request $request){
        $data = Auth::user();
        // return response()->json($data);
        return response($data);
    }

    public function register(Request $request){
        $data = $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()
            ]
        ]);

        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $this->createToken($user->id)
        ]);
    }
    
    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required'
            ],
            'remember' => 'boolean'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(!Hash::check($validated['password'], $user->password)){
            return abort(401, "email salah");
        }

        
        return response([
            'user' => $user,
            'token' => $this->createToken($user->id)
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }
    private function createToken($uid){
        $payload = [
            'iat' => intval(microtime(true)),
            'exp' => intval(microtime(true)) + (60 * 60 * 60),
            'uid' => $uid
        ];
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
}
