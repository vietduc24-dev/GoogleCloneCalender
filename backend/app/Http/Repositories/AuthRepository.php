<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;
        
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('Logout attempted without authenticated user');
                return false;
            }

            // Delete the current access token
            $user->currentAccessToken()->delete();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Logout failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
} 