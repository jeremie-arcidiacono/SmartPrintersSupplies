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


Route::get('/printers', [PrinterController::class, 'index'])->name('printers.index');
Route::get('/printers/{printer}', [PrinterController::class, 'show'])->name('printers.show');
Route::post('/printers', [PrinterController::class, 'store'])->name('printers.store');
Route::put('/printers/{printer}', [PrinterController::class, 'update'])->name('printers.update');
Route::delete('/printers/{printer}', [PrinterController::class, 'destroy'])->name('printers.destroy')->withTrashed();
Route::get('/printers/{printer}/events', [PrinterController::class, 'events'])->name('printers.events');


Route::get('/models', [PrinterModelController::class, 'index'])->name('models.index');
Route::get('/models/{printerModel}', [PrinterModelController::class, 'show'])->name('models.show');
Route::post('/models', [PrinterModelController::class, 'store'])->name('models.store');
Route::put('/models/{printerModel}', [PrinterModelController::class, 'update'])->name('models.update');
Route::delete('/models/{printerModel}', [PrinterModelController::class, 'destroy'])->name('models.destroy')->withTrashed();

Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
Route::get('/supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');
Route::post('/supplies', [SupplyController::class, 'store'])->name('supplies.store');
Route::put('/supplies/{supply}', [SupplyController::class, 'update'])->name('supplies.update');
Route::delete('/supplies/{supply}', [SupplyController::class, 'destroy'])->name('supplies.destroy')->withTrashed();

Route::get('/supplies/{supply}/compatibilities', [SupplyController::class, 'indexCompatibility'])->name('supplies.indexCompatibility');
Route::get('/models/{printerModel}/compatibilities', [PrinterModelController::class, 'indexCompatibility'])->name('models.indexCompatibility');
Route::post('/models/{printerModel}/compatibilities/', [PrinterModelController::class, 'storeCompatibility'])->name('models.storeCompatibility');
Route::delete('/models/{printerModel}/compatibilities/{supply}', [PrinterModelController::class, 'destroyCompatibility'])->name('models.destroyCompatibility');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

