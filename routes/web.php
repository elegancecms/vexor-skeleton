<?php

use Vexor\Http\Request;
use Vexor\Http\Response;

/** @var \Vexor\Router\Router $router */

// ── Ana Sayfa ─────────────────────────────────────────────────────────────────

$router->get('/', function (Request $request): Response {
    return Response::json(['framework' => 'Vexor', 'version' => '1.0.0', 'status' => 'running']);
});

// ── Auth (Guest) ──────────────────────────────────────────────────────────────

$router->get('/login',            'App\Controllers\AuthController@loginForm');
$router->post('/login',           'App\Controllers\AuthController@login');
$router->get('/register',         'App\Controllers\AuthController@registerForm');
$router->post('/register',        'App\Controllers\AuthController@register');
$router->get('/forgot-password',  'App\Controllers\AuthController@forgotForm');
$router->post('/forgot-password', 'App\Controllers\AuthController@forgot');
$router->get('/reset-password',   'App\Controllers\AuthController@resetForm');
$router->post('/reset-password',  'App\Controllers\AuthController@reset');
$router->post('/logout',          'App\Controllers\AuthController@logout');

// ── Auth (Protected) ──────────────────────────────────────────────────────────

$router->middleware('Vexor\Core\Http\Middleware\AuthMiddleware')->group(function ($r) {

    $r->get('/dashboard', function (Request $request): Response {
        $user = $request->getAttribute('user');
        return Response::json(['message' => 'Hoş geldiniz!', 'user' => $user->toArray()]);
    });

    $r->get('/profile', 'App\Controllers\ProfileController@show');
    $r->put('/profile', 'App\Controllers\ProfileController@update');

});
