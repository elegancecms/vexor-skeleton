<?php

use Vexor\Core\Http\Request;
use Vexor\Core\Http\Response;

/** @var \Vexor\Core\Router\Router $router */

$router->get('/', function (Request $request): Response {
    return Response::json([
        'framework' => 'Vexor',
        'version'   => '1.0.0',
        'status'    => 'running',
        'message'   => '⚡ Welcome to Vexor Framework',
    ]);
});

$router->get('/health', function (Request $request): Response {
    return Response::json([
        'status'    => 'healthy',
        'timestamp' => date('Y-m-d H:i:s'),
    ]);
});

$router->post('/login',    'App\Controllers\AuthController@login');
$router->post('/logout',   'App\Controllers\AuthController@logout');
$router->post('/register', 'App\Controllers\AuthController@register');

$router->middleware('Vexor\Core\Http\Middleware\AuthMiddleware')->group(function ($r) {
    $r->get('/dashboard', function (Request $request): Response {
        return Response::json([
            'message' => 'Welcome!',
            'user'    => $request->getAttribute('user'),
        ]);
    });
});
