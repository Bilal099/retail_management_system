<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MerchantController; 
use App\Http\Controllers\SupplierController; 

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthController::class,'login']);
Route::group(['middleware' => ['jwt.verify']], function ($router) {

    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

    Route::controller(MerchantController::class)->prefix('merchant')->group(function () {
        Route::get('/list/{page}/{limit?}', 'index');
        Route::post('/store', 'store');
        Route::get('/show/{id}', 'show');
        Route::post('/update/{id}','update');
        Route::delete('/delete/{id}','destroy');
    });
    
    Route::controller(SupplierController::class)->prefix('merchant')->group(function () {
        Route::get('/list/{page}/{limit?}', 'index');
        Route::post('/store', 'store');
        Route::get('/show/{id}', 'show');
        Route::post('/update/{id}','update');
        Route::delete('/delete/{id}','destroy');
    });

});