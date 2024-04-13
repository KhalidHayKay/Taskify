<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TaskController;
use App\Middlewares\AuthMiddleware;
use App\Controllers\CategoryController;
use App\Middlewares\GuestMiddleware;

return function(App $app) {
    $app->get('/', [HomeController::class, 'index'])->add(AuthMiddleware::class);
    $app->get('/categories', [CategoryController::class, 'index'])->add(AuthMiddleware::class);
    $app->get('/categories/all', [CategoryController::class, 'retrieveAll'])->add(AuthMiddleware::class);
    $app->post('/categories/add', [CategoryController::class, 'new'])->add(AuthMiddleware::class);
    $app->delete('/categories/delete', [CategoryController::class, 'remove'])->add(AuthMiddleware::class);
    $app->put('/categories/update', [CategoryController::class, 'update'])->add(AuthMiddleware::class);
    $app->get('/tasks', [TaskController::class, 'index'])->add(AuthMiddleware::class);

    $app->get('/login', [AuthController::class, 'loginView'])->add(GuestMiddleware::class);
    $app->get('/signup', [AuthController::class, 'signupView'])->add(GuestMiddleware::class);
    $app->post('/login', [AuthController::class, 'login'])->add(GuestMiddleware::class);
    $app->post('/signup', [AuthController::class, 'signup'])->add(GuestMiddleware::class);

    $app->post('/logout', [AuthController::class, 'logout'])->add(AuthMiddleware::class);
};