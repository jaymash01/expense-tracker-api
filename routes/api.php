<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExpensesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('/auth')->group(function ($router) {
    $router->post('/create-account', 'createAccount');
    $router->post('/login', 'login');
});

Route::middleware(['auth:api'])->group(function ($router) {
    $router->controller(AuthController::class)->prefix('/auth')->group(function ($router) {
        $router->post('/update-account', 'updateAccount');
        $router->get('/user', 'getAuthUser');
        $router->post('/change-password', 'changePassword');
        $router->delete('/delete-account', 'deleteAccount');
    });

    $router->get('/dashboard', DashboardController::class);
    $router->apiResource('/categories', CategoriesController::class);
    $router->apiResource('/expenses', ExpensesController::class);
});
