<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GudangController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return 'not found';
});

Route::post('/api/gudang_insert', [GudangController::class, 'insert']);
Route::get('/api/gudang_all', [GudangController::class, 'get']);
Route::get('/api/gudang_by_id/{id}', [GudangController::class, 'detail']);
Route::put('/api/gudang_update', [GudangController::class, 'update']);
Route::delete('/api/gudang_delete', [GudangController::class, 'delete']);