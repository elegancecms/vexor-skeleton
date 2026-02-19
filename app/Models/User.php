<?php

declare(strict_types=1);

namespace App\Models;

use Vexor\ORM\Model;

class User extends Model
{
    protected static string $table = 'users';

    protected array $fillable = [
        'name',
        'email',
        'password',
        'role',
        'api_key',
        'remember_token',
        'two_factor_secret',
        'two_factor_enabled',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'is_active',
    ];

    protected array $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'api_key',
        'password_reset_token',
        'password_reset_expiry',
    ];

    protected array $casts = [
        'two_factor_enabled' => 'boolean',
        'is_active'          => 'boolean',
    ];

    // ── Rol Kontrolleri ───────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ── Email Doğrulama ───────────────────────────────────────────────────────

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // ── 2FA ───────────────────────────────────────────────────────────────────

    public function hasTwoFactorEnabled(): bool
    {
        return (bool) $this->two_factor_enabled;
    }

    // ── İlişkiler ─────────────────────────────────────────────────────────────

    public function tokens(): array
    {
        return $this->hasMany(PersonalAccessToken::class, 'user_id');
    }
}
