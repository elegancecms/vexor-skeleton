<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Vexor\Core\Auth\AuthManager;
use Vexor\Core\Security\SecurityManager;
use Vexor\Core\Exceptions\AuthException;
use Vexor\Core\Exceptions\ValidationException;

/**
 * AuthService
 * 
 * Auth iş mantığını controller'dan ayırır.
 * Hybrid MVC + Service Layer mimarisi gereği
 * tüm auth logic buradadır.
 */
class AuthService
{
    public function __construct(
        private AuthManager $auth,
        private SecurityManager $security
    ) {}

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(array $data): array
    {
        // Email benzersizlik kontrolü
        $exists = User::query()->where('email', $data['email'])->exists();
        if ($exists) {
            throw new ValidationException(['email' => ['Bu e-posta adresi zaten kayıtlı.']]);
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $this->security->hashPassword($data['password']),
            'role'     => 'user',
            'is_active'=> 1,
        ]);

        $token        = $this->auth->generateJwt($user);
        $refreshToken = $this->auth->generateRefreshToken($user);

        return [
            'user'          => $user->toArray(),
            'access_token'  => $token,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => 3600,
        ];
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(string $email, string $password, string $ip): array
    {
        // Rate limit: aynı IP'den 5 başarısız denemeden sonra kilitle
        if (!$this->security->rateLimit("login_fail:{$ip}", 5, 60)) {
            throw new AuthException('Çok fazla başarısız giriş denemesi. 1 dakika bekleyin.', 429);
        }

        $user = User::findByEmail($email);

        if (!$user || !$this->security->verifyPassword($password, $user->password)) {
            throw new AuthException('E-posta veya şifre hatalı.', 401);
        }

        if (!(bool)$user->is_active) {
            throw new AuthException('Hesabınız devre dışı bırakılmış.', 403);
        }

        // Şifre rehash gerekiyorsa güncelle
        if ($this->security->needsRehash($user->password)) {
            $user->password = $this->security->hashPassword($password);
        }

        // Son giriş bilgilerini güncelle
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->last_login_ip = $ip;
        $user->save();

        $token        = $this->auth->generateJwt($user);
        $refreshToken = $this->auth->generateRefreshToken($user);

        return [
            'user'          => $user->toArray(),
            'access_token'  => $token,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => 3600,
        ];
    }

    // ── Token Refresh ─────────────────────────────────────────────────────────

    public function refresh(string $refreshToken): array
    {
        $data = $this->auth->validateJwt($refreshToken);

        if (!$data) {
            throw new AuthException('Geçersiz veya süresi dolmuş token.', 401);
        }

        $user = User::find($data['sub']);

        if (!$user || !(bool)$user->is_active) {
            throw new AuthException('Kullanıcı bulunamadı.', 401);
        }

        $newToken        = $this->auth->generateJwt($user);
        $newRefreshToken = $this->auth->generateRefreshToken($user);

        return [
            'access_token'  => $newToken,
            'refresh_token' => $newRefreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => 3600,
        ];
    }

    // ── Forgot Password ───────────────────────────────────────────────────────

    public function forgotPassword(string $email): string
    {
        $user = User::findByEmail($email);

        // Güvenlik: kullanıcı yoksa da aynı mesajı ver
        if (!$user) {
            return 'Eğer bu e-posta kayıtlıysa sıfırlama bağlantısı gönderildi.';
        }

        $token = $this->auth->generatePasswordResetToken($user);

        // TODO: Mail gönder
        // Mail::send('password-reset', ['token' => $token, 'user' => $user]);

        return 'Eğer bu e-posta kayıtlıysa sıfırlama bağlantısı gönderildi.';
    }

    // ── Reset Password ────────────────────────────────────────────────────────

    public function resetPassword(string $token, string $email, string $newPassword): void
    {
        $success = $this->auth->resetPassword($token, $email, $newPassword);

        if (!$success) {
            throw new AuthException('Geçersiz veya süresi dolmuş sıfırlama bağlantısı.', 400);
        }
    }

    // ── Change Password ───────────────────────────────────────────────────────

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!$this->security->verifyPassword($currentPassword, $user->password)) {
            throw new AuthException('Mevcut şifre hatalı.', 400);
        }

        $user->password = $this->security->hashPassword($newPassword);
        $user->save();
    }
}
