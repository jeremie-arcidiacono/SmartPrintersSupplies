<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PrinterController;

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
