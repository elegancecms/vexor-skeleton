<?php

declare(strict_types=1);

namespace App\Models;

use Vexor\ORM\Model;

class PersonalAccessToken extends Model
{
    protected static string $table = 'personal_access_tokens';

    protected array $fillable = [
        'user_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    protected array $casts = [
        'abilities' => 'array',
    ];

    public function user(): ?User
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isExpired(): bool
    {
        if ($this->expires_at === null) return false;
        return strtotime($this->expires_at) < time();
    }

    public function can(string $ability): bool
    {
        $abilities = $this->abilities ?? [];
        return in_array('*', $abilities) || in_array($ability, $abilities);
    }
}
