<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/companies', [CompanyController::class, 'create']);
Route::put('/companies/{id}', [CompanyController::class, 'update']);



Route::middleware('auth:api')->put('/user', [UserController::class, 'update']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->put('/user/update-password', [UserController::class, 'updatePassword']);
Route::middleware('auth:sanctum')->get('/company-info', [CompanyController::class, 'showCompanyInfo']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
