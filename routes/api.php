<?php

use App\Http\Controllers\Api\NotebookController;
use Illuminate\Http\Request;
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

Route::group([
    'as' => 'api.',
    'prefix' => 'v1',
//    'middleware' => ['auth:api']
], static function () {
    Route::apiResource('notebook', NotebookController::class)
       ->except('update');

    //for sent files as multipart/form-data and populate $_FILES
    Route::post('/notebook/{id}', [NotebookController::class, 'update']);
});
