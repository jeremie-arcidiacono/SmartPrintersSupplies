<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PrinterController;
use App\Http\Controllers\api\PrinterModelController;
use App\Http\Controllers\api\SupplyController;
use App\Http\Controllers\api\EventController;

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

// Printer routes
Route::get('/printers', [PrinterController::class, 'index'])->name('api.printers.index');
Route::get('/printers/{printer}', [PrinterController::class, 'show'])->name('api.printers.show');
Route::post('/printers', [PrinterController::class, 'store'])->name('api.printers.store');
Route::put('/printers/{printer}', [PrinterController::class, 'update'])->name('api.printers.update');
Route::delete('/printers/{printer}', [PrinterController::class, 'destroy'])->name('api.printers.destroy')->withTrashed();
Route::get('/printers/{printer}/events', [PrinterController::class, 'events'])->name('api.printers.events');

// Model routes
Route::get('/models', [PrinterModelController::class, 'index'])->name('api.models.index');
Route::get('/models/{printerModel}', [PrinterModelController::class, 'show'])->name('api.models.show');
Route::post('/models', [PrinterModelController::class, 'store'])->name('api.models.store');
Route::put('/models/{printerModel}', [PrinterModelController::class, 'update'])->name('api.models.update');
Route::delete('/models/{printerModel}', [PrinterModelController::class, 'destroy'])->name('api.models.destroy')->withTrashed();

// Supply routes
Route::get('/supplies', [SupplyController::class, 'index'])->name('api.supplies.index');
Route::get('/supplies/{supply}', [SupplyController::class, 'show'])->name('api.supplies.show');
Route::post('/supplies', [SupplyController::class, 'store'])->name('api.supplies.store');
Route::put('/supplies/{supply}', [SupplyController::class, 'update'])->name('api.supplies.update');
Route::delete('/supplies/{supply}', [SupplyController::class, 'destroy'])->name('api.supplies.destroy')->withTrashed();
Route::get('/supplies/{supply}/stockHistory', [SupplyController::class, 'indexStockHistory'])->name('api.supplies.stockHistory');

// Compatibility model-supply routes
Route::get('/supplies/{supply}/compatibilities', [SupplyController::class, 'indexCompatibility'])->name('api.supplies.indexCompatibility');
Route::get('/models/{printerModel}/compatibilities', [PrinterModelController::class, 'indexCompatibility'])->name('api.models.indexCompatibility');
Route::post('/models/{printerModel}/compatibilities/', [PrinterModelController::class, 'storeCompatibility'])->name('api.models.storeCompatibility');
Route::delete('/models/{printerModel}/compatibilities/{supply}', [PrinterModelController::class, 'destroyCompatibility'])->name('api.models.destroyCompatibility');

// Event routes
Route::get('/events', [EventController::class, 'index'])->name('api.events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('api.events.show');

