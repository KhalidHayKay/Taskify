<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TaskController;
use App\Controllers\CategoryController;
use App\Controllers\ContactPersonController;
use App\Controllers\MailController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\InvalidContactPersonDataExceptionMiddleware;
use App\Middlewares\SignatureValidationMiddleware;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->add(AuthMiddleware::class);

    $app->group('', function (RouteCollectorProxy $guest) {
        $guest->get('/login', [AuthController::class, 'loginView']);
        $guest->get('/signup', [AuthController::class, 'signupView']);
        $guest->post('/login', [AuthController::class, 'login']);
        $guest->post('/signup', [AuthController::class, 'signup']);
    })->add(GuestMiddleware::class);

    $app->post('/logout', [AuthController::class, 'logout'])->add(AuthMiddleware::class);

    $app->group('/user/contact_person', function (RouteCollectorProxy $contactPerson) {
        $contactPerson->get('', [ContactPersonController::class, 'index']);
        $contactPerson->get('/create', [ContactPersonController::class, 'createView']);
        $contactPerson->post('/create', [ContactPersonController::class, 'create']);
        $contactPerson->delete('', [ContactPersonController::class, 'delete']);

        $contactPerson->get('/acknowledgement', [ContactPersonController::class, 'checkAcknowledgement']);
        $contactPerson->post('/acknowledgement', [ContactPersonController::class, 'acknowledge']);
    })->add(AuthMiddleware::class)->add(InvalidContactPersonDataExceptionMiddleware::class);

    //todo: Check to see if not adding auth middleware will not be a security risk
    $app->group('/request', function (RouteCollectorProxy $guest) {
        $guest->get('/contact_person/{userId}/{hash}', [ContactPersonController::class, 'request'])
            ->setName('contactPersonRequest')
            ->add(SignatureValidationMiddleware::class);
    });

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

    $app->group('/mail', function (RouteCollectorProxy $mail) {
        $mail->get('', [MailController::class, 'view']);
        $mail->get('/test', [MailController::class, 'test']);
        $mail->get('/test/view', [MailController::class, 'viewTest']);
    })->add(AuthMiddleware::class);
};