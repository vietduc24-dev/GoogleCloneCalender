<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Contracts\AuthRepositoryInterface;
use App\Http\Request\RegisterRequest;
use App\Http\Request\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\Auth\AuthenticationException;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authRepository->register($request->validated());
            
            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new AuthenticationException(
                'Validation failed',
                $e->errors(),
                422
            );
        } catch (\Exception $e) {
            Log::error('Registration failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new AuthenticationException(
                'Registration failed',
                ['general' => ['An unexpected error occurred during registration']],
                500
            );
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authRepository->login($request->validated());
            
            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tài khoản hoặc mật khẩu không đúng'
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'user' => $result['user'],
                'token' => $result['token']
            ]);
        } catch (\Exception $e) {
            Log::error('Login failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra khi đăng nhập'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $result = $this->authRepository->logout();
            
            if (!$result) {
                throw new AuthenticationException(
                    'Logout failed',
                    ['general' => ['Unable to logout user']],
                    401
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($e instanceof AuthenticationException) {
                throw $e;
            }

            throw new AuthenticationException(
                'Logout failed',
                ['general' => ['An unexpected error occurred during logout']],
                500
            );
        }
    }
}
