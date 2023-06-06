<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [ProductController::class, 'register']);
Route::put('update', [ProductController::class, 'update']);
Route::delete('delete/{id}', [ProductController::class, 'delete']);
Route::get('getbyid/{id}', [ProductController::class, 'getById']);
Route::post('getproduct', [ProductController::class, 'getProduct']);

Route::get('importproduct/{oid}', [ProductController::class, 'importProduct']);
