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
    return view('welcome');
});

Auth::routes();

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


Route::resource('camions', App\Http\Controllers\CamionController::class);
Route::delete('camions/documentos/{documento}', [App\Http\Controllers\CamionController::class, 'destroyDocumento'])
    ->name('camions.documentos.destroy');


Route::resource('productos', App\Http\Controllers\ProductoController::class);


Route::resource('proveedors', App\Http\Controllers\ProveedorController::class);


Route::resource('ordenCargas', App\Http\Controllers\OrdenCargaController::class);
Route::get('ordenCargas/{id}/pdf', [App\Http\Controllers\OrdenCargaController::class, 'pdf'])
    ->name('ordenCargas.pdf');
