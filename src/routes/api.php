<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PrinterController;
use App\Http\Controllers\api\PrinterModelController;

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


Route::get('/printers', [PrinterController::class, 'index'])->name('printers.index');
Route::get('/printers/{printer}', [PrinterController::class, 'show'])->name('printers.show');
Route::post('/printers', [PrinterController::class, 'store'])->name('printers.store');
Route::put('/printers/{printer}', [PrinterController::class, 'update'])->name('printers.update');
Route::delete('/printers/{printer}', [PrinterController::class, 'destroy'])->name('printers.destroy')->withTrashed();;


Route::get('/models', [PrinterModelController::class, 'index'])->name('models.index');
Route::get('/models/{printerModel}', [PrinterModelController::class, 'show'])->name('models.show');
Route::post('/models', [PrinterModelController::class, 'store'])->name('models.store');
Route::put('/models/{printerModel}', [PrinterModelController::class, 'update'])->name('models.update');
Route::delete('/models/{printerModel}', [PrinterModelController::class, 'destroy'])->name('models.destroy')->withTrashed();;