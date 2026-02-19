<?php

return [
    'model'       => \App\Models\User::class,
    'jwt_ttl'     => (int) env('JWT_TTL', 3600),
    'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 2592000),
    'rate_limit'  => (int) env('AUTH_RATE_LIMIT', 5),
    'two_factor'  => (bool) env('AUTH_2FA', false),
];
