<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->route('printers.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::prefix('printers')->group(function () {
    Route::get('/', function () {
        return view('tables.printers');
    })->middleware(['auth'])->name('printers.index');

    Route::get('/create', function () {
        return view('forms.printer');
    })->middleware(['auth'])->name('printers.create');

    Route::get('/{printer}/edit', function ($printer) {
        return view('forms.printer', ['idPrinter' => $printer]);
    })->middleware(['auth'])->name('printers.update');
});

Route::get('/models', function () {
    return view('tables.models');
})->middleware(['auth'])->name('models.index');

require __DIR__.'/auth.php';