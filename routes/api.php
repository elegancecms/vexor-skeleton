<?php

use Vexor\Core\Http\Request;
use Vexor\Core\Http\Response;

/** @var \Vexor\Core\Router\Router $router */

$router->prefix('/api')->group(function ($r) {

    $r->get('/ping', fn(Request $req) => Response::json(['pong' => true]));

    $r->post('/auth/token',   'App\Controllers\Api\AuthController@token');
    $r->post('/auth/refresh', 'App\Controllers\Api\AuthController@refresh');

    $r->middleware([
        'Vexor\Core\Http\Middleware\AuthMiddleware',
        'Vexor\Core\Http\Middleware\RateLimitMiddleware',
    ])->group(function ($r) {
        $r->apiResource('users', 'App\Controllers\Api\UserController');
        $r->get('/me', 'App\Controllers\Api\UserController@me');
    });

});
