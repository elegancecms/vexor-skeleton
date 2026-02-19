<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use Vexor\Http\Controller;
use Vexor\Http\Request;
use Vexor\Http\Response;
use Vexor\Application;
use Vexor\Auth\AuthManager;
use Vexor\Exceptions\AuthException;
use Vexor\Exceptions\ValidationException;

class AuthController extends Controller
{
    private AuthService $authService;
    private AuthManager $auth;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->auth        = $app->make(AuthManager::class);
        $this->authService = $app->make(AuthService::class);
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function registerForm(Request $request): Response
    {
        return $this->view('auth/register');
    }

    public function register(Request $request): Response
    {
        try {
            $data = $this->validate($request, [
                'name'                  => 'required|min:2|max:100',
                'email'                 => 'required|email|max:255',
                'password'              => 'required|min:8|confirmed',
            ]);

            $result = $this->authService->register($data);

            // Session'a al
            $this->auth->login($result['user'] instanceof \App\Models\User
                ? $result['user']
                : \App\Models\User::find($result['user']['id'])
            );

            return $this->redirect('/dashboard');

        } catch (ValidationException $e) {
            return $this->view('auth/register', [
                'errors' => $e->getErrors(),
                'old'    => $request->only('name', 'email'),
            ]);
        }
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function loginForm(Request $request): Response
    {
        if ($this->auth->check()) {
            return $this->redirect('/dashboard');
        }
        return $this->view('auth/login');
    }

    public function login(Request $request): Response
    {
        try {
            $data = $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $remember = (bool) $request->input('remember', false);
            $result   = $this->authService->login($data['email'], $data['password'], $request->ip());

            $user = \App\Models\User::findByEmail($data['email']);
            $this->auth->login($user, $remember);

            return $this->redirect('/dashboard');

        } catch (AuthException $e) {
            return $this->view('auth/login', [
                'error' => $e->getMessage(),
                'old'   => $request->only('email'),
            ]);
        } catch (ValidationException $e) {
            return $this->view('auth/login', [
                'errors' => $e->getErrors(),
                'old'    => $request->only('email'),
            ]);
        }
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): Response
    {
        $this->auth->logout();
        return $this->redirect('/login');
    }

    // ── Forgot Password ───────────────────────────────────────────────────────

    public function forgotForm(Request $request): Response
    {
        return $this->view('auth/forgot-password');
    }

    public function forgot(Request $request): Response
    {
        try {
            $data    = $this->validate($request, ['email' => 'required|email']);
            $message = $this->authService->forgotPassword($data['email']);

            return $this->view('auth/forgot-password', ['success' => $message]);
        } catch (ValidationException $e) {
            return $this->view('auth/forgot-password', ['errors' => $e->getErrors()]);
        }
    }

    // ── Reset Password ────────────────────────────────────────────────────────

    public function resetForm(Request $request): Response
    {
        return $this->view('auth/reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request): Response
    {
        try {
            $data = $this->validate($request, [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $this->authService->resetPassword($data['token'], $data['email'], $data['password']);

            return $this->view('auth/login', ['success' => 'Şifreniz başarıyla güncellendi. Giriş yapabilirsiniz.']);

        } catch (AuthException|ValidationException $e) {
            return $this->view('auth/reset-password', ['error' => $e->getMessage()]);
        }
    }
}
