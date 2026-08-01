<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturaController;

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
    return redirect()->route('home');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    Route::resource('facturas', App\Http\Controllers\FacturaController::class);
    Route::get('facturas/{id}/pdf', [FacturaController::class, 'pdf'])
        ->name('facturas.pdf');


    Route::resource('clientes', App\Http\Controllers\ClienteController::class);
    Route::delete('clientes/documentos/{documento}', [App\Http\Controllers\ClienteController::class, 'destroyDocumento'])
        ->name('clientes.documentos.destroy');
    Route::post('clientes/{cliente}/camiones', [App\Http\Controllers\ClienteController::class, 'asignarCamion'])
        ->name('clientes.camiones.store');
    Route::delete('clientes/{cliente}/camiones/{camion}', [App\Http\Controllers\ClienteController::class, 'desasignarCamion'])
        ->name('clientes.camiones.destroy');


    Route::resource('empresas', App\Http\Controllers\EmpresaController::class);


    Route::resource('chofers', App\Http\Controllers\ChoferController::class);
    Route::delete('chofers/documentos/{documento}', [App\Http\Controllers\ChoferController::class, 'destroyDocumento'])
        ->name('chofers.documentos.destroy');
    Route::patch('chofers/{chofer}/estado', [App\Http\Controllers\ChoferController::class, 'cambiarEstado'])
        ->name('chofers.estado');


    Route::resource('camions', App\Http\Controllers\CamionController::class);
    Route::delete('camions/documentos/{documento}', [App\Http\Controllers\CamionController::class, 'destroyDocumento'])
        ->name('camions.documentos.destroy');
    Route::patch('camions/{camion}/estado', [App\Http\Controllers\CamionController::class, 'cambiarEstado'])
        ->name('camions.estado');


    Route::resource('productos', App\Http\Controllers\ProductoController::class);
    Route::patch('productos/{producto}/estado', [App\Http\Controllers\ProductoController::class, 'cambiarEstado'])
        ->name('productos.estado');


    Route::resource('proveedors', App\Http\Controllers\ProveedorController::class);


    Route::resource('ordenCargas', App\Http\Controllers\OrdenCargaController::class);
    Route::get('ordenCargas/{id}/pdf', [App\Http\Controllers\OrdenCargaController::class, 'pdf'])
        ->name('ordenCargas.pdf');


    Route::resource('valeCombustibles', App\Http\Controllers\ValeCombustibleController::class);
    Route::get('valeCombustibles/{id}/pdf', [App\Http\Controllers\ValeCombustibleController::class, 'pdf'])
        ->name('valeCombustibles.pdf');


    Route::resource('users', App\Http\Controllers\UserController::class);


    Route::resource('viaticos', App\Http\Controllers\ViaticoController::class);
    Route::get('viaticos/{id}/pdf', [App\Http\Controllers\ViaticoController::class, 'pdf'])
        ->name('viaticos.pdf');
    Route::delete('viaticos/documentos/{documento}', [App\Http\Controllers\ViaticoController::class, 'destroyDocumento'])
        ->name('viaticos.documentos.destroy');


    Route::get('liquidacions', [App\Http\Controllers\LiquidacionController::class, 'index'])
        ->name('liquidacions.index');
    Route::get('liquidacions/create', [App\Http\Controllers\LiquidacionController::class, 'create'])
        ->name('liquidacions.create');
    Route::post('liquidacions', [App\Http\Controllers\LiquidacionController::class, 'store'])
        ->name('liquidacions.store');
    Route::get('liquidacions/{id}/pdf', [App\Http\Controllers\LiquidacionController::class, 'pdf'])
        ->name('liquidacions.pdf');
    Route::delete('liquidacions/{id}', [App\Http\Controllers\LiquidacionController::class, 'destroy'])
        ->name('liquidacions.destroy');


    Route::get('parametrizaciones', [App\Http\Controllers\ParametrizacionController::class, 'edit'])
        ->name('parametrizaciones.edit');
    Route::put('parametrizaciones', [App\Http\Controllers\ParametrizacionController::class, 'update'])
        ->name('parametrizaciones.update');
});
