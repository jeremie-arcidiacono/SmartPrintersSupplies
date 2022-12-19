<?php

use App\Http\Controllers\UserController;
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

    Route::get('/{printer}/detail', function ($printer) {
        return view('printer', ['idPrinter' => $printer]);
    })->middleware(['auth'])->name('printers.show');

    Route::get('/create', function () {
        return view('forms.printer');
    })->middleware(['auth'])->name('printers.create');

    Route::get('/{printer}/edit', function ($printer) {
        return view('forms.printer', ['idPrinter' => $printer]);
    })->middleware(['auth'])->name('printers.update');
});

Route::prefix('models')->group(function () {
    Route::get('/', function () {
        return view('tables.models');
    })->middleware(['auth'])->name('models.index');

    Route::get('/create', function () {
        return view('forms.model');
    })->middleware(['auth'])->name('models.create');

    Route::get('/{printerModel}/edit', function ($printerModel) {
        return view('forms.model', ['idPrinterModel' => $printerModel]);
    })->middleware(['auth'])->name('models.update');
});

Route::prefix('supplies')->group(function () {
    Route::get('/', function () {
        return view('tables.supplies');
    })->middleware(['auth'])->name('supplies.index');

    Route::get('/{supply}/detail', function ($supply) {
        return view('supply', ['idSupply' => $supply]);
    })->middleware(['auth'])->name('supply.show');

    Route::get('/create', function () {
        return view('forms.supply');
    })->middleware(['auth'])->name('supplies.create');

    Route::get('/{supply}/edit', function ($supply) {
        return view('forms.supply', ['idSupply' => $supply]);
    })->middleware(['auth'])->name('supplies.update');
});

Route::get('/events/consumption', function () {
    return view('tables.events.consumption');
})->middleware(['auth'])->name('events.consumption');

Route::get('/events/object', function () {
    return view('events.object');
})->middleware(['auth'])->name('events.object');

Route::prefix('statistics')->group(function () {
    Route::get('/mostActivePrinters', function () {
        return view('statistics.mostActivePrinters');
    })->middleware(['auth'])->name('statistics.mostActivePrinters');
});

Route::get('/users', [UserController::class, 'index'])->middleware(['auth'])->name('users.index');
Route::patch('/users/{user}/toggleStatus', [UserController::class, 'toggleStatus'])->middleware(['auth'])->name('users.toggleStatus');

Route::get('/users/create', function () {
    return redirect()->route('register');
})->middleware(['auth'])->name('users.create');


require __DIR__ . '/auth.php';
