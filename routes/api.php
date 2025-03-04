<?php

use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TimesheetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'api'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    

    Route::resource('projects', ProjectController::class);
    Route::get('my-projects', [ProjectController::class, 'my_projects']);
    Route::post('project/{project}/assign-me', [ProjectController::class, 'assign_me']);
    Route::resource('timesheets', TimesheetController::class);
    Route::resource('attributes', AttributeController::class);
});
