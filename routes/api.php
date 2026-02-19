<?php

use Vexor\Core\Http\Request;
use Vexor\Core\Http\Response;

/** @var \Vexor\Core\Router\Router $router */

$router->prefix('/api')->group(function ($r) {

    // Health
    $r->get('/ping', fn(Request $req) => Response::json(['pong' => true]));

    // Auth (public)
    $r->post('/auth/register',        'App\Controllers\Api\AuthController@register');
    $r->post('/auth/token',           'App\Controllers\Api\AuthController@token');
    $r->post('/auth/refresh',         'App\Controllers\Api\AuthController@refresh');
    $r->post('/auth/forgot-password', 'App\Controllers\Api\AuthController@forgotPassword');
    $r->post('/auth/reset-password',  'App\Controllers\Api\AuthController@resetPassword');

    // Protected
    $r->middleware([
        'Vexor\Core\Http\Middleware\AuthMiddleware',
        'Vexor\Core\Http\Middleware\RateLimitMiddleware',
    ])->group(function ($r) {

        $r->post('/auth/logout',  'App\Controllers\Api\AuthController@logout');
        $r->get('/me',            'App\Controllers\Api\UserController@me');
        $r->post('/me/password',  'App\Controllers\Api\UserController@changePassword');

        // Users (admin)
        $r->apiResource('users', 'App\Controllers\Api\UserController');

    });

});
