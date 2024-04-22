<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TaskController;
use App\Middlewares\AuthMiddleware;
use App\Controllers\CategoryController;
use App\Middlewares\GuestMiddleware;
use Slim\Routing\RouteCollectorProxy;

return function(App $app) {
    $app->get('/', [HomeController::class, 'index'])->add(AuthMiddleware::class);
    
    $app->group('', function (RouteCollectorProxy $guest) {
        $guest->get('/login', [AuthController::class, 'loginView']);
        $guest->get('/signup', [AuthController::class, 'signupView']);
        $guest->post('/login', [AuthController::class, 'login']);
        $guest->post('/signup', [AuthController::class, 'signup']);
    })->add(GuestMiddleware::class);
    
    $app->post('/logout', [AuthController::class, 'logout'])->add(AuthMiddleware::class);

    $app->group('/categories', function (RouteCollectorProxy $categories) {
        $categories->get('', [CategoryController::class, 'index']);
        $categories->get('/all', [CategoryController::class, 'retrieveAll']);
        $categories->get('/{id:[0-9]+}', [CategoryController::class, 'retrieve']);
        $categories->post('', [CategoryController::class, 'new']);
        $categories->delete('/{id:[0-9]+}', [CategoryController::class, 'remove']);
        $categories->put('/{id:[0-9]+}', [CategoryController::class, 'update']);
    })->add(AuthMiddleware::class);

    $app->group('/tasks', function (RouteCollectorProxy $task) {
        $task->get('', [TaskController::class, 'index']);
        $task->post('', [TaskController::class, 'new']);
        $task->get('/all', [TaskController::class, 'retrieveAll']);
        $task->get('/load', [TaskController::class, 'retrieveForTable']);
        $task->get('/{id:[0-9]+}', [TaskController::class, 'retrieve']);
        $task->delete('/{id:[0-9]+}', [TaskController::class, 'remove']);
    })->add(AuthMiddleware::class);
};