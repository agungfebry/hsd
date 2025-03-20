<?php

use Illuminate\Http\Request;
use App\Http\Controllers\APIs as Backend;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('categories')->group(function($router){
    $router->get('/', [Backend\CategoryController::class, 'index']);
    $router->post('/', [Backend\CategoryController::class, 'create']);
});

Route::prefix('products')->group(function($router){
    $router->patch('/{id}', [Backend\ProductController::class, 'update']);
    $router->post('', [Backend\ProductController::class, 'store']);
    $router->get('', [Backend\ProductController::class, 'index']);
    $router->get('/{id}', [Backend\ProductController::class, 'show']);
    $router->delete('/{id}', [Backend\ProductController::class, 'delete']);
});

