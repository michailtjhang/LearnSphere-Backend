<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\CourseController;
use App\Http\Controllers\front\AccountController;
use App\Http\Controllers\front\OutcomeController;


Route::post('/register', [AccountController::class, 'register']);
Route::post('/login', [AccountController::class, 'authenticate']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/meta-data', [CourseController::class, 'metaData']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);

    // Outcome routes
    Route::get('/outcome', [OutcomeController::class, 'index']);
    Route::post('/outcome', [OutcomeController::class, 'store']);
    Route::put('/outcome/{id}', [OutcomeController::class, 'update']);
    Route::delete('/outcome/{id}', [OutcomeController::class, 'destroy']);
});
