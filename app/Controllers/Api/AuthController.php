<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\AuthService;
use Vexor\Core\Http\Controller;
use Vexor\Core\Http\Request;
use Vexor\Core\Http\Response;
use Vexor\Core\Application;
use Vexor\Core\Auth\AuthManager;
use Vexor\Core\Exceptions\AuthException;
use Vexor\Core\Exceptions\ValidationException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->authService = $app->make(AuthService::class);
    }

    // POST /api/auth/register
    public function register(Request $request): Response
    {
        try {
            $data = $this->validate($request, [
                'name'                  => 'required|min:2|max:100',
                'email'                 => 'required|email|max:255',
                'password'              => 'required|min:8|confirmed',
            ]);

            $result = $this->authService->register($data);
            return $this->success($result, 'Kayıt başarılı.', 201);

        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->getErrors());
        }
    }

    // POST /api/auth/token (login)
    public function token(Request $request): Response
    {
        try {
            $data = $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $result = $this->authService->login($data['email'], $data['password'], $request->ip());
            return $this->success($result, 'Giriş başarılı.');

        } catch (AuthException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 401);
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->getErrors());
        }
    }

    // POST /api/auth/refresh
    public function refresh(Request $request): Response
    {
        try {
            $refreshToken = $request->input('refresh_token') ?? $request->bearerToken();

            if (!$refreshToken) {
                return $this->error('Refresh token gerekli.', 400);
            }

            $result = $this->authService->refresh($refreshToken);
            return $this->success($result, 'Token yenilendi.');

        } catch (AuthException $e) {
            return $this->error($e->getMessage(), 401);
        }
    }

    // POST /api/auth/forgot-password
    public function forgotPassword(Request $request): Response
    {
        try {
            $data    = $this->validate($request, ['email' => 'required|email']);
            $message = $this->authService->forgotPassword($data['email']);
            return $this->success(message: $message);
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->getErrors());
        }
    }

    // POST /api/auth/reset-password
    public function resetPassword(Request $request): Response
    {
        try {
            $data = $this->validate($request, [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $this->authService->resetPassword($data['token'], $data['email'], $data['password']);
            return $this->success(message: 'Şifre başarıyla güncellendi.');

        } catch (AuthException $e) {
            return $this->error($e->getMessage(), 400);
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->getErrors());
        }
    }

    // POST /api/auth/logout (JWT stateless, client token'ı siler)
    public function logout(Request $request): Response
    {
        // JWT stateless — client tarafında token silinir
        // Blacklist için personal_access_tokens tablosu kullanılabilir
        return $this->success(message: 'Çıkış yapıldı.');
    }
}
