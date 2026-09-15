<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password'),
        );

        return ResponseJson::success($result, __('messages.registered'), 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated('email'),
            $request->validated('password'),
        );

        return ResponseJson::success($result, __('messages.logged_in'));
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return ResponseJson::success(null, __('messages.logged_out'));
    }
}
