<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TaskController;
use App\Middlewares\AuthMiddleware;
use App\Controllers\CategoryController;
use App\Controllers\MailController;
use App\Controllers\UserController;
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

    $app->get('/user/account', [UserController::class, 'index'])->add(AuthMiddleware::class);
    $app->post('/user/account/contact_person', [UserController::class, 'setContactPerson'])->add(AuthMiddleware::class);
    
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
        $task->put('/{id:[0-9]+}', [TaskController::class, 'update']);
        $task->put('/priority/set/{id:[0-9]+}', [TaskController::class, 'setPriority']);
    })->add(AuthMiddleware::class);

    $app->get('/mail', [MailController::class, 'test'])->add(AuthMiddleware::class);
};